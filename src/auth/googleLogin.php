<?php
require_once './vendor/autoload.php';

$provider = new League\OAuth2\Client\Provider\Google([
    'clientId'     => GOOGLE_CLIENT_ID,
    'clientSecret' => GOOGLE_CLIENT_SECRET,
    'redirectUri'  => GOOGLE_REDIRECT_URI,
]);

// Génère l'URL Google et stocke le "state" en session (protection CSRF)
$authUrl = $provider->getAuthorizationUrl();
$_SESSION['oauth2state'] = $provider->getState(); // récup le token d'identification de la requête

header('Location: ' . $authUrl);
exit;