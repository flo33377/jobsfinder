<?php

// id DB
include('envir/sqltable.php');
include('envir/api.php');


/* Fonction pour se connecter à la DB */

function connect(): PDO { // se connecte à la DB local ou en prod
    try {
        if(defined(('ENV') && ENV === 'local')) {
            // partie locale
            $dbpath = __DIR__ . "/db/jobsfinder_jobs_local.db";
            $mysqlClient = new PDO("sqlite:{$dbpath}");
        } else {
            // partie prod
                $mysqlClient = new PDO(DB_HOST, DB_ID, DB_PW);
        }
        return $mysqlClient;
    
    } catch(Exception $e) {
        error_log("Connexion BDD échouée : " . $e->getMessage());
        exit(0); // sortie sans echo ou erreur fatale 
    }
}

/* Fonction(s) de debeug */

// Ecriture des logs

function writeLog($message) {
    $logFile = __DIR__ . '/logs/cron.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}


/* Fonction(s) pour get des infos de la DB */

function getAllJobs() : array { // renvoie toutes les jobs en BDD
    $SQLGetAllJobs = "SELECT * FROM jobsfinder_jobs 
    ORDER BY posted_at DESC";
    $pdo = connect();
    $stmtGetAllJobs = $pdo->prepare($SQLGetAllJobs);
    $stmtGetAllJobs->execute([]);

    return $stmtGetAllJobs->fetchAll();
}


/* Fonction(s) pour update des choses en DB */

function changeOfferStatus(int $id, string $status): bool { // update le statut d'une offre en DB
    $SQLChangeOfferStatus = "UPDATE jobsfinder_jobs 
    SET status = :status WHERE id = :id";
    $pdo = connect();
    try {
        $stmtChangeOfferStatus = $pdo->prepare($SQLChangeOfferStatus);
        $stmtChangeOfferStatus->execute([
            'status' => $status,
            'id' => $id
        ]);
        return true;

    } catch(Exception $e) {
        return false;
    };
}


?>
