
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

  <title>JobsFinder</title>
</head>


<body>

  <header id="header">

  <?php if(isset($_SESSION['user_id'])) : ?>
      <svg id='menu_button' viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M3 6C3 5.44772 3.44772 5 4 5H20C20.5523 5 21 5.44772 21 6C21 6.55228 20.5523 7 20 7H4C3.44772 7 3 6.55228 3 6ZM3 12C3 11.4477 3.44772 11 4 11H20C20.5523 11 21 11.4477 21 12C21 12.5523 20.5523 13 20 13H4C3.44772 13 3 12.5523 3 12ZM3 18C3 17.4477 3.44772 17 4 17H20C20.5523 17 21 17.4477 21 18C21 18.5523 20.5523 19 20 19H4C3.44772 19 3 18.5523 3 18Z"/>
      </svg>
    <?php else : ?>
      <div></div>
    <?php endif ?>

    <div>
      <a href='<?= BASE_URL ?>' class="header_logo">
        <p id="title_header">Jobs Finder</p>
        <img src="https://fneto-prod.fr/jobsfinder/public/img/find-icon.png" alt="Icon website">
      </a>
    </div>

    <div></div>

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
    <p>© Copyright <?= date('Y') ?><br>Florian Neto. Tous droits réservés.</p>
  </footer>


</body>

</html>