<?php
/**
 * Created by PhpStorm.
 * User: Bruce
 * Date: 04-26-2019
 * Time: 7:31 PM

 * Pair Programming #3 Pets3 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


//Require autoload
require_once('vendor/autoload.php');
require_once('model/validation-functions.php');

//Create an instance of the Base Class
$f3 = Base::instance();
//Turn on Fat-Free error reporting
$f3->set('DEBUG', 3);

$f3->set('colors', array('red', 'green', 'blue'));



//Define a default route
$f3->route('GET /', function() {
    echo '<h1>My Pets3</h1>';
    echo "<a href='order'>Order a pet</a>";
});

//Define a route that renders form1.html to get a valid animal type
$f3->route('GET|POST /order', function($f3) {
    $_SESSION=array();
    $template = new Template();
    //check if $POST even exists, then check animal type
    if (isset($_POST['animal'])) {
       //check valid animal type
       if (validString($_POST['animal'])) {
        $_SESSION['animal'] = $_POST['animal'];
        $f3->reroute('/order2');
       }
       else
       {
           //instantiate an error array with message
           $f3->set("errors['animal']", "Please enter an alpha character animal");
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

$f3->route('GET|POST /results', function() {
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