
<?php

/*
BUT DU FICHIER :
=> va chercher les users en attente (table import_queue) 
et déclenche leur import perso
*/


require(__DIR__ . '/../mainFunctions.php');

$pdo = connect();


// Étape 1 : récupère le prochain user pending
$stmt = $pdo->query("
    SELECT user_id FROM jobsfinder_users 
    WHERE import_status = 'pending' 
    ORDER BY user_id ASC 
    LIMIT 1
");
$nextUser = $stmt->fetch();

if (!$nextUser) exit; // queue vide, rien à faire

$userId = $nextUser['user_id'];

// Étape 2 : passe ce user en running
$stmt = $pdo->prepare("
    UPDATE jobsfinder_users 
    SET import_status = 'running', import_started_at = NOW()
    WHERE user_id = ? AND import_status = 'pending'
");
$stmt->execute([$userId]);

if ($stmt->rowCount() === 0) exit; // queue vide, rien à faire

// Étape 3 : import
$keywords     = array_column(getKeywordsByUserId($userId, 'key'), 'expression');
$blockedWords = array_column(getKeywordsByUserId($userId, 'blacklist'), 'expression');

if (!empty($keywords)) {
    cleanupJobsInDB($userId);
    importAdzunaOffersForUser($userId, $keywords, $blockedWords);
    importFranceTravailOffersForUser($userId, $keywords, $blockedWords);
}

// Étape 4 : marque comme terminé
$stmt = $pdo->prepare("
    UPDATE jobsfinder_users 
    SET import_status = 'done', import_finished_at = NOW() 
    WHERE user_id = ?
");
$stmt->execute([$userId]);

?>

