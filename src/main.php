<?php

    // debogger

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

    // dependancies
include('mainFunctions.php');
/* $index_cours = include_once('envir/index-cours.php'); // index des cours */

    // Session
session_set_cookie_params(86400); // durée du cookie de session = 24h
session_start();
/* unset($_SESSION['access_granted']); */


    // Constantes

// base_url = lien vers la HP basé sur le serveur utilisé 
define("BASE_URL", ($_SERVER["SERVER_PORT"] === "5000") ? "http://localhost:5000/" : "https://fneto-prod.fr/jobsfinder/");

// Home = Page d'accueil
define("HOME", __DIR__ . "/content/home.php");



    // Variables de pages

    // setting des param par défaut
$page = "home"; // chemin du routeur par défaut => cas HP
$content = HOME; // const du contenu de la page par défaut


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
        if(isset($_GET['cours']) && ($_GET['cours'] != null)) { // tentative d'accès à un cours
            $page = "display_courses" ;
            $requested_course = $_GET['cours'];
        }
        if(isset($_GET['summary']) && ($_GET['summary'] != null)) { // tentative d'accès au sommaire d'un cours
            $page = "display_summary" ;
            $requested_course = $_GET['summary'];
        }
        break;
}



    // Roads
switch($page){
    case "home" : // cas par défaut => HP du site
        $content = HOME;
        $allJobsArray = getAllJobs();
        break;
}



?>