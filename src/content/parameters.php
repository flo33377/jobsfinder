
<div class="parameters_content">

<h2 class="page_title">Paramètres</h2>

    <div class="container">
        <div class="container_title">
            <h3>
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M10,18a1,1,0,0,1-.71-.29l-5-5a1,1,0,0,1,1.42-1.42L10,15.59l8.29-8.3a1,1,0,1,1,1.42,1.42l-9,9A1,1,0,0,1,10,18Z"></path>
                </svg>
                Lien de suivi
            </h3>
        </div>

        <form method="POST" action>
            <input type="hidden" name="post_save_reporting_link" required>
            <p class="comments">Renseignez le lien vers votre outil de suivi (Google Sheets, Notion, etc.). Il sera accessible directement depuis le menu.</p>
            <label for="reporting_link">Lien actuel</label>
            <input type="text" id="reporting_link" name="reporting_link"
            <?php if(!empty($_SESSION['reporting_link'])) : ?>
                value="<?= $_SESSION['reporting_link'] ?>" disabled
                <?php else : ?>
                    placeholder="Aucun lien sauvegardé"
                <?php endif ?>>
            <input type="submit" id="change_reporting_url_btn"
            <?php if(!empty($_SESSION['reporting_link'])) : ?>
                value="Modifier"
            <?php else : ?>
                value="Sauvegarder"
                <?php endif ?>>
        </form>
    </div>


    <div class="container">
        <div class="container_title">
            <h3>
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <line x1="21" y1="21" x2="3" y2="21"></line>
                    <path d="M19.88,7,11,15.83,7,17l1.17-4,8.88-8.88A2.09,2.09,0,0,1,20,4,2.09,2.09,0,0,1,19.88,7Z"></path>
                </svg>
                Espace CV
            </h3>
        </div>

        <form method="POST" action>
            <input type="hidden" name="post_save_cv_link" required>
            <p class="comments">Si vous utilisez un éditeur de CV en ligne (Figma, Canva, etc.), renseignez-le ici pour y avoir accès depuis votre Espace CV.</p>
            <label for="cv_link">Lien actuel</label>
            <input type="text" id="cv_link" name="cv_link"
            <?php if(!empty($_SESSION['cv_link'])) : ?>
                value="<?= $_SESSION['cv_link'] ?>" disabled
                <?php else : ?>
                    placeholder="Aucun lien sauvegardé"
                <?php endif ?>>
            <input type="submit" id="change_cv_link_btn"
            <?php if(!empty($_SESSION['cv_link'])) : ?>
                value="Modifier"
            <?php else : ?>
                value="Sauvegarder"
                <?php endif ?>>
        </form>
    </div>


    <div class="container">
        <div class="container_title">
        <h3>
        <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path d="M7.27,12H8.78V10.55H7.27ZM9.92,4.58h0A3.36,3.36,0,0,0,7.9,4a2.82,2.82,0,0,0-1.55.41A2.49,2.49,0,0,0,5.28,6.58H6.83a1.55,1.55,0,0,1,.26-.86A.93.93,0,0,1,8,5.31a1,1,0,0,1,.87.33,1.26,1.26,0,0,1,.24.75A1.17,1.17,0,0,1,8.87,7a1.34,1.34,0,0,1-.32.31l-.39.31a2.19,2.19,0,0,0-.71.8,4.39,4.39,0,0,0-.18,1.25H8.73a2.22,2.22,0,0,1,.07-.63,1.18,1.18,0,0,1,.41-.57l.38-.29a3.66,3.66,0,0,0,.78-.74,1.93,1.93,0,0,0,.35-1.18A2,2,0,0,0,9.92,4.58Zm3-1.53A7,7,0,1,0,13,13,7,7,0,0,0,13,3.05ZM12,12A5.6,5.6,0,0,1,4,12,5.61,5.61,0,0,1,4,4,5.6,5.6,0,0,1,12,4,5.61,5.61,0,0,1,12,12Z"/>
            </g>
        </svg>
            Aide & questions</h3>
        </div>

        <p class="comments">Un problème ou une suggestion ? Contactez-moi directement.</p>
        <div class="contact_btn">
            <a href='https://www.linkedin.com/in/florian-neto-751008b9/'>
            <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1 3.5l.5-.5h13l.5.5v9l-.5.5h-13l-.5-.5v-9zm1 1.035V12h12V4.536L8.31 8.9H7.7L2 4.535zM13.03 4H2.97L8 7.869 13.03 4z"/>
            </svg>
                <p>Me contacter</p>
            </a>
        </div>
    </div>


    <div class="container disconnect_container">
        <div class="container_title">
        <h3>
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.593 10.943c.584.585.584 1.53 0 2.116L18.71 15.95c-.39.39-1.03.39-1.42 0a.996.996 0 0 1 0-1.41 9.552 9.552 0 0 1 1.689-1.345l.387-.242-.207-.206a10 10 0 0 1-2.24.254H8.998a1 1 0 1 1 0-2h7.921a10 10 0 0 1 2.24.254l.207-.206-.386-.241a9.562 9.562 0 0 1-1.69-1.348.996.996 0 0 1 0-1.41c.39-.39 1.03-.39 1.42 0l2.883 2.893zM14 16a1 1 0 0 0-1 1v1.5a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1.505a1 1 0 1 0 2 0V5.5A2.5 2.5 0 0 0 12.5 3h-7A2.5 2.5 0 0 0 3 5.5v13A2.5 2.5 0 0 0 5.5 21h7a2.5 2.5 0 0 0 2.5-2.5V17a1 1 0 0 0-1-1z"/>
            </svg>
            Vous deconnecter</h3>
        </div>
        <p class="comments">Vous serez redirigé vers la page d'accueil.</p>

        <div class="disconnect_btn">
            <a href="<?= BASE_URL ?>?action=disconnect">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.593 10.943c.584.585.584 1.53 0 2.116L18.71 15.95c-.39.39-1.03.39-1.42 0a.996.996 0 0 1 0-1.41 9.552 9.552 0 0 1 1.689-1.345l.387-.242-.207-.206a10 10 0 0 1-2.24.254H8.998a1 1 0 1 1 0-2h7.921a10 10 0 0 1 2.24.254l.207-.206-.386-.241a9.562 9.562 0 0 1-1.69-1.348.996.996 0 0 1 0-1.41c.39-.39 1.03-.39 1.42 0l2.883 2.893zM14 16a1 1 0 0 0-1 1v1.5a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1.505a1 1 0 1 0 2 0V5.5A2.5 2.5 0 0 0 12.5 3h-7A2.5 2.5 0 0 0 3 5.5v13A2.5 2.5 0 0 0 5.5 21h7a2.5 2.5 0 0 0 2.5-2.5V17a1 1 0 0 0-1-1z"/>
                </svg>
                <p>Deconnexion</p>
            </a>
        </div>
    </div>


</div>

