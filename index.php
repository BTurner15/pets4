<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Require autoload
require_once('vendor/autoload.php');
require_once('model/validation-functions.php');

//Create an instance of the Base Class
$f3 = Base::instance();
//Turn on Fat-Free error reporting
$f3->set('DEBUG', 3);

$f3->set('colors', array('red', 'green', 'blue','orange', 'yellow'));

//Define a default route
$f3->route('GET /', function() {
    $_SESSION = array();
    echo '<h1>My Pets 4</h1>';
    echo "<a href='order'>Order a pet</a>";
});

//Define a route that renders form1.html to get a valid animal type
$f3->route('GET|POST /order', function($f3) {
    $_SESSION=array();
    $template = new Template();
    //check if $POST even exists, then check animal type
    if (isset($_POST['animal']) && isset($_POST['qty'])) {
       //check valid animal type
        //print_r($_POST);
        if ( validString($_POST['animal']) && validQuantity($_POST['qty']) ) {
            $_SESSION['animal'] = $_POST['animal'];
            // for Pets IV #1
            $_SESSION['qty'] = $_POST['qty'];
            $f3->reroute('/order2');
            print_r($_SESSION);
        }
        else if ( validString($_POST['animal']) && !validQuantity($_POST['qty']) ) {
           $_SESSION['animal'] = $_POST['animal'];
           $f3->set("errors['qty']", "Please enter an Quantity >= 1 of your pet(s)");
        }
        else if ( !validString($_POST['animal']) && validQuantity($_POST['qty']) ) {
           //instantiate an error array with message
           $_SESSION['qty'] = $_POST['qty'];
           $f3->set("errors['animal']", "Please enter an alpha character animal");
        }
        else if ( !validString($_POST['animal']) && !validQuantity($_POST['qty']) ) {
            $f3->set("errors['animal']", "Please enter an alpha character animal");
            $f3->set("errors['qty']", "Please enter an Quantity >= 1 of your pet(s)");
        }
    }
    echo $template->render('views/form1.html');
});

//Define a order2 route that uses POST for color selection
//Get the data from form1 and add it to a session variable
//Display form2
$f3->route('GET|POST /order2', function($f3) {
     $template = new Template();
    if (isset($_POST['color'])) {
        //check valid color
        if (validColor($_POST['color'])) {
            $_SESSION['color'] = $_POST['color'];
            $f3->reroute('/results');
        }
        else
        {
            //instantiate an error array with message
            $f3->set("errors['color']", "Some kind of color problem (spoofing)");
        }
    }
    echo $template->render('views/form2.html');
});
/* ok for PP Week 5 with Brian, here is a
* 1. On the first form, add a quantity field. Display an error message
 * * for a quantity that is empty, non-numeric, or less than one.
 * Make your form sticky.
 */

$f3->route('GET|POST /results', function() {
    //rerouted via GET, POST array is empty
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