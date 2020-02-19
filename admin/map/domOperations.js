
function setActive(s) {
    var arr = ['addDefib', 'showDefibs', 'showReports', 'makeAlert'];
    console.log("arr init");
    arr.forEach(function (item) {
        if (item == s) {
            document.getElementById(s).classList.add("active");
            console.log("active set");
        } else {
            if (document.getElementById(item).classList.contains("active")) {
                document.getElementById(item).classList.remove("active");
                console.log("active remove from old");
            }
        }
    });
}
function addDefib() {
    //deActiveIfActive
    if (document.getElementById('addDefib').classList.contains("active")) {
        document.getElementById('addDefib').classList.remove("active");
        closeAddDefibCard();
    } else {
        setActive("addDefib");
        document.getElementById("addDefibForm").reset();
        var elements = ['address', 'city', 'state', 'country', 'latitude', 'longitude'];

        elements.forEach(function (item) {
            if (item == 'state') {
                if (!(localStorage.getItem(item) == 'undefined')) {

                    document.getElementById('city').value = localStorage.getItem(item);
                }
            } else {
                if (!(localStorage.getItem(item) == 'undefined')) {
                    console.log(localStorage.getItem(item));
                    document.getElementById(item).value = localStorage.getItem(item);
                }
            }

        });
        document.getElementById("addDefibCard").style.visibility = "visible";
    }


}
function showDefibs() {
    setActive("showDefibs");
    console.log("showDefibs()");
}
function showReports() {
    setActive("showReports");
    console.log("showReports()");
}
function makeAlert() {
    setActive("makeAlert");
    console.log("makeAlert()");
}
function closeAddDefibCard() {
    document.getElementById("addDefibCard").style.visibility = "hidden";
    setActive("addDefibForm");
}

