

<h2 class="page_title">Critères de recherche</h2>
<p class="subtitles">Ces critères pilotent l'import automatique des offres qui vous conviennent.</p>

<div id="keywords_container" class="container">
    <div class="container_title space_between">
        <h3>
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 512 512">
            <g>
                <path d="M497.938,430.063l-126.914-126.91C389.287,272.988,400,237.762,400,200C400,89.719,310.281,0,200,0
                C89.719,0,0,89.719,0,200c0,110.281,89.719,200,200,200c37.762,0,72.984-10.711,103.148-28.973l126.914,126.91
                C439.438,507.313,451.719,512,464,512c12.281,0,24.563-4.688,33.938-14.063C516.688,479.195,516.688,448.805,497.938,430.063z
                M64,200c0-74.992,61.016-136,136-136s136,61.008,136,136s-61.016,136-136,136S64,274.992,64,200z"/>
            </g>
            </svg>
            Expressions clés
        </h3>
        <div class="keywords_counter<?php if(count($userKeywords) >= 20) : echo " maximum_count"; endif ?>">
            <?php if(!isset($userKeywords)) : // Cas => la requête en DB a échouée ?>
                <p>0/20</p>
            <?php else : ?>
                <p>
                <?php echo count($userKeywords) ?>/20</p>
                <?php endif ?>
        </div>
    </div>

    <p class="comments">Mots-clés utilisés pour récupérer les offres. Multipliez les variantes pour élargir votre scope.</p>

    <?php if(!isset($userKeywords)) : // Cas => la requête en DB a échouée ?>
        <p class="error_message">Une erreur s'est produite, merci de ré-essayer plus tard.</p>
    
    <?php elseif(isset($userKeywords) && empty($userKeywords)) : // Cas => aucune expression clé en base ?>
        <p class="error_message">Aucune expression enregistrée.</p>
        <div role="button" id="keyword_create_first" data-popup-id="add_exp_modal" onclick="injectExpTypeToAdd('key')">
            <p>Créer votre première expression</p>
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z"/>
            </svg>
        </div>

    <?php else : // Cas => Au moins 1 keyword ou blacklist de trouvé ?>

        <?php foreach($userKeywords as $keyword) : ?>
            <?php if($keyword['type'] == "key") : ?>
                <div class="keyword" data-id="<?= $keyword['id'] ?>">
                    <p><?= $keyword['expression'] ?></p>
                    <button data-popup-id="erase_exp_modal" onclick="injectExpIdToErase(<?= $keyword['id'] ?>)">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 20L4 4.00003M20 4L4.00002 20" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            <?php endif ?>
        <?php endforeach ?>

        <?php if(count($userKeywords) < 20) : ?>
            <div role="button" id='keyword_create_new' data-popup-id="add_exp_modal" onclick="injectExpTypeToAdd('key')">
                <p>Ajouter</p> 
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z"/>
                </svg>
            </div>
        <?php endif ?>

        <?php if(count($userKeywords) >= 20) : ?>
            <div class="info_max_reach">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 14a1 1 0 0 1-1-1v-3a1 1 0 1 1 2 0v3a1 1 0 0 1-1 1zm-1.5 2.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
                    <path d="M10.23 3.216c.75-1.425 2.79-1.425 3.54 0l8.343 15.852C22.814 20.4 21.85 22 20.343 22H3.657c-1.505 0-2.47-1.6-1.77-2.931L10.23 3.216zM20.344 20L12 4.147 3.656 20h16.688z"/>
                </svg>
                <p>Maximum atteint, supprimez des expressions pour en ajouter de nouvelles.</p>
            </div>
        <?php endif ?>


    <?php endif ?>
</div>


<div id="blacklist_container" class="container">
    <div class="container_title space_between">
        <h3>
            <svg viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                <path d="M213.333 960c0-167.36 56-321.707 149.44-446.4L1406.4 1557.227c-124.693 93.44-279.04 149.44-446.4 149.44-411.627 0-746.667-335.04-746.667-746.667m1493.334 0c0 167.36-56 321.707-149.44 446.4L513.6 362.773c124.693-93.44 279.04-149.44 446.4-149.44 411.627 0 746.667 335.04 746.667 746.667M960 0C429.76 0 0 429.76 0 960s429.76 960 960 960 960-429.76 960-960S1490.24 0 960 0" fill-rule="evenodd"/>
            </svg>
            Expressions bannies
        </h3>
        <div class="keywords_counter<?php if(count($userBlacklists) >= 20) : echo " maximum_count"; endif ?>">
            <?php if(!isset($userBlacklists)) : // Cas => la requête en DB a échouée ?>
                <p>0/20</p>
            <?php else : ?>
                <p>
                    <?php echo count($userBlacklists) ?>/20</p>
                <?php endif ?>
        </div>
    </div>

    <p class="comments">Exclut les offres dont le titre contient ces mots. Utile pour filtrer les stages, alternances, secteurs non souhaités ou compétences particulières que vous n'avez pas.</p>

    <?php if(!isset($userBlacklists)) : // Cas => la requête en DB a échouée ?>
        <p class="error_message">Une erreur s'est produite, merci de ré-essayer plus tard.</p>
    
    <?php elseif(isset($userBlacklists) && empty($userBlacklists)) : // Cas => aucune expression clé en base ?>
        <p class="error_message">Aucune expression enregistrée.</p>
        <div id="blacklist_create_first" data-popup-id="add_exp_modal" onclick="injectExpTypeToAdd('blacklist')">
            <p>Créer votre première expression bannie</p>
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z"/>
            </svg>
        </div>

    <?php else : // Cas => Au moins 1 keyword ou blacklist de trouvé ?>

        <?php foreach($userBlacklists as $keyword) : ?>
            <?php if($keyword['type'] == "blacklist") : ?>
                <div class="blacklist" data-id="<?= $keyword['id'] ?>">
                    <p><?= $keyword['expression'] ?></p>
                    <button data-popup-id="erase_exp_modal" onclick="injectExpIdToErase(<?= $keyword['id'] ?>)">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 20L4 4.00003M20 4L4.00002 20" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            <?php endif ?>
        <?php endforeach ?>

        <?php if(count($userBlacklists) < 20) : ?>
            <div role="button" id='blacklist_create_new' data-popup-id="add_exp_modal" onclick="injectExpTypeToAdd('blacklist')">
                <p>Ajouter</p> 
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z"/>
                </svg>
            </div>
        <?php endif ?>

        <?php if(count($userBlacklists) >= 20) : ?>
            <div class="info_max_reach">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 14a1 1 0 0 1-1-1v-3a1 1 0 1 1 2 0v3a1 1 0 0 1-1 1zm-1.5 2.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
                    <path d="M10.23 3.216c.75-1.425 2.79-1.425 3.54 0l8.343 15.852C22.814 20.4 21.85 22 20.343 22H3.657c-1.505 0-2.47-1.6-1.77-2.931L10.23 3.216zM20.344 20L12 4.147 3.656 20h16.688z"/>
                </svg>
                <p>Maximum atteint, supprimez des expressions pour en ajouter de nouvelles.</p>
            </div>
        <?php endif ?>


    <?php endif ?>
</div>

<!-- Modale de suppression d'expression -->
<dialog id="erase_exp_modal">
    <div class="modal_content modale_erase_criteria">
      <button class="close_popup close_desktop_only">X</button>

      <p>Êtes-vous sûr de vouloir supprimer cette expression ?</p>
      <form action method="POST">
        <input type="hidden" name="post_erase_exp_id" id="post_erase_exp_id" required>
        <div class="action_btns">
            <button class="close_popup">Annuler</button>
            <input type="submit" id="erase_exp_btn" value="Supprimer"></a>
        </div>
      </form>
    </div>
</dialog>

<!-- Modale d'ajout d'expression -->
<dialog id="add_exp_modal">
    <div class="modal_content modale_add_criteria">
      <button class="close_popup close_desktop_only">X</button>

      <form action method="POST">
        <input type="hidden" name="post_add_exp">
        <input type="hidden" name="post_add_type" id="post_add_type" required>

        <label for="exp_add_name">Ajouter une nouvelle expression</label>
        <input type="text" name="exp_add_name" id="exp_add_name" class="autofocus_target" placeholder="ex : Product Owner" required>
        <p id="exp_add_error" hidden></p>

        <div class="action_btns">
        <button class="close_popup">Annuler</button>
            <input type="submit" value="Ajouter">
        </div>
      </form>

    </div>
</dialog>

