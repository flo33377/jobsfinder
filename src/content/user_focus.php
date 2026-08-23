
<div class="nav_back_container">
    <a class="nav_back_btn" href="<?= BASE_URL ?>?mode=users_list">
        <svg viewBox="0 0 36 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path d="M27.66,15.61,18,6,8.34,15.61A1,1,0,1,0,9.75,17L17,9.81V28.94a1,1,0,1,0,2,0V9.81L26.25,17a1,1,0,0,0,1.41-1.42Z">
            </path>
        </svg>
    </a>
</div>


<h2 class="page_title">Utilisateur n°<?= $user['user_id'] ?></h2>

<div class="container user_focus">
    <div class="container_title">
        <div class="user_focus_icon">
            <?php if($user['name']) : ?>
                <p><?= mb_substr($user['name'], 0, 1) ?></p>
            <?php else : ?>
                <p><?= mb_substr($user['user_email'], 0, 1) ?></p>
            <?php endif ?>
        </div>
        <div class="user_focus_name">
            <p class="user-name"><?= $user["name"] ?></p>
            <p class="user-email"><?= $user["user_email"] ?></p>
        </div>
    </div>

    <div class="user_focus_datas">
        <p>Rôle</p>
        <span class="role-badge <?= $user['role'] === 'admin' ? 'role-admin' : 'role-user' ?>">
            <?php if($user['role'] == "admin") echo "Admin"; else echo "Utilisateur"; ?>
        </span>
    </div>

    <div class="user_focus_datas">
        <p>Dernière connexion</p>
        <?= $user['last_login_at'] ? date('d/m/Y à H:i', strtotime($user['last_login_at'])) : 'Jamais connecté' ?>
    </div>

    <div class="user_focus_datas">
        <p>Statut</p>
        <span class="status-badge status-<?= $user['import_status'] ?? 'unknown' ?>">
            <?= $user['import_status'] ?? '—' ?>
        </span>
    </div>

</div>

<div class="container user_focus">
    <div class="container_title user_focus_title"><p>Offres</p></div>

    <?php // set le ratio d'offres masquées et postulées
    if($offersCount <= 0) { 
        $hiddenOffersRatio = 0;
        $appliedOffersRatio = 0;
    } else {
        $hiddenOffersRatio = round((($hiddenJobsCount/$offersCount)*100), 0);
        $appliedOffersRatio = round((($appliedJobsCount/$offersCount)*100), 0);
    }?>


    <div class="user_focus_datas">
        <p>Offre<?php if($offersCount > 1) { echo "s";}?> en base</p>
        <p><?= $offersCount ?> offre<?php if($offersCount > 1) { echo "s";}?></p>
    </div>

    <div class="user_focus_datas">
        <p class="comments">dont <?= $hiddenOffersRatio ?>% masquée<?php if($offersCount > 1) { echo "s";}?> 
        et <?= $appliedOffersRatio ?>% postulée<?php if($offersCount > 1) { echo "s";}?></p>
    </div>

    <div class="user_focus_datas">
        <p>Expression<?php if($keywordsCounter > 1) { echo "s";}?> clés</p>
        <p><?= $keywordsCounter ?> expression<?php if($keywordsCounter > 1) { echo "s";}?> clé</p>
    </div>

    <div class="user_focus_datas">
        <p><?php if($blacklistCounter > 1) { echo "Mots bannis";} else { echo "Mot banni";}?></p>
        <p><?= $blacklistCounter ?> mot<?php if($blacklistCounter > 1) { echo "s";}?> blacklist</p>
    </div>
</div>


<div class="container cv_size_block user_focus
<?php if($cvStorage['fileCount'] >= 10 || $userMo >= 6) : echo "max_size_reached"; endif ?>">
    <div class="container_title user_focus_title">
        <p>Espace CV</p>
    </div>
    <div class="container_title space_between">
        <p>Espace utilisé</p>
        <p class="bold"><?= $usedMo ?> Mo / 6 Mo</p>
    </div>

    <div>
        <progress id="available_size_bar" max="6" value="<?= $usedMo ?>" aria-label="Espace de stockage déjà utilisé : "></progress>
    </div>

    <div>
        <p><?= $cvStorage['fileCount'] ?> / 10 fichiers</p>
        <?php if($cvStorage['fileCount'] >= 10) : ?>
            <p class="warning">Nombre de fichier maximum atteint</p>
        <?php endif ?>
    </div>
</div>

<div class="contact_btn">
    <?php if($user['import_status'] == "paused") : ?>
        <a class="main_cta" 
        href="<?= BASE_URL ?>?action=admin_force_status_user&pause=false&user=<?= $user['user_id'] ?>"
        >Réactiver ce compte</a>
    <?php else : ?>
        <a class="main_cta" 
        href="<?= BASE_URL ?>?action=admin_force_status_user&pause=true&user=<?= $user['user_id'] ?>"
        >Mettre ce compte en pause</a>
    <?php endif ?>
</div>




