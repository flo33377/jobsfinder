<?php 

session_start();

require("../mainFunctions.php");

$userId = $_SESSION['user_id'] ?? NULL;

// si user pas connecté
if ($userId === NULL) {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode(["error" => "Non authentifié"]);
    exit;
}

try {
    $newOffersCount = globalDBUpdateForUser();
    
    // Si l'import s'est bien déroulé, on remet le user en "active"
    // (couvre le cas où le compte était en pause et où l'utilisateur relance manuellement)
    setStatusForUser($userId, 'done');
    
    header('Content-Type: application/json');
    echo json_encode(["inserted" => $newOffersCount]);
    
} catch (Throwable $e) {
    error_log("Erreur import manuel user {$userId}: " . $e->getMessage());
    
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(["error" => "Une erreur s'est produite lors de l'import."]);
}


?>