

<h2 class="page_title">Espace CV</h2>
<p class="subtitles">Centralisez les différentes versions de votre CV au même endroit pour avoir toujours accès à la dernière version où que vous soyez.</p>

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



<div class="cv_downloader_container">
    <a target="_blank" download class="cv_downloader_title" href="https://fneto-prod.fr/jobsfinder/public/files/CV-id1.pdf">
        <p>CV agence - CP web</p>
    </a>

    <a target="_blank" download class="cv_downloader_title" href="https://fneto-prod.fr/jobsfinder/public/files/CV-id2.pdf">
        <p>CV agence - CP marketing</p>
    </a>

    <a target="_blank" download class="cv_downloader_title" href="https://fneto-prod.fr/jobsfinder/public/files/CV-id3.pdf">
        <p>CV that makes sense - CP web</p>
    </a>

    <a target="_blank" download class="cv_downloader_title" href="https://fneto-prod.fr/jobsfinder/public/files/CV-id4.pdf">
        <p>CV that makes sense - CP marketing</p>
    </a>

    <a target="_blank" download class="cv_downloader_title" href="https://fneto-prod.fr/jobsfinder/public/files/CV-id5.pdf">
        <p>CV that makes sense - Général (Candidature spont.)</p>
    </a>
</div>

<!-- Dev note :
 Fait comme ça avant départ en week-end, mais sera à corriger en plus clean.
 Faudrait que les CV soient listés dans un JSON qui leur donne un ID et stocke
 leur nom de fichier, comme ça pas en brut mais généré dynamiquement depuis le JSON.
 Si ajout => créé dans le JSON, recréé auto dynamiquement + permet de leur mettre 
 un data-id pour savoir lesquels modifier/remplacer etc. et qu'ils soient à jour au 
 prochain chargement
-->
