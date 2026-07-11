

<h2 class="page_title">Espace CV</h2>
<p class="subtitles">Centralisez les différentes versions de votre CV au même endroit pour avoir toujours accès à la dernière version où que vous soyez.</p>

<!-- Container accès espace personnalisé -->
<div class="container emphasis">
    <div class="container_title">
        <h3>
            <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <rect width="48" height="48" fill="none"/>
                                </g>
                                <g>
                                    <path d="M28,4a2,2,0,0,0-2,2.3A2.1,2.1,0,0,0,28.1,8h9.1L24.7,20.5a2,2,0,0,0-.2,2.8A1.8,1.8,0,0,0,26,24a2,2,0,0,0,1.4-.6L40,10.8v9.1A2.1,2.1,0,0,0,41.7,22,2,2,0,0,0,44,20V6a2,2,0,0,0-2-2Z"/>
                                    <path d="M41.7,30A2.1,2.1,0,0,0,40,32.1V40H8V8h8a2,2,0,0,0,2-2.3A2.1,2.1,0,0,0,15.9,4H6A2,2,0,0,0,4,6V42a2,2,0,0,0,2,2H42a2,2,0,0,0,2-2V32A2,2,0,0,0,41.7,30Z"/>
                                </g>
                            </g>
                        </svg>
            Editeur de CV en ligne
        </h3>
    </div>

    <p class="comments">Vous gérez vos CV sur Canva, Figma ou une plateforme en ligne ? Renseignez le lien dans les paramètres pour y accéder directement ici.</p>

    <?php if(!empty($_SESSION['cv_link'])) : ?>
        <div class="interaction_btn">
            <a target="_blank" href="<?= $_SESSION['cv_link']; ?>">Editer en ligne</a>
        </div>
    <?php else : ?>
        <div class="interaction_btn">
            <a href="<?= BASE_URL ?>?mode=parameters">Renseignez votre lien</a>
        </div>
    <?php endif ?>
</div>

<!-- Container poids disponible -->
<div class="container cv_size_block 
<?php if($cvStorage['fileCount'] >= 10 || $userMo >= 6) : echo "max_size_reached"; endif ?>">
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



<p class="subtitles bold">Fichiers</p>

<!-- Bouton ajout -->
<div role="button" id="upload_new_cv" data-popup-id="upload_cv_modal" class="cv_add_button">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2h-6v6a1 1 0 1 1-2 0v-6H5a1 1 0 1 1 0-2h6V5a1 1 0 0 1 1-1z"/>
    </svg>
    <p>Ajouter un nouveau CV</p>
</div>

<div class="cv_listing_bloc">
    <?php foreach($cvList as $file) : ?>
        <div class="container cv_listing_item">
            <div class="container_title space_between">
                <h3>
                    <div class="cv_icon">
                        <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.71 4.29l-3-3L10 1H4L3 2v12l1 1h9l1-1V5l-.29-.71zM13 14H4V2h5v4h4v8zm-3-9V2l3 3h-3z"/>
                        </svg>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>src/api/view_cv.php?file=<?= urlencode($file['filename']) ?>&action=view" target="_blank">
                            <?= htmlspecialchars(str_replace('.store', '', $file['filename'])) ?>
                        </a>
                        <p class="cv_subinfos">Modifié le 
                            <?= $file['modified_at_date'] ?>
                        - <?= round($file['size'] / (1024), 0) ?> Ko</p>
                    </div>
                </h3>
                <div class="erase_cv_button" onclick="injectCvNameToDelete('<?= $file['filename'] ?>')" data-popup-id="erase_cv_modal">
                    <svg viewBox="-3.5 0 19 19" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.383 13.644A1.03 1.03 0 0 1 9.928 15.1L6 11.172 2.072 15.1a1.03 1.03 0 1 1-1.455-1.456l3.928-3.928L.617 5.79a1.03 1.03 0 1 1 1.455-1.456L6 8.261l3.928-3.928a1.03 1.03 0 0 1 1.455 1.456L7.455 9.716z"/>
                    </svg>
                </div>
            </div>
            <div class="cv_management_btns">
                <div data-popup-id="download_cv_modal" data-filename="<?= htmlspecialchars($file['filename']) ?>"
                data-displayname="<?= htmlspecialchars($file['display_name']) ?>"
                onclick="prepareDownloadModal(this)">
                    <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.50005 1.04999C7.74858 1.04999 7.95005 1.25146 7.95005 1.49999V8.41359L10.1819 6.18179C10.3576 6.00605 10.6425 6.00605 10.8182 6.18179C10.994 6.35753 10.994 6.64245 10.8182 6.81819L7.81825 9.81819C7.64251 9.99392 7.35759 9.99392 7.18185 9.81819L4.18185 6.81819C4.00611 6.64245 4.00611 6.35753 4.18185 6.18179C4.35759 6.00605 4.64251 6.00605 4.81825 6.18179L7.05005 8.41359V1.49999C7.05005 1.25146 7.25152 1.04999 7.50005 1.04999ZM2.5 10C2.77614 10 3 10.2239 3 10.5V12C3 12.5539 3.44565 13 3.99635 13H11.0012C11.5529 13 12 12.5528 12 12V10.5C12 10.2239 12.2239 10 12.5 10C12.7761 10 13 10.2239 13 10.5V12C13 13.1041 12.1062 14 11.0012 14H3.99635C2.89019 14 2 13.103 2 12V10.5C2 10.2239 2.22386 10 2.5 10Z"/>
                    </svg>
                    <p>Télécharger</p>
                </div>
                <div data-popup-id="update_cv_modal" onclick="injectCvNameToUpdate('<?= $file['filename'] ?>')">
                <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 14.5C3.63401 14.5 0.5 11.366 0.5 7.5C0.5 5.26904 1.54367 3.28183 3.1694 2M7.5 0.5C11.366 0.5 14.5 3.63401 14.5 7.5C14.5 9.73096 13.4563 11.7182 11.8306 13M11.5 10V13.5H15M0 1.5H3.5V5" stroke="#000000"/>
                </svg>
                    <p>Mettre à jour</p>
                </div>
            </div>
        </div>
    <?php endforeach ?>

</div>


<!-- Modale d'upload de cv -->
<dialog id="upload_cv_modal">
    <div class="modal_content">
      <button class="close_popup close_desktop_only">X</button>

        <form action method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_upload_cv">

            <p>Ajouter un nouveau CV</p>
            <div class="input_file_bloc" id="input_file_bloc">
                <label for="cv_upload_file">
                    <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.50005 1.04999C7.74858 1.04999 7.95005 1.25146 7.95005 1.49999V8.41359L10.1819 6.18179C10.3576 6.00605 10.6425 6.00605 10.8182 6.18179C10.994 6.35753 10.994 6.64245 10.8182 6.81819L7.81825 9.81819C7.64251 9.99392 7.35759 9.99392 7.18185 9.81819L4.18185 6.81819C4.00611 6.64245 4.00611 6.35753 4.18185 6.18179C4.35759 6.00605 4.64251 6.00605 4.81825 6.18179L7.05005 8.41359V1.49999C7.05005 1.25146 7.25152 1.04999 7.50005 1.04999ZM2.5 10C2.77614 10 3 10.2239 3 10.5V12C3 12.5539 3.44565 13 3.99635 13H11.0012C11.5529 13 12 12.5528 12 12V10.5C12 10.2239 12.2239 10 12.5 10C12.7761 10 13 10.2239 13 10.5V12C13 13.1041 12.1062 14 11.0012 14H3.99635C2.89019 14 2 13.103 2 12V10.5C2 10.2239 2.22386 10 2.5 10Z"/>
                    </svg>
                    Télécharger votre nouveau CV</label>
                <input type="file" name="cv_upload_file" id="cv_upload_file" accept=".pdf" required>
                <p id="cv_uploaded_name" class="underline"></p>
            </div>

            <label for="cv_upload_name">Modifier le nom du fichier (optionnel) :</label>
            <input type="text" placeholder="Ex : CV Chef de projet (agence)" id="cv_upload_name" name="cv_upload_name" required>

            <div class="action_btns">
                <button type="button" class="close_popup">Annuler</button>
                <input type="submit" value="Ajouter">
            </div>
        </form>

    </div>
</dialog>


<!-- Modale de suppression de cv -->
<dialog id="erase_cv_modal">
    <div class="modal_content">
      <button class="close_popup close_desktop_only">X</button>

      <p>Êtes-vous sûr de vouloir supprimer ce CV ?</p>
      <form action method="POST">
        <input type="hidden" name="post_delete_cv_name" id="post_delete_cv_name" required>
        <div class="action_btns">
            <button type="button" class="close_popup">Annuler</button>
            <input type="submit" class="erase_button" value="Supprimer">
        </div>
      </form>
    </div>
</dialog>


<!-- Modale de remplacement de cv -->
<dialog id="update_cv_modal">
    <div class="modal_content">
      <button class="close_popup close_desktop_only">X</button>

      <form action method="POST" enctype="multipart/form-data">
        <input type="hidden" name="post_update_cv_name" id="post_update_cv_name" required>

        <p>Remplacer ce CV par un autre fichier</p>
        <p>Fichier à remplacer :</p>
        <p id="cv_to_update"></p>
            <div class="input_file_update_bloc" id="input_file_update_bloc">
                <label for="cv_update_file">
                    <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.50005 1.04999C7.74858 1.04999 7.95005 1.25146 7.95005 1.49999V8.41359L10.1819 6.18179C10.3576 6.00605 10.6425 6.00605 10.8182 6.18179C10.994 6.35753 10.994 6.64245 10.8182 6.81819L7.81825 9.81819C7.64251 9.99392 7.35759 9.99392 7.18185 9.81819L4.18185 6.81819C4.00611 6.64245 4.00611 6.35753 4.18185 6.18179C4.35759 6.00605 4.64251 6.00605 4.81825 6.18179L7.05005 8.41359V1.49999C7.05005 1.25146 7.25152 1.04999 7.50005 1.04999ZM2.5 10C2.77614 10 3 10.2239 3 10.5V12C3 12.5539 3.44565 13 3.99635 13H11.0012C11.5529 13 12 12.5528 12 12V10.5C12 10.2239 12.2239 10 12.5 10C12.7761 10 13 10.2239 13 10.5V12C13 13.1041 12.1062 14 11.0012 14H3.99635C2.89019 14 2 13.103 2 12V10.5C2 10.2239 2.22386 10 2.5 10Z"/>
                    </svg>
                    Insérer le nouveau CV</label>
                <input type="file" name="cv_update_file" id="cv_update_file" accept=".pdf" required>
                <p id="cv_update_name" class="underline"></p>
            </div>

        <div class="action_btns">
            <button type="button" class="close_popup">Annuler</button>
            <input type="submit" class="erase_button" value="Remplacer">
        </div>
      </form>
    </div>
</dialog>


<!-- Modale de téléchargement d'un cv -->
<dialog id="download_cv_modal">
    <div class="modal_content">
      <button class="close_popup close_desktop_only">X</button>

      <p>Quel nom souhaitez-vous donner au CV à télécharger ?</p>
      <input type="text" id="download_cv_name_input">
        <div class="action_btns">
            <button type="button" class="close_popup">Annuler</button>
            <a id="download_cv_confirm_btn" class="standard_cta">Télécharger</a>
        </div>
    </div>
</dialog>

