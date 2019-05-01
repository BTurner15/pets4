<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Bruce
 * Date: 1/18/2019
 * Time: 7:31 PM

 * Pair Programming #3 Friday 18 2019
 */


ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require autoload
require_once('vendor/autoload.php');

//Create an instance of the Base Class
$f3 = Base::instance();

//Turn on Fat-Free error reporting
$f3->set('DEBUG', 3);

//Define a default route
$f3->route('GET /', function() {
    echo '<h1>My Pets</h1>';
    echo "<a href='order'>Order a pet</a>";
});

//Define a order route that renders form1.html
$f3->route('GET /order', function() {
    $view = new Template();
    echo $view->render('views/form1.html');
});

//Define a order2 route that uses POST
//Get the data from form1 and add it to a session variable
//Display form2
$f3->route('POST /order2', function() {
    print_r($_POST);
    $_SESSION['animal'] =    $_POST['animal'];
    $view = new Template();
    echo $view->render('views/form2.html');
});

$f3->route('POST /results', function() {
    print_r($_POST);
    $_SESSION['color'] =    $_POST['color'];
    $view = new Template();
    echo $view->render('views/results.html');
});
//Define a animal type route
$f3->route('GET /@animal', function($f3, $params) {

    $animal = $params['animal'];
    switch($animal) {
        case 'chicken':
        echo 'Cluck!';
            break;
        case 'dog':
            echo 'Woof!';
            break;
        case 'lion':
            echo 'Roar!';
            break;
        case 'pig':
            echo 'Onik!';
            break;
        case 'goose':
            echo 'Honk!';
            break;
        default:
            $f3->error(404);
    }

});
$f3->run();