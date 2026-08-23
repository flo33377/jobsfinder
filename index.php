
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

<script>const BASE_URL = "<?=  BASE_URL ?>"; // récup URL courante en JS</script>
<?php if(isset($server_notif_message) && (!empty($server_notif_message))) : 
  // Récup des infos de notifs serveur pour affichage banner en JS ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    showNotification(<?= json_encode($server_notif_message) ?>, <?= $server_notif_type ? json_encode($server_notif_type) : "success" ?>);
  });
</script>
<?php endif ?>


  <header id="header">

    <div>
      <a href='<?= BASE_URL ?>' class="header_logo">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 512 512">
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
  <?php if(isset($_SESSION['user_id'])) : ?>
    <div id="burger_menu" close>

      <div class="menu_item <?php if($currentPage === "users_list") : echo 'active'; endif ?>">
        <a href="<?= BASE_URL ?>.?mode=users_list">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM6 8a6 6 0 1 1 12 0A6 6 0 0 1 6 8zm2 10a3 3 0 0 0-3 3 1 1 0 1 1-2 0 5 5 0 0 1 5-5h8a5 5 0 0 1 5 5 1 1 0 1 1-2 0 3 3 0 0 0-3-3H8z"/>
          </svg>
          <p>Utilisateurs</p>
          <p class="menu_info incoming">Admin</p>
        </a>
      </div>

      <div class="menu_item <?php if($currentPage === "offers") : echo 'active'; endif ?>">
        <a href="<?= BASE_URL ?>">
          <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
            <path d="M28,8H21V6a2,2,0,0,0-2-2H13a2,2,0,0,0-2,2V8H4a2,2,0,0,0-2,2V26a2,2,0,0,0,2,2H28a2,2,0,0,0,2-2V10A2,2,0,0,0,28,8ZM13,6h6V8H13Zm15,4v9H4V10ZM4,26V21H28v5Z"/>
            <path d="M15,18h2a1,1,0,0,0,0-2H15a1,1,0,0,0,0,2Z"/>
          </svg>
          <p>Offres</p>
        </a>
      </div>

      <div class="menu_item <?php if($currentPage === "cv") : echo 'active'; endif ?>">
        <a href="<?= BASE_URL ?>?mode=cv_storage">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 4a2 2 0 0 1 2-2h8a1 1 0 0 1 .707.293l5 5A1 1 0 0 1 20 8v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4zm13.586 4L14 4.414V8h3.586zM12 4H6v16h12V10h-5a1 1 0 0 1-1-1V4zm-4 9a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1zm0 4a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1z"/>
        </svg>
          <p>Espace CV</p>
          <p class="menu_info incoming">A venir</p>
        </a>
      </div>

      <div class="menu_item">
        <a  
        <?php if(!empty($_SESSION['reporting_link'])) : ?>
          href="<?= $_SESSION['reporting_link'] ?>" target="_blank" 
          <?php else : ?>
          href="<?= BASE_URL ?>?mode=parameters" class="pending_menu_item"
          <?php endif ?>>
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M10,18a1,1,0,0,1-.71-.29l-5-5a1,1,0,0,1,1.42-1.42L10,15.59l8.29-8.3a1,1,0,1,1,1.42,1.42l-9,9A1,1,0,0,1,10,18Z"></path>
          </svg>
          <p>Suivi candidature</p>
          <?php if(empty($_SESSION['reporting_link'])) : ?>
            <p class="menu_info error">Non-renseigné</p>
          <?php endif ?>
        </a>
      </div>

      <div class="menu_item <?php if($currentPage === "logs") : echo 'active'; endif ?>">
        <a href="<?= BASE_URL ?>?mode=log_diary">
          <svg viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
            <path d="M72.50391,150.62988,100.791,128,72.50391,105.37012A11.9996,11.9996,0,0,1,87.49609,86.62988l40,32a11.99895,11.99895,0,0,1,0,18.74024l-40,32a11.9996,11.9996,0,1,1-14.99218-18.74024ZM143.99414,172h32a12,12,0,1,0,0-24h-32a12,12,0,0,0,0,24ZM236,56.48535v143.0293A20.50824,20.50824,0,0,1,215.51465,220H40.48535A20.50824,20.50824,0,0,1,20,199.51465V56.48535A20.50824,20.50824,0,0,1,40.48535,36h175.0293A20.50824,20.50824,0,0,1,236,56.48535ZM212,60H44V196H212Z"/>
          </svg>
          <p>Journal de logs</p>
          <p class="menu_info incoming">A venir</p>
        </a>
      </div>

      <div class="menu_item <?php if($currentPage === "criterias") : echo 'active'; endif ?>">
        <a href="<?= BASE_URL ?>?mode=criterias">
          <svg viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <g>
              <path d="M 4 1 L 4 6 L 7 6 L 7 1 L 4 1 z M 5 2 L 6 2 L 6 5 L 5 5 L 5 2 z M 0 3 L 0 4 L 3 4 L 3 3 L 0 3 z M 8 3 L 8 4 L 20 4 L 20 3 L 8 3 z M 13 8 L 13 13 L 16 13 L 16 8 L 13 8 z M 14 9 L 15 9 L 15 12 L 14 12 L 14 9 z M 0 10 L 0 11 L 12 11 L 12 10 L 0 10 z M 17 10 L 17 11 L 20 11 L 20 10 L 17 10 z M 4 15 L 4 20 L 7 20 L 7 15 L 4 15 z M 5 16 L 6 16 L 6 19 L 5 19 L 5 16 z M 0 17 L 0 18 L 3 18 L 3 17 L 0 17 z M 8 17 L 8 18 L 20 18 L 20 17 L 8 17 z "/>
            </g>
          </svg>
          <p>Critères de recherche</p>
        </a>
      </div>

      <div class="menu_item <?php if($currentPage === "parameters") : echo 'active'; endif ?>">
        <a href="<?= BASE_URL ?>?mode=parameters">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" d="m12 2.25c-1.5208 0-2.79833 1.14348-2.96627 2.65494l-.0917.82526c-.01543.13888-.11542.30627-.31935.42432-.20104.11638-.39276.11958-.52314.06246l-.7609-.33338c-1.39293-.61029-3.022-.07562-3.78238 1.2414s-.40889 2.9952.81611 3.8963l.66963.4927c.11406.0839.20801.2529.20801.486s-.09395.4021-.20801.486l-.66963.4927c-1.225.9011-1.57649 2.5793-.81611 3.8963s2.38945 1.8517 3.78238 1.2414l.7609-.3334c.13038-.0571.3221-.0539.52314.0625.20393.118.30392.2854.31935.4243l.0917.8253c.16794 1.5114 1.44547 2.6549 2.96627 2.6549s2.7983-1.1435 2.9663-2.6549l.0917-.8253c.0154-.1389.1154-.3063.3193-.4243.2011-.1164.3928-.1196.5232-.0625l.7609.3334c1.3929.6103 3.022.0756 3.7824-1.2414.7603-1.317.4088-2.9952-.8162-3.8963l-.6696-.4927c-.114-.0839-.208-.2529-.208-.486s.094-.4021.208-.486l.6696-.4927c1.225-.9011 1.5765-2.57928.8162-3.8963-.7604-1.31702-2.3895-1.85169-3.7824-1.2414l-.7609.33337c-.1304.05713-.3221.05393-.5232-.06245-.2039-.11805-.3039-.28544-.3193-.42433l-.0917-.82525c-.168-1.51146-1.4455-2.65494-2.9663-2.65494zm-1.4754 2.82059c.0835-.75181.719-1.32059 1.4754-1.32059s1.3919.56878 1.4755 1.32059l.0917.82525c.0781.70365.5289 1.25021 1.0586 1.55686.5335.30881 1.2296.42166 1.8766.13819l.7609-.33337c.6929-.30357 1.5032-.03762 1.8814.61748s.2034 1.48982-.4059 1.93807l-.6696.49263c-.5691.4186-.8192 1.0794-.8192 1.6943s.2501 1.2757.8192 1.6943l.6696.4926c.6093.4483.7841 1.283.4059 1.9381s-1.1885.921-1.8814.6175l-.7609-.3334c-.647-.2835-1.3431-.1706-1.8766.1382-.5297.3067-.9805.8532-1.0586 1.5569l-.0917.8252c-.0836.7518-.7191 1.3206-1.4755 1.3206s-1.3919-.5688-1.4754-1.3206l-.0917-.8252c-.0782-.7037-.529-1.2502-1.05873-1.5569-.53347-.3088-1.2296-.4217-1.87659-.1382l-.76089.3334c-.69286.3035-1.50317.0376-1.88139-.6175-.37823-.6551-.20339-1.4898.40594-1.9381l.66963-.4926c.56902-.4186.81914-1.0794.81914-1.6943s-.25012-1.2757-.81914-1.6943l-.66963-.49263c-.60933-.44825-.78417-1.28297-.40594-1.93807.37822-.6551 1.18853-.92105 1.88139-.61748l.76089.33337c.64699.28347 1.34312.17062 1.87659-.13819.52973-.30665.98053-.85321 1.05873-1.55685zm.2254 6.92941c0-.6904.5597-1.25 1.25-1.25.6904 0 1.25.5596 1.25 1.25s-.5596 1.25-1.25 1.25c-.6903 0-1.25-.5596-1.25-1.25zm1.25-2.75c-1.5188 0-2.74999 1.2312-2.74999 2.75s1.23119 2.75 2.74999 2.75 2.75-1.2312 2.75-2.75-1.2312-2.75-2.75-2.75z" fill-rule="evenodd"/>
          </svg>
          <p>Paramètres</p>
        </a>
      </div>

      <div class="menu_item disconnect_btn">
        <a href="<?= BASE_URL ?>?action=disconnect">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.593 10.943c.584.585.584 1.53 0 2.116L18.71 15.95c-.39.39-1.03.39-1.42 0a.996.996 0 0 1 0-1.41 9.552 9.552 0 0 1 1.689-1.345l.387-.242-.207-.206a10 10 0 0 1-2.24.254H8.998a1 1 0 1 1 0-2h7.921a10 10 0 0 1 2.24.254l.207-.206-.386-.241a9.562 9.562 0 0 1-1.69-1.348.996.996 0 0 1 0-1.41c.39-.39 1.03-.39 1.42 0l2.883 2.893zM14 16a1 1 0 0 0-1 1v1.5a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1.505a1 1 0 1 0 2 0V5.5A2.5 2.5 0 0 0 12.5 3h-7A2.5 2.5 0 0 0 3 5.5v13A2.5 2.5 0 0 0 5.5 21h7a2.5 2.5 0 0 0 2.5-2.5V17a1 1 0 0 0-1-1z"/>
          </svg>
          <p>Déconnexion</p>
        </a>
      </div>

    </div>

    <!-- Overlay du menu -->
    <div class="menu_overlay" id="menu_overlay"></div>
  <?php endif ?>

  <div class="page_wrapper">
    <main id='content'>

    <?php include($content); ?>

    <p id="notification_banner"></p>
    </main>

    

    <footer>
      <p>© <?= date('Y') ?> Florian Neto - Jobsfinder</p>
      <p>Tous droits réservés.</p>
    </footer>
  </div>


</body>

</html>