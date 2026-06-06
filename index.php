
<?php // dépendances
include_once(__DIR__ . "/src/main.php");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="./public/css/design-system.css">

  <meta name="theme-color" content="#0000">

  <script src="./public/js/js-ux.js" defer></script>
  <script src="./public/js/js-functions.js" defer></script>

  <link rel="apple-touch-icon" sizes="180x180" href="https://fneto-prod.fr/jobsfinder/public/img/find-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://fneto-prod.fr/jobsfinder/public/img/find-icon.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://fneto-prod.fr/jobsfinder/public/img/find-icon.png">

  <!-- Font Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <title>JobsFinder</title>
</head>


<body>

  <header id="header">

    <div>
      <a href='<?= BASE_URL ?>' class="header_logo">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
          <g>
            <path d="M497.938,430.063l-126.914-126.91C389.287,272.988,400,237.762,400,200C400,89.719,310.281,0,200,0
            C89.719,0,0,89.719,0,200c0,110.281,89.719,200,200,200c37.762,0,72.984-10.711,103.148-28.973l126.914,126.91
            C439.438,507.313,451.719,512,464,512c12.281,0,24.563-4.688,33.938-14.063C516.688,479.195,516.688,448.805,497.938,430.063z
            M64,200c0-74.992,61.016-136,136-136s136,61.008,136,136s-61.016,136-136,136S64,274.992,64,200z"/>
          </g>
        </svg>
        <p>Jobs Finder</p>
      </a>
    </div>

  <?php if(isset($_SESSION['user_id'])) : ?>
      <svg id='menu_button' viewBox="0 0 24 24" aria-label="Menu" role="button">
        <line class="bar top" x1="3" y1="6"  x2="21" y2="6" />
        <line class="bar middle" x1="3" y1="12" x2="21" y2="12" />
        <line class="bar bottom" x1="3" y1="18" x2="21" y2="18" />
      </svg>
    <?php else : ?>
      <div><a href="<?= BASE_URL ?>?mode=login" class="standard_cta connect">Se connecter avec Google</a></div>
    <?php endif ?>

  </header>

  <!-- contenu du menu -->
  <div id="burger_menu" close>
    <div class="menu_item"><a href="<?= BASE_URL ?>?mode=cv_storage">Bibliothèque de CV</a></div>
    <div class="menu_item"><a target="_blank" href="https://docs.google.com/spreadsheets/d/13OQw9J7J2AboUtiRq9ufvLfK46Y93o84C1b3_TL34vc/edit?usp=sharing">Suivi candidature</a></div>
    <div class="menu_item"><a href="<?= BASE_URL ?>?mode=log_diary">Journal de logs</a><!-- label supp => <p class="menu_info">Admin</p>--></div>
    <div class="menu_item"><a href="<?= BASE_URL ?>?mode=parameters">Paramètres</a></div>
  </div>


  <main id='content'>

  <?php include($content); ?>

  </main>

  <p id="notification_banner"></p>

  <footer>
    <p>© <?= date('Y') ?> Florian Neto - Jobsfinder</p>
    <p>Tous droits réservés.</p>
  </footer>


</body>

</html>