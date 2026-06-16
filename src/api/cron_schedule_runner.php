<?php 

/*
BUT DU FICHIER :
=> Aller chercher les users qui existent et les placer
 dans la file d'attente (table import_queue) pour attendre 
qu'on lance leur import
*/

require(__DIR__ . '/../mainFunctions.php');

$pdo = connect();

// Remet en "pending" uniquement les users "done" ou sans statut
// Les users "pending" ou "running" ne sont pas touchés
$pdo->exec("
    UPDATE jobsfinder_users 
    SET import_status = 'pending', 
        import_started_at = NULL, 
        import_finished_at = NULL
    WHERE import_status = 'done' OR import_status IS NULL
");


?>