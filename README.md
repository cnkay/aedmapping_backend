# AEDMapping_Backend


AEDMapping projects' backend developed with PHP

### Features

  - Show all defibrillators on map
  - Show reported defibrillators
  - Add new defibrillators


### Backend Side Tech Stack

* [jQuery](https://jquery.com/) - The Write Less, Do More, JavaScript Library.
* [AJAX](https://api.jquery.com/jquery.ajax/) - Perform an asynchronous HTTP (Ajax) request.
* [Google Maps JavaScript API ](https://developers.google.com/maps/documentation/javascript/tutorial) - Lets you customize maps with your own content and imagery for display on web pages and mobile devices.
* [RedBeanPHP](https://www.redbeanphp.com/) - RedBeanPHP is an easy to use ORM for PHP. It's a Zero Config ORM lib that 'automagically' builds your database schema. 
* [Bootstrap](https://getbootstrap.com/) - Build responsive, mobile-first projects on the web with the world’s most popular front-end component library.
* [MySQL](https://www.mysql.com/) - An open-source relational database management system.

#### /db/rb-db-config.php
```php
R::setup('mysql:host=<DATABASE_ADDRESS>;dbname=<DATABASE_NAME>>', '<USERNAME>', '<PASSWORD>');
```
#### /admin/map/map.php
```javascript
...
<script src="https://maps.googleapis.com/maps/api/js?key=<YOUR API KEY>4&callback=initMap" async defer></script>
...
```
##### *Run /db/rb-test.php file for truncate and create database tables automatically! 

### REST Endpoints

| Method | Endpoint | Response |
| ------ | ------ | ------ |
| GET | [/api/user/findAll.php][PlDb] | Returns list of users |
| POST | [/api/user/add.php][PlGh] | Response code and message |
| POST | [/api/user/login.php][PlGd] | Response code and message |
| GET | [/api/defib/findAll.php][PlOd] | Returns list of defibrillators |
| GET | [/api/defib/find.php?id={id}][PlMe] | Returns defibrillator with id |
| POST | [/api/defib/add.php][PlGa] | Response code and message
| POST | [/api/report/add.php][PlGa] | Response code and message

### Screenshots

![Sign In](/images/signin.png)
![Index](/images/index.png)
![Onclick](/images/onclick.png)
![Show Defibrillator](/images/showdefib.png)
![Show Defibrillator with Reports](/images/showdefibwithreport.png)
![Add Defibrillator1](/images/addanewdefib1.png)
![Add Defibrillator2](/images/addanewdefib2.png)


License
----

GNU General Public License v3.0

