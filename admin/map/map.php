<?php
session_start();
$url = "../../login/login.php";
if (isset($_SESSION)) {
    if (isset($_SESSION['logged_in']) != "Active") {
        redirect($url);
    }
} else {
    redirect($url);
}

function redirect($url) {
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
        exit;
    }
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
        <title>AEDMapping Admin Dashboard</title>
        <link href="https://fonts.googleapis.com/css?family=Kulim+Park&display=swap" rel="stylesheet"> 
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="map.css">
        <!-- FontAwesomeLink -->
        <script src="https://kit.fontawesome.com/9ff6e74205.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <div id="test"></div>
        <div id="addDefibCard" class="card centerCard overflow-auto" style="visibility: hidden;">
            <div class="card-body m-auto">
                <form class="m-auto" id="addDefibForm" method="post">
                    <button onclick="closeAddDefibCard();" type="button" class="close mb-2" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="form-group mb-2">
                        <label style="font-size: 30px">Add a new Defibrillator</label>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="name1" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="description" id="description" placeholder="Description" value="desc1" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="address" id="address" placeholder="Address" required>
                        <small class="form-text text-muted">Address from map</small>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="city" id="city" placeholder="City/State" required>
                        <small class="form-text text-muted">City or State from map</small>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="country" id="country" placeholder="Country" required>
                        <small class="form-text text-muted">Country from map</small>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Latitude" required>
                        <small class="form-text text-muted">Latitude from map</small>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Longitude" required>
                        <small class="form-text text-muted">Longitude from map</small>
                    </div>

                    <button type="submit" class="btn btn-primary mb-2">Add</button>
                </form>
            </div>
        </div>
        <div id="map"></div>
        <!-- Nav Start -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="z-index: 1;position:relative;">
            <a class="navbar-brand" href="#">AEDMapping</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index/index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="map.php">Map</a>
                    </li>

                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <button class="btn btn-primary my-2 my-sm-0" onclick="location.href = '../logout.php'" type="button">Logout</button>
                </form>
            </div>
        </nav>
        <!-- Nav End -->
        <div class="list-group" style="width: 18rem;z-index: 1;">
            <ul class="list-group list-group-flush">
                <a id="addDefib" href="#" onclick="addDefib();" class="list-group-item list-group-item-action">Add Defibrallator</a> 
                <a id="showDefibs" href="#" onclick="showDefibs();" class="list-group-item list-group-item-action">Show Defibrallators</a> 
                <a id="showReports" href="#" onclick="showReports();" class="list-group-item list-group-item-action">Show Reports</a>
                <a id="makeAlert" href="#"<?php
                if (($_SESSION['role'] == 'munic')) {
                    echo 'hidden';
                }
                ?> onclick="makeAlert();" class="list-group-item list-group-item-action">Make Alert</a>
            </ul>                               <!-- change role to another one-->
        </div>

        <script>
            var showDefibJqueryFunction;
            var map;
            var geocoder;
            var markers = [];
            function initMap() {
                var marker = new google.maps.Marker();
                /*     var map = new google.maps.Map(document.getElementById('map'), {
                 center: {lat: 39.157409286886356, lng: 21.715644649155138},
                 zoom: 1
                 }); */
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 39.157409286886356, lng: 21.715644649155138},
                    zoom: 1
                });
                geocoder = new google.maps.Geocoder();
                var infoWindow = new google.maps.InfoWindow();
                /* var geocoder = new google.maps.Geocoder;
                 var infoWindow = new google.maps.InfoWindow; */
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        infoWindow.setPosition(pos);
                        infoWindow.setContent('Location found.');
                        infoWindow.open(map);
                        map.setCenter(pos);
                        map.setZoom(10);
                    }, function () {
                        handleLocationError(true, infoWindow, map.getCenter());
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
                google.maps.event.addListener(map, 'click', function (event) {

                    console.log("Latitude : " + event.latLng.lat());
                    console.log("Longitude : " + event.latLng.lng());
                    geocodeLatLng(geocoder, map, infoWindow, event.latLng.lat(), event.latLng.lng(), marker);
                });
            }

            function geocodeLatLng(geocoder, map, infoWindow, latitude, longitude, marker) {
                var latlng = {lat: latitude, lng: longitude};
                geocoder.geocode({'location': latlng}, function (results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            map.setZoom(11);
                            if (markers.length > 0) {
                                markers[0].setMap(null);
                                markers = [];
                            }

                            var marker = new google.maps.Marker({
                                position: latlng,
                                map: map
                            });
                            markers.push(marker);
                            markers[0].setMap(map);
                            localStorage.clear();
                            var result = results[0].address_components;
                            var geoResults = [];
                            result.forEach(function (item) {
                                if (item.types[0] == "administrative_area_level_1") {
                                    localStorage.setItem("state", item.long_name);
                                }
                                if (item.types[0] == "country") {

                                    localStorage.setItem("country", item.long_name);
                                }
                                if (item.types[0] == "locality") {

                                    localStorage.setItem("city", item.long_name);
                                }
                                if (item.types[0] == "route") {

                                    localStorage.setItem("address", item.long_name);
                                }
                            });
                            if (localStorage.getItem("state") === null && localStorage.getItem("city") === null) {
                                result.forEach(function (item) {
                                    if (item.types[0] == "administrative_area_level_5" || item.types[0] == "administrative_area_level_3")
                                        localStorage.setItem("city", item.long_name);
                                });
                            }
                            localStorage.setItem("latitude", latitude);
                            localStorage.setItem("longitude", longitude);
                            result.forEach(function (item) {
                                console.log(item);
                            });
                            infoWindow.setContent(results[0].formatted_address);
                            infoWindow.open(map, marker);
                        } else {
                            window.alert('No results found');
                        }

                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }

                });
            }

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.setContent(browserHasGeolocation ?
                        'Error: Please allow location permission and reload page.' :
                        'Error: Your browser doesn\'t support geolocation.');
                infoWindow.open(map);
            }
            function showDefibrillatorsFromDb(defibs, report) {
                var reports = [];
                if (Array.isArray(report)) {
                    for (var b = 0; b < report.length; b++) {
                        reports[b] = report[b];
                    }
                } else {
                    reports.push(report);
                }


                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null); //Reset map
                }
                var defibMarkers = [];
                var defibInfoWindows = [];
                var reportIconUrl = 'http://maps.google.com/mapfiles/kml/shapes/caution.png';
                var iconR = {
                    caution: {
                        reportIconUrl
                    }
                };
                var reportedDefibs = [];
                for (var r = 0; r < reports.length; r++) {
                    for (var j = 0; j < defibs.length; j++) {
                        if (reports[r].defibrillator_id == defibs[j].id) {
                            reportedDefibs.push(defibs[j].id); //Declare reported defibs
                            var marker = new google.maps.Marker({
                                position: new google.maps.LatLng(defibs[j].latitude, defibs[j].longitude),
                                map: map,
                                icon: reportIconUrl
                            });
                            defibMarkers.push(marker);
                            var contentString =
                                    '<p>Name : ' + defibs[j].name + '<br>' + //DEĞİŞİYOR
                                    'Description : ' + defibs[j].description + '<br>' +
                                    'Address : ' + defibs[j].address + '<br>' +
                                    'State/City : ' + defibs[j].city + '<br>' +
                                    'Country : ' + defibs[j].country + '<br>' +
                                    '---------------<br>' +
                                    'Report Type : ' + reports[r].type + '<br>' +
                                    'Report Comment : ' + reports[r].comment + '<br>' +
                                    'Report Mail : ' + reports[r].mail + '<br' + '</p>';
                            var infoWindow = new google.maps.InfoWindow({//ORTAK
                                content: contentString
                            });
                            defibInfoWindows.push(infoWindow); // Fill markers infowindow array //ORTAK
                            continue; // Reported defib found, so continue for other reported defibrillator id -> Because of one to one mapping
                        }

                    }
                }
                for (var k = 0; k < defibs.length; k++) {
                    if (reportedDefibs.length > 0) {
                        if (reportedDefibs.includes(defibs[k].id)) {
                            continue;
                        }
                    }
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(defibs[k].latitude, defibs[k].longitude),
                        map: map
                    });
                    defibMarkers.push(marker);
                    var contentString = '<p>Name : ' + defibs[k].name + '<br>' + //DEĞİŞİYOR
                            'Description : ' + defibs[k].description + '<br>' +
                            'Address : ' + defibs[k].address + '<br>' +
                            'State/City : ' + defibs[k].city + '<br>' +
                            'Country : ' + defibs[k].country + '<br></p>';
                    var infoWindow = new google.maps.InfoWindow({//ORTAK
                        content: contentString
                    });
                    defibInfoWindows.push(infoWindow);
                }
                markers = [];
                for (var q = 0; q < defibMarkers.length; q++) {
                    defibMarkers[q].setMap(map);
                /*    google.maps.event.addListener(defibMarkers[q], 'click', function () {
                        defibInfoWindows[q].open(map, defibMarkers[q]);
                    });*/

                    //infoWindow.setContent("Name : " + response[k].name + " Description : " + response[k].description + " Address : " + response[k].address + ", " + response[k].city + ", " + response[k].country);
                    defibInfoWindows[q].open(map, defibMarkers[q]);
                    //Fill global markers array with defibMarkers array's items(for page switch(show reports vs))
                    markers[q] = defibMarkers[q];
                }
                /*
                 var r = 0;
                 for (var j = 0; j < defibs.length; j++) { // Fill markers
                 if (r < reports.length) {
                 if (defibs[j].id == reports[r].defibrillator_id) {
                 var marker = new google.maps.Marker({
                 position: new google.maps.LatLng(defibs[j].latitude, defibs[j].longitude),
                 map: map,
                 icon: iconR['caution']
                 });
                 } else {
                 
                 }
                 r++;
                 }
                 var marker = new google.maps.Marker({
                 position: new google.maps.LatLng(defibs[j].latitude, defibs[j].longitude),
                 map: map                                                //DEĞİŞİYOR
                 });
                 
                 defibMarkers[j] = marker; // Fill markers array             //ORTAK
                 var contentString = '<p>Name : ' + defibs[j].name + '<br>' + //DEĞİŞİYOR
                 'Description : ' + defibs[j].description + '<br>' +
                 'Address : ' + defibs[j].address + '<br>' +
                 'State/City : ' + defibs[j].city + '<br>' +
                 'Country : ' + defibs[j].country + '<br></p>';
                 var infoWindow = new google.maps.InfoWindow({//ORTAK
                 content: contentString
                 });
                 defibInfoWindows[j] = infoWindow; // Fill markers infowindow array //ORTAK
                 }
                 markers = [];
                 for (var k = 0; k < defibMarkers.length; k++) {
                 defibMarkers[k].setMap(map);
                 //infoWindow.setContent("Name : " + response[k].name + " Description : " + response[k].description + " Address : " + response[k].address + ", " + response[k].city + ", " + response[k].country);
                 defibInfoWindows[k].open(map, defibMarkers[k]);
                 //Fill global markers array with defibMarkers array's items(for page switch(show reports vs))
                 markers[k] = defibMarkers[k];
                 }*/



            }
            function showReportsFromDb(response) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
                for (var j = 0; j < response.length; j++) {
                    var latlng = {lat: response[i].latitude, lng: response[i].longitude};
                    var marker = new google.maps.Marker({
                        position: latlng,
                        map: map
                    });
                    marker.setMap(map);
                    infoWindow.setContent("Reported! " + "Name : " + response[i].name + " Type : " + response[i].type + " Comment :  " + response[i].comment + " Mail : " + response[i].mail);
                    infoWindow.open(map, marker);
                }
            }

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<YOUR API KEY>4&callback=initMap" async defer></script>
        <!-- Footer -->
        <div class="footer">
            <p id="digital-clock"> AEDMapping Admin Dashboard | Role : EKAB | System Time :</p><div>
                <!-- Footer -->
                <script src="../time.js"></script>
                <script src="domOperations.js"></script>
                <script
                    src="https://code.jquery.com/jquery-3.4.1.min.js"
                    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous"></script>
                <script>

            $(document).ready(function () {


                $('#addDefibForm').submit(function (e) {

                    e.preventDefault();
                    var name = $("#name").val();
                    var description = $("#description").val();
                    var address = $("#address").val();
                    var city = $("#city").val();
                    var country = $("#country").val();
                    var latitude = $("#latitude").val();
                    var longitude = $("#longitude").val();
                    var request = {'name': name, 'description': description, 'address': address, 'city': city, 'country': country, 'latitude': latitude, 'longitude': longitude};
                    $.ajax({

                        type: "POST",
                        url: "<YOUR WEBSITE LINK>/api/defib/add.php",
                        dataType: "json",
                        data: JSON.stringify(request),
                        success: function (response) {
                            var code = response.code;
                            var message = response.msg;
                            console.log(message);
                            if (code == 200) {
                                alert("Success: " + message);
                                document.getElementById("addDefibCard").style.visibility='hidden';
                            } else {
                                alert("Error" + message);
                            }
                        },
                        complete: function (response) {

                        }

                    });
                });
                $('#showDefibs').click(function (e) {
                    e.preventDefault();
                    $.ajax({

                        type: "GET",
                        url: "<YOUR WEBSITE LINK>/api/defib/findAll.php",
                        dataType: "json",
                        success: function (response) {
                            if (response.code === undefined) {
                                for (var i = 0; i < response.length; i++) {
                                    console.log("id : " + response[i].id);
                                    console.log("name : " + response[i].name);
                                    console.log("description : " + response[i].description);
                                    console.log("address : " + response[i].address);
                                    console.log("city : " + response[i].city);
                                    console.log("country : " + response[i].country);
                                    console.log("latitude : " + response[i].latitude);
                                    console.log("longitude : " + response[i].longitude);
                                    console.log("****************************");
                                    //showDefibrillatorsFromDb(response);
                                    findAllReportsWithDefibs(response);
                                }

                            } else {
                                alert(response.code + " " + response.msg);
                            }

                        }

                    });
                });
                
                function findAllReportsWithDefibs(defibs) {
                    $.ajax({

                        type: "GET",
                        url: "<YOUR WEBSITE LINK>/api/report/findAll.php",
                        dataType: "json",
                        success: function (response) {
                            if (response.code === undefined) {
                                if (response.length == 0) {
                                    response = [];
                                    showDefibrillatorsFromDb(defibs, response);
                                } else {
                                    for (var i = 0; i < response.length; i++) {
                                        console.log("id : " + response[i].id);
                                        console.log("type : " + response[i].type);
                                        console.log("comment : " + response[i].comment);
                                        console.log("mail : " + response[i].mail);
                                        console.log("defibrillator_id : " + response[i].defibrillator_id);
                                        console.log("****************************");
                                        showDefibrillatorsFromDb(defibs, response);
                                    }
                                }



                            } else {
                                alert(response.code + " " + response.msg);
                            }

                        }

                    });
                }
            }
            );
                </script>
                <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
                </body>
                </html>
