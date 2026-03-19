<?php

// id DB
include('envir/sqltable.php');


/* Fonction pour se connecter à la DB */

function connect(): PDO { // se connecte à la DB local ou en prod
    if($_SERVER["SERVER_PORT"] === "5000") {
        // partie locale
        $dbpath = __DIR__ . "/db/jobsfinder_jobs_local.db";
        try {
            $mysqlClient = new PDO("sqlite:{$dbpath}");
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else {
        // partie prod
        try {
            $mysqlClient = new PDO(
                DB_HOST,
                DB_ID,
                DB_PW
            );
        } catch(Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
        }

    return $mysqlClient;
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
