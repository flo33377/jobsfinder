<?php

// id DB
include_once('envir/sqltable.php');
include_once('envir/api.php');


/* Fonction pour se connecter à la DB */

function connect(): PDO { // se connecte à la DB local ou en prod
    try {
        if(defined('ENV') && ENV === 'local') {
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

/* Fonctions d'authentification */

function getUserByEmail(string $email) { // retourne les infos d'un user s'il existe en base 
    $SQLGetUserByEmail = "SELECT user_id, user_email, reporting_link FROM jobsfinder_users 
    WHERE user_email = :user_email";
    $pdo = connect();
    $stmtGetUserByEmail = $pdo->prepare($SQLGetUserByEmail);
    $stmtGetUserByEmail->execute([
        'user_email' => $email
    ]);

    return $stmtGetUserByEmail->fetch();
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

function formatPostedAt(string $postedAt): string { // renvoie un meilleur format 
    // pour la date de publication des offres
    $posted = new DateTime($postedAt);
    $now = new DateTime();
    $diff = $now->diff($posted)->days;

    if ($diff === 0) return "Postée aujourd'hui";
    if ($diff === 1) return "Postée il y a 1 jour";
    if ($diff <= 3) return "Postée il y a {$diff} jours";
    
    return "Postée le " . $posted->format('d/m');
}


/* Fonction(s) pour update des choses en DB */

function changeOfferStatus(int $id, string $status): bool { // update le statut d'une offre en DB
    $SQLChangeOfferStatus = "UPDATE jobsfinder_jobs 
    SET status = :status, new = :new WHERE id = :id";
    $pdo = connect();
    try {
        $stmtChangeOfferStatus = $pdo->prepare($SQLChangeOfferStatus);
        $stmtChangeOfferStatus->execute([
            'status' => $status,
            'new' => "",
            'id' => $id
        ]);
        return true;

    } catch(Exception $e) {
        return false;
    };
}


function changeReportingURLByUserId(int $id, string $url): bool { // update le lien d'un fichier de suivi d'un user
    // $id => user id // $url => nouvelle URL de fichier de suivi
    $SQLChangeReportingUrl = "UPDATE jobsfinder_users 
    SET reporting_link = :reporting_link WHERE user_id = :id";
    $pdo = connect();
    try {
        $stmtChangeReportingUrl = $pdo->prepare($SQLChangeReportingUrl);
        $stmtChangeReportingUrl->execute([
            'id' => $id,
            'reporting_link' => $url
        ]);
        return true;

    } catch(Exception $e) {
        return false;
    };
}


/* Fonctions de gestion de BDD (imports, cleanage) */

function cleanupJobsInDB() { // retire les offres de plus de 30j en DB
    $pdo = connect();

    // Remet new à vide sur toutes les offres
    $pdo->exec("UPDATE jobsfinder_jobs SET new = ''");

    // supprime les anciennes offres
    $sql = "DELETE FROM jobsfinder_jobs
    WHERE posted_at < NOW() - INTERVAL 31 DAY";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}


function importAdzunaOffersInDB() : int { // importe les offres d'Adzuna
    $pdo = connect();
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO jobsfinder_jobs
        (source, source_id, title, company, location, description, url, posted_at, status, new, fingerprint)
        VALUES
        ('adzuna', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // compteur d'offres importées
    $inserted = 0;

    $app_id   = ADZUNA_ID;
    $app_key  = ADZUNA_KEY;

    $search_location = "Île-de-France"; // renommé pour éviter l'écrasement
    $max_pages = 10;

    foreach (KEYWORDS as $keyword) {

        sleep(1);

        for ($page = 1; $page <= $max_pages; $page++) {

            $params = [
                "app_id"           => $app_id,
                "app_key"          => $app_key,
                "what"             => $keyword,
                "where"            => $search_location,
                "max_days_old"     => 30,
                "results_per_page" => 50
            ];

            $url = "https://api.adzuna.com/v1/api/jobs/fr/search/$page?"
                . http_build_query($params, '', '&', PHP_QUERY_RFC3986);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => ["Accept: application/json"]
            ]);

            $response  = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // écriture dans le fichier de logs en mode admin
            /* writeLog("Keyword: $keyword - Page: $page - HTTP: $http_code"); */

            if ($http_code === 204) {
                break;
            }

            if ($http_code !== 200) {
                /* writeLog("Réponse brute: " . $response);
                echo "Erreur HTTP $http_code pour : $keyword (page $page)\n";
                echo $response . "\n"; */
                break;
            }

            $data = json_decode($response, true);

            if (!isset($data["results"]) || empty($data["results"])) {
                break;
            }

            foreach ($data["results"] as $job) {

                $title        = strip_tags($job["title"] ?? "");
                $company      = strip_tags($job["company"]["display_name"] ?? "");
                $job_location = strip_tags($job["location"]["display_name"] ?? "");
                $job_url      = $job["redirect_url"] ?? "";
                $description  = strip_tags($job["description"] ?? "");
                $source_id    = $job["id"] ?? "";
                $posted_at    = $job["created"] ?? null;

                $fingerprint = md5(
                    strtolower(trim($title)) .
                    strtolower(trim($company)) .
                    strtolower(trim($job_location)) .
                    substr(strtolower(trim($description)), 0, 200)
                );

                // Filtre stage / alternance
                $blocked = false;
                foreach (BLOCKED_WORDS as $word) {
                    if (
                        stripos($title, $word) !== false ||
                        stripos($description, $word) !== false
                    ) {
                        $blocked = true;
                        break;
                    }
                }
                if ($blocked) continue;

                $stmt->execute([
                    $source_id,
                    $title,
                    $company,
                    $job_location,
                    $description,
                    $job_url,
                    $posted_at,
                    "visible",
                    "true",
                    $fingerprint
                ]);

                // MàJ du compteur
                $inserted += $stmt->rowCount();
            }
        }
    }

    return $inserted; // renvoie le compteur d'offres
}


function importFranceTravailOffersInDB() : int { // importe les offres de France Travail
    $pdo = connect();
    $stmt = $pdo->prepare("INSERT IGNORE INTO jobsfinder_jobs
    (source, source_id, title, company, location, description, url, posted_at, status, new, fingerprint)
    VALUES
    ('francetravail', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // compteur d'offres importées
    $inserted = 0;

    // Récupération du token France Travail

    $client_id = FT_CLIENT_ID;
    $client_secret = FT_CLIENT_SECRET;

    $token_url = "https://entreprise.francetravail.fr/connexion/oauth2/access_token?realm=/partenaire";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $token_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            "grant_type" => "client_credentials",
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "scope" => "o2dsoffre api_offresdemploiv2"
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json"
        ]
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        error_log("Curl error FT: " . curl_error($ch));
        return 0;
    }

    curl_close($ch);

    $token_data = json_decode($response, true);
    $access_token = $token_data["access_token"] ?? null;

    if (!$access_token) {
        error_log("Token FT manquant: " . json_encode($token_data));
        return 0;
    }


    // Recherche des offres

    $keywords = KEYWORDS;

    foreach ($keywords as $keyword) {

        sleep(1); // anti rate-limit : 1 seconde entre chaque requête

        $params = [
            "motsCles" => $keyword,
            "region" => "11",
            "range" => "0-149",
            "minCreationDate" => date("Y-m-d") . "T00:00:00Z",
            "maxCreationDate" => date("Y-m-d") . "T23:59:59Z"
        ];    

        $url = "https://api.francetravail.io/partenaire/offresdemploi/v2/offres/search?"
        . http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " . $access_token,
                "Accept: application/json"
            ]
        ]);
    
        $response  = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // écriture dans le fichier de logs en mode admin
        /* writeLog("Keyword: $keyword - HTTP: $http_code"); */

    
        if ($http_code !== 200) {
            /* writeLog("Réponse brute: " . $response);
            echo "Erreur HTTP $http_code pour : $keyword\n";
            echo $response . "\n"; */
            continue;
        }
    
        $data = json_decode($response, true);
    
        if (!isset($data["resultats"])) {
            /* echo "Pas de résultats pour : $keyword\n"; */
            continue;
        }

        foreach ($data["resultats"] as $job) {

            $title = strip_tags($job["intitule"] ?? "");
            $company = strip_tags($job["entreprise"]["nom"] ?? "");
            $location = strip_tags($job["lieuTravail"]["libelle"] ?? "");
            $description = strip_tags(mb_substr($job["description"] ?? "", 0, 800, 'UTF-8'));
            $job_url = $job["origineOffre"]["urlOrigine"] ?? "";
            $source_id = $job["id"] ?? "";
            $posted_at = $job["dateCreation"] ?? null;

            $fingerprint = md5(
                strtolower(trim($title)) .
                strtolower(trim($company)) .
                strtolower(trim($location)) .
                substr(strtolower(trim($description)), 0, 200)
            );


            // filtre stage / alternance

            $blocked = false;

            foreach (BLOCKED_WORDS as $word) {

                if (
                    stripos($title, $word) !== false ||
                    stripos($description, $word) !== false
                ) {
                    $blocked = true;
                    break;
                }
            }

            if ($blocked) {
                continue;
            }

            $stmt->execute([
                $source_id,
                $title,
                $company,
                $location,
                $description,
                $job_url,
                $posted_at,
                "visible",
                "true",
                $fingerprint
            ]);

            // MàJ du compteur
            $inserted += $stmt->rowCount();
        }
    }

    return $inserted; // renvoie le compteur d'offres
}


function globalDBUpdate() : int { // MàJ globale de la DB : clean des anciennes offres et imports

    cleanupJobsInDB();
    $newOffersAdzuna = importAdzunaOffersInDB();
    $newOffersFranceTravail = importFranceTravailOffersInDB();

    return $newOffersAdzuna + $newOffersFranceTravail;
}


/* Fonctions pour la table keywords */

function getKeywordsByUserId(string $user_id, string $mode) : array { // récupère les critères d'un user
    // 1er param => user // 2e param => "key" pour expression clé et "blacklist" pour exp bannies
    if (!in_array($mode, ['key', 'blacklist'])) {
        // check que mode vaut bien key ou blacklist, sinon renvoie une erreur
        throw new \InvalidArgumentException("Mode invalide : $mode");
    }

    $SQLGetKeywordsByUserId = "SELECT * FROM jobsfinder_keywords 
    WHERE user_id = :user_id AND type = :mode";
    $pdo = connect();
    $stmtGetKeywordsByUserId = $pdo->prepare($SQLGetKeywordsByUserId);
    $stmtGetKeywordsByUserId->execute([
        'user_id' => $user_id,
        'mode' => $mode
    ]);

    return $stmtGetKeywordsByUserId->fetchAll();
}


function checkMatchBetweenKeyAndUserId(int $id) : bool { // vérifie que l'expression qu'un
    // user veut manipuler est bien à lui
    $SQLGetKeywordByKeyId = "SELECT user_id FROM jobsfinder_keywords 
    WHERE id = :id LIMIT 1";
    $pdo = connect();
    $stmtGetKeywordByKeyId = $pdo->prepare($SQLGetKeywordByKeyId);
    $stmtGetKeywordByKeyId->execute([
        'id' => $id
    ]);

    // récupère le retour de la requête sous forme de tableau
    $row = $stmtGetKeywordByKeyId->fetch();

    if(empty($row)) { return false; }; // si expression non-trouvées, renvoie false
    // sélectionne dans le tableau uniquement le user_id
    $ownerId = $row['user_id'];

    // résultat de l'opé par défaut
    $result = false;

    // récupère user_id en session et le compare
    isset($_SESSION['user_id']) ? $userId = $_SESSION['user_id'] : $userId = NULL;
    if($userId !== NULL) {
        $userId == $ownerId ? $result = true : $result = false;
    }

    return $result;
}


function eraseKeyFromDB(int $id) : bool { // supprime l'expression en DB et dit si c'est OK
    $SQLEraseKeyFromDB = "DELETE FROM jobsfinder_keywords 
    WHERE id = :id";
    $pdo = connect();
    try {
        $stmtEraseKeyFromDB = $pdo->prepare($SQLEraseKeyFromDB);
        $stmtEraseKeyFromDB->execute([
            'id' => $id
        ]);
        return true;

    } catch(Exception $e) {
        return false;
    };
}

function createNewExpressionToUser(int $id, string $exp, string $mode) : bool { // créé une nouvelle expression pour un user
    // $id => user id, $exp => expression clé à intégrer, $mode => "key" ou "blacklist" pour le type d'exp
    // retourne le résultat de l'opération success ou error

    // check que la longueur est correcte
    $expression = trim($exp);
    $expLen = mb_strlen($expression);
    if ($expLen < 3 || $expLen > 30) {
        return false;
    }

    // si oui, procède à l'opération
    $SQLAddNewExpression = "INSERT INTO jobsfinder_keywords (user_id, expression, type) VALUES (:user_id, :expression, :type)";
    $pdo = connect();
    $stmt = $pdo->prepare($SQLAddNewExpression);
    try {
        $stmt->execute([
            'user_id' => $id,
            'expression' => $exp,
            'type' => $mode
        ]);
        return true;

    } catch(Exception $e) {
        return false;
    };
}


?>
