<?php

    // debogger

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

    // dependancies
include('mainFunctions.php');

    // Session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(86400); // durée du cookie de session = 24h
    session_start(); // ne démarre la session que s'il n'y en a pas encore
}

/* var_dump($_SESSION); */
/* unset($_SESSION['user_id']); */
/* session_destroy(); */


    // Constantes

// base_url = lien vers la HP basé sur le serveur utilisé 
define("BASE_URL", ($_SERVER["SERVER_PORT"] === "5000") ? "http://localhost:5000/" : "https://fneto-prod.fr/jobsfinder/");

// Home = Page d'accueil
define("HOME", __DIR__ . "/content/home.php");

// LOGIN = Formulaire d'auth
define("LOGIN", __DIR__ . "/content/login.php");

// CV_STORAGE = Bibliothèque de CV
define("CV_STORAGE", __DIR__ . "/content/cv_storage.php");

// LOG_DIARY = Journal de log
define("LOG_DIARY", __DIR__ . "/content/log_diary.php");

// PARAMETERS = Page paramètres
define("PARAMETERS", __DIR__ . "/content/parameters.php");


    // Variables de pages

    // setting des param par défaut
$page = "home"; // chemin du routeur par défaut => cas HP



    // Authentification

$isAuth = isset($_SESSION['user_id']);
$isCallback = isset($_GET['mode']) && $_GET['mode'] === 'callback';
$isLogin = isset($_GET['mode']) && $_GET['mode'] === 'login';

if (!$isAuth && !$isCallback && !$isLogin) {
    // afficher la page login
    $page = "login";
}


    // Routeur

// récupération de la méthode de requête utilisée
$method = $_SERVER['REQUEST_METHOD'];

// switch routeur
switch ($method) {
    case "POST":
        if (!empty($_POST)) {
            //if(isset($_POST['post_authenticate'])) $page = "check_authenticate"; // input caché post_authenticate
        }
        break;

    case "GET":
        if(isset($_GET['mode']) && ($_GET['mode'] === "login") && 
        !isset($_GET['error'])) { // envoie sur l'auth Google
            require_once './src/auth/googleLogin.php';
        }

        if(isset($_GET['mode']) && ($_GET['mode'] === "callback")) { // retour de l'auth Google
            require_once './src/auth/googleCallback.php';
        }

        if(isset($_GET['mode']) && ($_GET['mode'] === "login") 
        && isset($_GET['error']) && ($_GET['error'] === 'no_account')) { // user n'existe pas en db
            $page = 'login';
            $error = 'no_account';
        }

        if(isset($_GET['mode']) && ($_GET['mode'] === "cv_storage")) { // accès à la biblio de cv
            $page = "cv_storage" ;
        }

        if(isset($_GET['mode']) && ($_GET['mode'] === "log_diary")) { // tentative d'accès au sommaire d'un cours
            $page = "log_diary" ;
        }

        if(isset($_GET['mode']) && ($_GET['mode'] === "parameters")) { // accès à la page de paramètres
            $page = "parameters" ;
        }

        if(isset($_GET['action']) && ($_GET['action'] === "disconnect")) { // demande de deconnexion user
            $page = "disconnect" ;
        }
        break;
}



    // Roads
switch($page){
    case "login" : // demande d'auth car pas co
        $content = LOGIN;
        break;

    case "home" : // cas par défaut => HP du site
        $content = HOME;
        $allJobsArray = getAllJobs();
        break;
    
    case "cv_storage" : // affichage de la page consultation des cv
        $content = CV_STORAGE;
        break;
    
    case "log_diary" : // affichage du journal de log
        $content = LOG_DIARY;
        break;

    case "parameters" : // accès à la page paramètres
        $content = PARAMETERS;
        break;

    case "disconnect" : // deconnexion du user
        unset($_SESSION['oauth2state'], $_SESSION['user_id'], $_SESSION['user_email']);
        header('Location: ' . BASE_URL);
}



?>