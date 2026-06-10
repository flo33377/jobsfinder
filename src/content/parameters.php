
<div class="parameters_content">

    <h2 class="parameters_title">Vous deconnecter</h2>

    <div class="disconnect_btn">
        <a href="<?= BASE_URL ?>?action=disconnect">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.593 10.943c.584.585.584 1.53 0 2.116L18.71 15.95c-.39.39-1.03.39-1.42 0a.996.996 0 0 1 0-1.41 9.552 9.552 0 0 1 1.689-1.345l.387-.242-.207-.206a10 10 0 0 1-2.24.254H8.998a1 1 0 1 1 0-2h7.921a10 10 0 0 1 2.24.254l.207-.206-.386-.241a9.562 9.562 0 0 1-1.69-1.348.996.996 0 0 1 0-1.41c.39-.39 1.03-.39 1.42 0l2.883 2.893zM14 16a1 1 0 0 0-1 1v1.5a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1.505a1 1 0 1 0 2 0V5.5A2.5 2.5 0 0 0 12.5 3h-7A2.5 2.5 0 0 0 3 5.5v13A2.5 2.5 0 0 0 5.5 21h7a2.5 2.5 0 0 0 2.5-2.5V17a1 1 0 0 0-1-1z"/>
            </svg>
            <p>Deconnexion</p>
        </a>
    </div>

    <h2 class="parameters_title">Lien de suivi</h2>

    <form method="POST" action>
        <input type="hidden" name="post_save_reporting_link" required>
        <label class="comments" for="reporting_link">Saisissez ici le lien de votre document de suivi en ligne (google drive, template Notions, etc.).<br>
        Il sera alors accessible directement depuis le menu.</label>
        <input type="text" id="reporting_link" name="reporting_link"
        <?php if(!empty($_SESSION['reporting_link'])) : ?>
            value="<?= $_SESSION['reporting_link'] ?>" disabled
            <?php endif ?>>
        <input type="submit" id="change_reporting_url_btn"
        <?php if(!empty($_SESSION['reporting_link'])) : ?>
            value="Modifier"
        <?php else : ?>
            value="Ajouter"
            <?php endif ?>>
    </form>


</div>

