<h1>Paramètres</h1>

<h2 class="parameters_title">Vos expressions clés</h2>
<p class="comments">
    Il s'agit des expressions utilisées pour rechercher des offres correspondantes à vos critères.
    N'hésitez pas à multiplier les mots de vocabulaire pour tirer le meilleur parti de Jobs Finder.<br>
    Ex : Chef de projet web => Chef de projet digital, Digital Project Manager, Expert web, etc.
</p>

<div id="keywords_container">
    <?php if(!isset($userKeywords)) : // Cas => la requête en DB a échouée ?>
        <p class="error_message">Une erreur s'est produite, merci de ré-essayer plus tard.</p>
    
    <?php elseif(isset($userKeywords) && empty($userKeywords)) : // Cas => aucune expression clé en base ?>
        <p class="error_message">Aucune expression enregistrée.</p>
        <button id="keywords_create_first">Créer votre première expression clé</button>

    <?php else : // Cas => Au moins 1 keyword ou blacklist de trouvé ?>

        <?php foreach($userKeywords as $keyword) : ?>
            <?php if($keyword['type'] == "key") : ?>
                <div class="keyword" data-id="<?= $keyword['id'] ?>">
                    <p><?= $keyword['expression'] ?></p>
                    <button onclick="eraseExpressionInDB(<?= $keyword['id'] ?>)">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 20L4 4.00003M20 4L4.00002 20" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            <?php endif ?>
        <?php endforeach ?>

        <button id='keyword_create_new'>Ajouter 
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z"/>
            </svg>
        </button>


    <?php endif ?>
</div>


<h2 class="parameters_title">Vos expressions bannies</h2>
<p class="comments">
    Ce sont les mots et expressions qui excluent les offres.<br>
    Si vous recherchez un CDI/CDD par exemple, vous pouvez exclure les offres qui contiennent 
    "Stage", "Alternant" ou encore "Freelance".<br>
    Cela peut aussi servir à exclure des types de mission ou des secteurs en particulier.
</p>

<div id="blacklist_container">
    <?php if(!isset($userKeywords)) : // Cas => la requête en DB a échouée ?>
        <p class="error_message">Une erreur s'est produite, merci de ré-essayer plus tard.</p>
    
    <?php elseif(isset($userKeywords) && empty($userKeywords)) : // Cas => aucune expression clé en base ?>
        <p class="error_message">Aucune expression enregistrée.</p>
        <button id="blacklist_create_first">Créer votre première expression bannie</button>

    <?php else : // Cas => Au moins 1 keyword ou blacklist de trouvé ?>

        <?php foreach($userKeywords as $keyword) : ?>
            <?php if($keyword['type'] == "blacklist") : ?>
                <div class="blacklist" data-id="<?= $keyword['id'] ?>">
                    <p><?= $keyword['expression'] ?></p>
                    <button onclick="eraseExpressionInDB(<?= $keyword['id'] ?>)">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 20L4 4.00003M20 4L4.00002 20" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            <?php endif ?>
        <?php endforeach ?>

        <button id='blacklist_create_new'>Ajouter 
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z"/>
            </svg>
        </button>


    <?php endif ?>
</div>


<a href="<?= BASE_URL ?>?action=disconnect" class="second_cta">Deconnexion</a>




