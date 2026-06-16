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

// CRITERIAS = Page critères/expressions clés
define("CRITERIAS", __DIR__ . "/content/criterias.php");

// PARAMETERS = Page paramètres
define("PARAMETERS", __DIR__ . "/content/parameters.php");


    // Variables de pages

    // setting des param par défaut
$page = "home"; // chemin du routeur par défaut => cas HP
$currentPage = "offers"; // page actuelle pour effet active dans le menu



    // Routeur

// récupération de la méthode de requête utilisée
$method = $_SERVER['REQUEST_METHOD'];

// switch routeur
switch ($method) {
    case "POST":
        if(!empty($_POST)) {
            if(isset($_POST['post_add_exp'])) {
            $page = "add_expression"; // input caché pour ajouter nouvelle expression
            }

            if(isset($_POST['post_erase_exp_id']) && is_numeric($_POST['post_erase_exp_id'])) { // demande de suppression d'une exp
                $page = "delete_criteria" ;
            }

            if(isset($_POST['post_save_reporting_link']) && !empty($_POST['reporting_link'])) {
                $page = "save_reporting_link"; // input caché pour sauvegarder le lien de suivi
            }

            if(isset($_POST['post_save_cv_link']) && !empty($_POST['cv_link'])) {
                $page = "save_cv_link"; // input caché pour sauvegarder le lien de l'espace CV
            }

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

        if(isset($_GET['mode']) && ($_GET['mode'] === "criterias") && (!isset($_GET['delete_exp']))) { // accès à la page de de déf des critères
            $page = "criterias" ;
        }

        if(isset($_GET['mode']) && ($_GET['mode'] === "parameters")) { // accès à la page de paramètres
            $page = "parameters" ;
        }

        if(isset($_GET['action']) && ($_GET['action'] === "disconnect")) { // demande de deconnexion user
            $page = "disconnect" ;
        }
        break;
}

    // Check d'authentification

$isAuth = isset($_SESSION['user_id']);
$isCallback = isset($_GET['mode']) && $_GET['mode'] === 'callback';
$isLogin = isset($_GET['mode']) && $_GET['mode'] === 'login';

if (!$isAuth && !$isCallback && !$isLogin) {
    // si tentative d'accéder à une page, enregistre l'erreur
    $page !== "home" ? $error = "forbidden_access" : $error = NULL;
    // afficher la page login
    $page = "login";
}



    // Roads
switch($page){
    case "login" : // demande d'auth car pas co
        $content = LOGIN;
        break;

    case "home" : // cas par défaut => HP du site
        if(isset($_SESSION['user_id'])) {
            $content = HOME;
            $allJobsArray = getAllJobsByUser($_SESSION['user_id']);
            $userKeywords = getKeywordsByUserId($_SESSION['user_id'], "key");
        } else {
            // => pas de user_id, normalement ne devrait pas être possible mais
            // checker quand même ce cas particulier
            $content = LOGIN;
            $error = "forbidden_access";
        }
        break;
    
    case "cv_storage" : // affichage de la page consultation des cv
        $content = CV_STORAGE;
        $currentPage = "cv";
        break;
    
    case "log_diary" : // affichage du journal de log
        $content = LOG_DIARY;
        $currentPage = "logs";
        break;

    case "criterias" : // accès à la page paramètres
        // Récupère et nettoie la notif de session si elle existe
        if (!empty($_SESSION['notif_message'])) {
            $server_notif_message = $_SESSION['notif_message'];
            $server_notif_type = $_SESSION['notif_type'];
            unset($_SESSION['notif_message']);
            unset($_SESSION['notif_type']);
        }
        $content = CRITERIAS;
        $userKeywords = getKeywordsByUserId($_SESSION['user_id'], "key");
        $userBlacklists = getKeywordsByUserId($_SESSION['user_id'], "blacklist");
        $currentPage = "criterias";
        break;
    
    case "add_expression" : // demande de d'ajout de nouvelle d'expression
        // check que le type d'exp à créer est cohérent
        if(!isset($_POST['post_add_type']) || (($_POST['post_add_type'] !== "key") && ($_POST['post_add_type'] !== "blacklist"))) {
            $_SESSION['notif_message'] = "Une erreur s'est produite. Merci de ré-essayer plus tard.";
            $_SESSION['notif_type'] = "error";
        } else {
            if($_POST['post_add_type'] == "key") { // récup le nombre d'expression en fonction du type
                $expCount = count(getKeywordsByUserId($_SESSION['user_id'], "key"));
            } else {
                $expCount = count(getKeywordsByUserId($_SESSION['user_id'], "blacklist"));
            }
            if($expCount >= 20) {
                // si le nbr max d'expression est atteint => erreur
                $_SESSION['notif_message'] = "Vous avez atteint le nombre maximum d'expressions.";
                $_SESSION['notif_type'] = "error";
            } else {
                // si nbr ok => effectue l'opération
                $successAddingOperation = createNewExpressionToUser($_SESSION['user_id'], $_POST['exp_add_name'], $_POST['post_add_type']);
                if($successAddingOperation) {
                    // si opération réussi => notif de succès
                    $_SESSION['notif_message'] = "Expression ajoutée.";
                    $_SESSION['notif_type'] = "success";
                } else {
                    // si échec de l'opération => erreur
                    $_SESSION['notif_message'] = "Une erreur s'est produite. Merci de ré-essayer plus tard.";
                    $_SESSION['notif_type'] = "error";
                };
            }
        };
        header('Location: ' . BASE_URL . '?mode=criterias');
        exit;
    
    
    case "delete_criteria" : // demande de suppression d'expression
        // check si user_id correspond bien au propriétaire de l'expression
        $match = checkMatchBetweenKeyAndUserId($_POST['post_erase_exp_id']);
        if(!$match) {
            // si le user n'est pas owner de l'expression => échec
            $_SESSION['notif_message'] = "Une erreur s'est produite. Merci de ré-essayer plus tard.";
            $_SESSION['notif_type'] = "error";
        } else {
                // s'il est owner de l'expression, poursuit
            $erasingResult = eraseKeyFromDB($_POST['post_erase_exp_id']);
            $_SESSION['notif_message'] = $erasingResult
            ? "Expression supprimée avec succès." // si succès
            : "Une erreur s'est produite. Merci de ré-essayer plus tard."; // sinon si échec
            $_SESSION['notif_type'] = $erasingResult ? "success" : "error";
        }
        header('Location: ' . BASE_URL . '?mode=criterias');
        exit;
    

    case "parameters" : // accès à la page paramètres
        // Récupère et nettoie la notif de session si elle existe
        if (!empty($_SESSION['notif_message'])) {
            $server_notif_message = $_SESSION['notif_message'];
            $server_notif_type = $_SESSION['notif_type'];
            unset($_SESSION['notif_message']);
            unset($_SESSION['notif_type']);
        }
        $content = PARAMETERS;
        $userKeywords = getKeywordsByUserId($_SESSION['user_id'], "key");
        $currentPage = "parameters";
        break;
    
    case "save_reporting_link" : // tentative de sauvegarde d'un lien de suivi des candidatures
        if(!filter_var($_POST['reporting_link'], FILTER_VALIDATE_URL)) {
            $_SESSION['notif_message'] = "L'URL saisie n'est pas valide.";
            $_SESSION['notif_type'] = "error";
        } else {
            $successChangeUrl = changeURLInDBByUserId($_SESSION['user_id'], $_POST['reporting_link'], "reporting_link");
            if($successChangeUrl) {
                // si opération réussi => notif de succès
                $_SESSION['notif_message'] = "URL modifiée.";
                $_SESSION['notif_type'] = "success";
                $_SESSION['reporting_link'] = $_POST['reporting_link'];
            } else {
                // si opération échouée => notif d'échec
                $_SESSION['notif_message'] = "Une erreur s'est produite. Merci de ré-essayer plus tard.";
                $_SESSION['notif_type'] = "error";
            }
        };
        header('Location: ' . BASE_URL . '?mode=parameters');
        exit;

    case "save_cv_link" : // tentative de sauvegarde d'un lien d'espace cv user
        if(!filter_var($_POST['cv_link'], FILTER_VALIDATE_URL)) {
            $_SESSION['notif_message'] = "L'URL saisie n'est pas valide.";
            $_SESSION['notif_type'] = "error";
        } else {
            $successChangeUrl = changeURLInDBByUserId($_SESSION['user_id'], $_POST['cv_link'], "cv_link");
            if($successChangeUrl) {
                // si opération réussi => notif de succès
                $_SESSION['notif_message'] = "URL modifiée.";
                $_SESSION['notif_type'] = "success";
                $_SESSION['cv_link'] = $_POST['cv_link'];
            } else {
                // si opération échouée => notif d'échec
                $_SESSION['notif_message'] = "Une erreur s'est produite. Merci de ré-essayer plus tard.";
                $_SESSION['notif_type'] = "error";
            }
        };
        header('Location: ' . BASE_URL . '?mode=parameters');
        exit;

    case "disconnect" : // deconnexion du user
        unset($_SESSION['oauth2state'], 
        $_SESSION['user_id'], 
        $_SESSION['user_email'], 
        $_SESSION['reporting_link'], 
        $_SESSION['cv_link']);
        header('Location: ' . BASE_URL);
}



?>