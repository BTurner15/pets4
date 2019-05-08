<?php
/**
 * Pair Programming IV with Bruce Turner & Brian Kiehn
 * GitHub Repo:https://github.com/BTurner15/pets4
 * File: index.php
 * Last Modification:
 *     - Tuesday May 7 2019
 *     - Time: 5:50 pm
 *     - Version 4.0
 * ok for PP Week 5 with Brian, here is a
 * 1. On the first form, add a quantity field. Display an error message
 * * for a quantity that is empty, non-numeric, or less than one.
 * Make your form sticky. Ok, I am doing #2 because I need to know it!
 */

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require autoload
require_once('vendor/autoload.php');
require_once('model/validation-functions.php');

//Create an instance of the Base Class
$f3 = Base::instance();

//Turn on Fat-Free error reporting
$f3->set('DEBUG', 3);

//Define arrays
$f3->set('colors', array('Red', 'Green', 'Blue','Orange', 'Yellow'));
$f3->set('accessories', array('one week of food', 'feeding station', 'user manual', 'training & care booklet'));

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
        }
        else if ( validString($_POST['animal']) && !validQuantity($_POST['qty']) ) {
           $_SESSION['animal'] = $_POST['animal'];
           $f3->set("errors['qty']", 'Please enter an Quantity >= 1 of your pet(s)');
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

/* Define a order2 route that uses POST for color selection, and
 * provision for optional accessory items via checkboxes
 * We will consider the order completed if a color is selected via pull-down menu
 * and NOT require the selection of any optional accessories
 *
 *
 */
//Display form2
$f3->route('GET|POST /order2', function($f3) {
    // if order to get here we have 'animal' and 'qty' validated & in $_SESSION

    $template = new Template();
    $access = $_POST['access'];

    if (isset($_POST['color'])) {
        //check valid color, we will not allow reroute with unspecified color
        if (validColor($_POST['color'])) {
            // we are done. Save, see if options are chosen, save as appropriate
            // and then reroute either way
            $_SESSION['color'] = $_POST['color'];
            if (empty($access)) {
                $_SESSION['access'] = "No optional accessories selected";
            } else {
                $f3->set('access', $access);
                $_SESSION['access'] = implode(', ', $access);
            }
            $f3->reroute('/results');
        }
        else {
            //instantiate an error array with message
            $f3->set("errors['color']", "color either not selected, or spoofing attempt");
            //save optional accessories if present
            if (empty($access)) {
                $_SESSION['access'] = "No optional accessories selected";
            } else {
                $f3->set('access', $access);
                $_SESSION['access'] = implode(', ', $access);
            }
        }
    }
    else {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // this the 1st ever submission of form? if so, do NOT display wrong color error
        }
        else {
            $f3->set("errors['color']", "color either not selected, or spoofing attempt");
        }
    }
    echo $template->render('views/form2.html');
});


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