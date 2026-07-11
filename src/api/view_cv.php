
<?php

// view_cv.php => sert à la fois visualisation et téléchargement
// Evite un accès direct (permet de bloquer les accès direct par url par sécurité)

session_start();
require(__DIR__ . "/../mainFunctions.php");
// base_url = lien vers la HP basé sur le serveur utilisé 
define("BASE_URL", ($_SERVER["SERVER_PORT"] === "5000") ? "http://localhost:5000/" : "https://fneto-prod.fr/jobsfinder/");


// Unset ci-dessous => pour tester cas denied
/* unset($_SESSION['user_id']); */

if (!isset($_SESSION['user_id'])) {
    $content = __DIR__ . "/content/login.php";
    exit;
}

$userId = $_SESSION['user_id'];

// basename() empêche toute tentative de sortir du dossier user (path traversal)
$filename = basename($_GET['file'] ?? '');

if (empty($filename)) {
    http_response_code(400);
    exit;
}

$filePath = __DIR__ . "/../storage/cv/user-{$userId}/{$filename}";

if (!file_exists($filePath)) {
    http_response_code(404);
    exit;
}

// Mode : "view" (affichage navigateur) ou "download" (téléchargement forcé)
// par défaut mode view
$mode = $_GET['action'] ?? 'view';

// Nom affiché au téléchargement — peut être renommé par l'utilisateur via la popup
$downloadName = !empty($_GET['name']) 
    ? sanitizeCvName($_GET['name']) 
    : str_replace('.pdf.store', '', $filename);

header('Content-Type: application/pdf');

// Si Mode = "download", le télécharge
if ($mode === 'download') {
    header('Content-Disposition: attachment; filename="' . $downloadName . '.pdf"');
} elseif($mode === 'view') {
    // si c'est view, le visualise
    header('Content-Disposition: inline; filename="' . $downloadName . '.pdf"');
} else {
    // sinon, si le user essaie de trafiquer le code, reload et affiche une erreur
    $_SESSION['notif_message'] = "Une erreur s'est produite, merci de ré-essayer.";
    $_SESSION['notif_type'] = "error";
    header('Location: ' . BASE_URL . '?mode=cv_storage');
    exit;
}

readfile($filePath);
exit;


?>
