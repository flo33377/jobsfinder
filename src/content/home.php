
<?php 
/* echo '<pre>';
print_r($allJobsArray);
echo '<pre>'; */
?>


<?php if($userIsPaused) : ?>
<!-- Avertissement si compte en pause -->
<div class="pause-banner">
    <div class="pause-banner-text">
        <p class="pause-banner-title">Votre recherche est en pause</p>
        <p class="pause-banner-desc">Aucune nouvelle offre n'est importée. Relancez un import manuel ou réactivez votre compte dans les paramètres pour reprendre.</p>
    </div>
    <a href="<?= BASE_URL ?>?action=unset_pause" class="pause-banner-cta">Réactiver</a>
</div>
<?php endif ?>


<?php if(!$userIsPaused && count($userKeywords) <= 0) : ?>
<!-- Avertissement si pas d'expression clé renseignée -->
<div class="info_no_exp">
    <div class="no_exp_banner_message">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 14a1 1 0 0 1-1-1v-3a1 1 0 1 1 2 0v3a1 1 0 0 1-1 1zm-1.5 2.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
            <path d="M10.23 3.216c.75-1.425 2.79-1.425 3.54 0l8.343 15.852C22.814 20.4 21.85 22 20.343 22H3.657c-1.505 0-2.47-1.6-1.77-2.931L10.23 3.216zM20.344 20L12 4.147 3.656 20h16.688z"/>
        </svg>
        <p>Aucune expression clé enregistrée. Ajoutez-en pour recevoir des offres.</p>
    </div>
    <a href="<?= BASE_URL ?>?mode=criterias">Ajouter une expression clé</a>
</div>
<?php endif ?>


<!-- Début du compteur d'offres -->

<?php 
$visibleJobs = array_filter($allJobsArray, function($job) {
    return $job['status'] === 'visible';
});
?>

<div id="parameters_bar">
    <p>
        <span id="visible_count"><?= count($visibleJobs) ?></span> offres visibles sur 
        <span id="total_count"><?= count($allJobsArray) ?></span> offres.
    </p>

    <div id="options_bar">
        <div class="filter">
            <label for="offers_displayed">Filtrer :</label>
            <select id="offers_displayed" name="offers_displayed">
                <option value="all">Toutes les offres</option>
                <option value="visible_only" selected>Non-masquées</option>
                <option value="applied_only">Postulées</option>
            </select>
        </div>

        <details id="details_options">
            <summary>Plus d'options</summary>
            <div id="details_options_content">

                <div id="search_bar">
                    <p>Rechercher :</p>
                    <input type="text" id='search_content' placeholder="Entrez un titre ou un mot">
                </div>
            
                <div class="filter">
                    <label for="sort_offers">Trier par :</label>
                    <select id="sort_offers" name="sort_offers">
                        <option value="newest" selected>Plus récent</option>
                        <option value="oldest">Plus ancien</option>
                    </select>
                </div>

                <div class="action_btn" id="manual_refresh_bloc">
                    <button type="button" class="main_cta" id="refresh_btn">Rafraîchir l'import</button>
                </div>

            </div>
        </details>
    </div>

</div>

<!-- Fin du compteur d'offre -->

<div id="job_board">

    <?php $first = true ?>
    <?php foreach($allJobsArray as $job) : ?>
        <!-- Card -->
        <div class="job_card <?= $job["status"] ?>" data-status="<?= $job['status'] ?>" data-id="<?= $job['id'] ?>" data-date="<?= $job['posted_at'] ?>">

            <input type="radio" id="<?= $job['source_id'] ?>-<?= $job['source'] ?>" name="focus" 
            <?php if($job['status'] === "visible" && $first) : ?> checked <?php $first = false; endif ?>>
            <!-- input pour afficher -->

            <label for="<?= $job['source_id'] ?>-<?= $job['source'] ?>">
                <!-- Contenu affiché par defaut -->
                <h3><?= $job['title'] ?></h3>

                <h4><?= $job['company'] ?>
                <?php if($job['company'] && $job['location']) : ?> - 
                <?php endif ?>
                <?= $job['location'] ?></h4>

                <?php if($job['posted_at']) : ?>
                    <div class="offer_timestamp">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.09814,12.63379,13,11.42285V7a1,1,0,0,0-2,0v5a.99985.99985,0,0,0,.5.86621l2.59814,1.5a1.00016,1.00016,0,1,0,1-1.73242ZM12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Z"/>
                        </svg>
                        <h5><?= formatPostedAt($job['posted_at']) ?></h5>
                    </div>
                <?php endif ?>

                <!-- Etiquette de la card -->
                <div class="job_tags">
                    <div class="job_status">
                        <?php switch($job['status']) {
                            case "visible" :
                                echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18c-3.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z"/></svg>';
                                echo "<p>Visible</p>";
                                break;
                            case "hidden" :
                                echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M20.0980654,15.8909586 L18.6838245,14.4767177 C19.3180029,13.8356474 19.9009094,13.1592525 20.4222529,12.4831239 C20.5528408,12.3137648 20.673512,12.1521776 20.7838347,12 C20.673512,11.8478224 20.5528408,11.6862352 20.4222529,11.5168761 C19.8176112,10.7327184 19.1301624,9.94820254 18.37596,9.21885024 C16.2825083,7.1943753 14.1050769,6 12,6 C11.4776994,6 10.9509445,6.07352686 10.4221233,6.21501656 L8.84014974,4.63304296 C9.8725965,4.22137709 10.9270589,4 12,4 C14.7275481,4 17.3356792,5.4306247 19.76629,7.78114976 C20.5955095,8.58304746 21.3456935,9.43915664 22.0060909,10.2956239 C22.4045936,10.8124408 22.687526,11.2189945 22.8424353,11.4612025 L23.1870348,12 L22.8424353,12.5387975 C22.687526,12.7810055 22.4045936,13.1875592 22.0060909,13.7043761 C21.4349259,14.4451181 20.7965989,15.1855923 20.0980652,15.8909583 L20.0980654,15.8909586 Z M17.0055388,18.4197523 C15.3942929,19.4304919 13.7209154,20 12,20 C9.27245185,20 6.66432084,18.5693753 4.23371003,16.2188502 C3.40449054,15.4169525 2.65430652,14.5608434 1.99390911,13.7043761 C1.59540638,13.1875592 1.31247398,12.7810055 1.15756471,12.5387975 L0.812965202,12 L1.15756471,11.4612025 C1.31247398,11.2189945 1.59540638,10.8124408 1.99390911,10.2956239 C2.65430652,9.43915664 3.40449054,8.58304746 4.23371003,7.78114976 C4.6043191,7.42275182 4.9790553,7.0857405 5.35771268,6.77192624 L1.29289322,2.70710678 L2.70710678,1.29289322 L22.7071068,21.2928932 L21.2928932,22.7071068 L17.0055388,18.4197523 Z M6.77972015,8.19393371 C6.39232327,8.50634201 6.00677809,8.84872289 5.62403997,9.21885024 C4.86983759,9.94820254 4.18238879,10.7327184 3.57774714,11.5168761 C3.44715924,11.6862352 3.32648802,11.8478224 3.21616526,12 C3.32648802,12.1521776 3.44715924,12.3137648 3.57774714,12.4831239 C4.18238879,13.2672816 4.86983759,14.0517975 5.62403997,14.7811498 C7.71749166,16.8056247 9.89492315,18 12,18 C13.1681669,18 14.3586152,17.6321975 15.5446291,16.9588426 L14.0319673,15.4461809 C13.4364541,15.7980706 12.7418086,16 12,16 C9.790861,16 8,14.209139 8,12 C8,11.2581914 8.20192939,10.5635459 8.55381909,9.96803265 L6.77972015,8.19393371 Z M10.0677432,11.4819568 C10.0235573,11.6471834 10,11.8208407 10,12 C10,13.1045695 10.8954305,14 12,14 C12.1791593,14 12.3528166,13.9764427 12.5180432,13.9322568 L10.0677432,11.4819568 Z"/>
                                    </svg>';
                                echo "Masquée";
                                break;
                            case "applied" :
                                echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10,18a1,1,0,0,1-.71-.29l-5-5a1,1,0,0,1,1.42-1.42L10,15.59l8.29-8.3a1,1,0,1,1,1.42,1.42l-9,9A1,1,0,0,1,10,18Z"></path></svg>';
                                echo "Postulée";
                                break;
                            default :
                                echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18c-3.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z"/></svg>';
                                echo "Visible";
                                break;
                        } ?>
                    </div>

                    <?php if($job['new'] == "true") : ?>
                        <div class="job_status new_offer_tag">
                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path d="M18,11a1,1,0,0,1-1,1,5,5,0,0,0-5,5,1,1,0,0,1-2,0,5,5,0,0,0-5-5,1,1,0,0,1,0-2,5,5,0,0,0,5-5,1,1,0,0,1,2,0,5,5,0,0,0,5,5A1,1,0,0,1,18,11Z"/>
                                    <path d="M19,24a1,1,0,0,1-1,1,2,2,0,0,0-2,2,1,1,0,0,1-2,0,2,2,0,0,0-2-2,1,1,0,0,1,0-2,2,2,0,0,0,2-2,1,1,0,0,1,2,0,2,2,0,0,0,2,2A1,1,0,0,1,19,24Z"/>
                                    <path d="M28,17a1,1,0,0,1-1,1,4,4,0,0,0-4,4,1,1,0,0,1-2,0,4,4,0,0,0-4-4,1,1,0,0,1,0-2,4,4,0,0,0,4-4,1,1,0,0,1,2,0,4,4,0,0,0,4,4A1,1,0,0,1,28,17Z"/>
                                </g>
                            </svg>
                            <p>Nouveau</p>
                        </div>
                    <?php endif ?>
                </div>

            </label>

            <!-- Infos supp de l'annonce -->
            <div class="infos">
                <!-- n'affiche que 800 cara et si dépasse, met "..." après -->
                <p><?= mb_substr($job['description'], 0, 600, 'UTF-8') ?>
                <?= mb_strlen($job['description'], 'UTF-8') > 600 ? '...' : '' ?></p>

                <div class="action_btn">
                    <!-- CTA Voir l'annonce -->
                    <a href="<?= $job['url'] ?>" target="_blank" class="main_cta">
                        <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <rect width="48" height="48" fill="none"/>
                                </g>
                                <g id="icons_Q2" data-name="icons Q2">
                                    <path d="M28,4a2,2,0,0,0-2,2.3A2.1,2.1,0,0,0,28.1,8h9.1L24.7,20.5a2,2,0,0,0-.2,2.8A1.8,1.8,0,0,0,26,24a2,2,0,0,0,1.4-.6L40,10.8v9.1A2.1,2.1,0,0,0,41.7,22,2,2,0,0,0,44,20V6a2,2,0,0,0-2-2Z"/>
                                    <path d="M41.7,30A2.1,2.1,0,0,0,40,32.1V40H8V8h8a2,2,0,0,0,2-2.3A2.1,2.1,0,0,0,15.9,4H6A2,2,0,0,0,4,6V42a2,2,0,0,0,2,2H42a2,2,0,0,0,2-2V32A2,2,0,0,0,41.7,30Z"/>
                                </g>
                            </g>
                        </svg>
                        <p>Voir l'annonce</p>
                    </a>

                    <?php if($job['status'] == "visible") : ?> <!-- CTA Masquer -->
                        <button type="button" onclick="updateInDBOfferStatus('<?= $job['id'] ?>', 'hidden')" class="second_cta">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M20.0980654,15.8909586 L18.6838245,14.4767177 C19.3180029,13.8356474 19.9009094,13.1592525 20.4222529,12.4831239 C20.5528408,12.3137648 20.673512,12.1521776 20.7838347,12 C20.673512,11.8478224 20.5528408,11.6862352 20.4222529,11.5168761 C19.8176112,10.7327184 19.1301624,9.94820254 18.37596,9.21885024 C16.2825083,7.1943753 14.1050769,6 12,6 C11.4776994,6 10.9509445,6.07352686 10.4221233,6.21501656 L8.84014974,4.63304296 C9.8725965,4.22137709 10.9270589,4 12,4 C14.7275481,4 17.3356792,5.4306247 19.76629,7.78114976 C20.5955095,8.58304746 21.3456935,9.43915664 22.0060909,10.2956239 C22.4045936,10.8124408 22.687526,11.2189945 22.8424353,11.4612025 L23.1870348,12 L22.8424353,12.5387975 C22.687526,12.7810055 22.4045936,13.1875592 22.0060909,13.7043761 C21.4349259,14.4451181 20.7965989,15.1855923 20.0980652,15.8909583 L20.0980654,15.8909586 Z M17.0055388,18.4197523 C15.3942929,19.4304919 13.7209154,20 12,20 C9.27245185,20 6.66432084,18.5693753 4.23371003,16.2188502 C3.40449054,15.4169525 2.65430652,14.5608434 1.99390911,13.7043761 C1.59540638,13.1875592 1.31247398,12.7810055 1.15756471,12.5387975 L0.812965202,12 L1.15756471,11.4612025 C1.31247398,11.2189945 1.59540638,10.8124408 1.99390911,10.2956239 C2.65430652,9.43915664 3.40449054,8.58304746 4.23371003,7.78114976 C4.6043191,7.42275182 4.9790553,7.0857405 5.35771268,6.77192624 L1.29289322,2.70710678 L2.70710678,1.29289322 L22.7071068,21.2928932 L21.2928932,22.7071068 L17.0055388,18.4197523 Z M6.77972015,8.19393371 C6.39232327,8.50634201 6.00677809,8.84872289 5.62403997,9.21885024 C4.86983759,9.94820254 4.18238879,10.7327184 3.57774714,11.5168761 C3.44715924,11.6862352 3.32648802,11.8478224 3.21616526,12 C3.32648802,12.1521776 3.44715924,12.3137648 3.57774714,12.4831239 C4.18238879,13.2672816 4.86983759,14.0517975 5.62403997,14.7811498 C7.71749166,16.8056247 9.89492315,18 12,18 C13.1681669,18 14.3586152,17.6321975 15.5446291,16.9588426 L14.0319673,15.4461809 C13.4364541,15.7980706 12.7418086,16 12,16 C9.790861,16 8,14.209139 8,12 C8,11.2581914 8.20192939,10.5635459 8.55381909,9.96803265 L6.77972015,8.19393371 Z M10.0677432,11.4819568 C10.0235573,11.6471834 10,11.8208407 10,12 C10,13.1045695 10.8954305,14 12,14 C12.1791593,14 12.3528166,13.9764427 12.5180432,13.9322568 L10.0677432,11.4819568 Z"/>
                            </svg>
                            <p>Masquer</p></button>
                    <?php elseif($job['status'] == "hidden") : ?> <!-- CTA Ne plus masquer -->
                        <button type="button" onclick="updateInDBOfferStatus('<?= $job['id'] ?>', 'visible')" class="second_cta">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18c-3.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z"/></svg>
                            <p>Ne plus masquer</p></button>
                    <?php endif ?>
                    <?php if($job['status'] == "visible") : ?> <!-- CTA J'ai postulé -->
                        <button type="button" onclick="updateInDBOfferStatus('<?= $job['id'] ?>', 'applied')" class="second_cta applied_btn">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10,18a1,1,0,0,1-.71-.29l-5-5a1,1,0,0,1,1.42-1.42L10,15.59l8.29-8.3a1,1,0,1,1,1.42,1.42l-9,9A1,1,0,0,1,10,18Z"></path></svg>
                            <p>J'ai postulé</p></button>
                    <?php endif ?>
                </div>
            </div>
        </div>

    <?php endforeach ?>
</div>

<div id="btn_up">
    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M13 30L25 18L37 30" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</div>

