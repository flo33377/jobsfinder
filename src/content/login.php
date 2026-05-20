
<?php if(!isset($error) || $error == NULL) : // si pas d'incident enregistré
    ?>

    <div class="login_page">
        <div class="framed_content login_main_content">
            <h1>Bienvenue sur Jobs Finder</h1>
            <h2>Votre job board sur mesure</h2>

            <a href="<?= BASE_URL ?>?mode=login" class="main_cta">Se connecter avec Google</a>
        </div>

        <p>Jobs Finder vous assiste dans votre recherche d'emploi en :
            <ul>
                <li><span class="bold">Regroupant les offres</span> de différents sites correspondants à votre recherche ;</li>
                <li>Vous permettant de les trier pour <span class="bold">ne plus revoir</span> celles qui ne vous intéressent pas ;</li>
                <li><span class="bold">Centralisant vos CVs</span> et vos documents de suivi ;</li>
                <li><span class="bold">Vous avertissant</span> lorsque de nouvelles offres sont disponibles.</li>
            </ul>
        </p>

        <p>Jobs Finder est actuellement en phase de test : le service est gratuit afin de l'améliorer.<br>
        Pour demander un accès, c'est par ici 👇</p>

        <a href='https://www.linkedin.com/in/florian-neto-751008b9/' class="second_cta">Prendre contact</a>

    </div>

<?php elseif(isset($error) && ($error === "no_account")) : // Error => compte n'existe pas en DB ?>
    <p class="error_message">Aucun compte associé à cet email.<br>
    Pour demander la création d'un compte de test, contactez le propriétaire du site.
    <a href='https://www.linkedin.com/in/florian-neto-751008b9/' class="second_cta">Prendre contact</a>
    <a href="<?= BASE_URL ?>?mode=login" class="main_cta">Se connecter avec un autre compte</a>
    </p>

<?php elseif(isset($error) && ($error === "forbidden_access")) : //Error => tentative d'accéder à un contenu réservé aux users connectés  ?>
    <p class="error_message">Vous n'avez pas accès à ce contenu.<br>
    Connectez-vous pour utiliser les fonctionnalités Jobs Finder.</p>
    <a href="<?= BASE_URL ?>?mode=login" class="main_cta">Se connecter avec Google</a>

<?php else : // Error => Cas d'erreur qui n'a pas de scénario propre ?>
    <p class="error_message">Une erreur s'est produite.<br>
    Merci de ré-essayer plus tard.</p>


<?php endif ?>
