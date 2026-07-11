<?php

require_once './vendor/autoload.php';

$provider = new League\OAuth2\Client\Provider\Google([
    'clientId'     => GOOGLE_CLIENT_ID,
    'clientSecret' => GOOGLE_CLIENT_SECRET,
    'redirectUri'  => GOOGLE_REDIRECT_URI,
]);

// Vérification du state anti-CSRF
if (empty($_GET['state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
    unset($_SESSION['oauth2state']);
    exit('State invalide, tentative de CSRF possible.');
}

// Échange du code contre un token
$token = $provider->getAccessToken('authorization_code', [
    'code' => $_GET['code']
]);

// Récupère les infos du user Google
$googleUser = $provider->getResourceOwner($token);
$email = $googleUser->getEmail();

// Vérifie si cet email existe dans ta table users
$user = getUserByEmail($email); // vérifie si le user a bien un compte en base

if ($user) {
    // Connexion réussie
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $email;
    $_SESSION['reporting_link'] = $user['reporting_link'] ?? NULL;
    $_SESSION['cv_link'] = $user['cv_link'] ?? NULL;

    updateLastLoginAtForUser($user['user_id']);

    header('Location: ' . BASE_URL);
} else {
    // Email inconnu en DB
    header('Location: ' . BASE_URL . '?mode=login&error=no_account');
}
exit;
?>