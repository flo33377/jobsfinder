
<?php 
/* echo '<pre>';
print_r($allJobsArray);
echo '<pre>'; */
?>

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
                <h4><?= $job['company'] ?> - <?= $job['location'] ?></h4>
                <?php if($job['posted_at']) : ?>
                    <h5>Posté le <?= $job['posted_at'] ?></h5>
                <?php endif ?>

                <!-- Etiquette de la card -->
                <div class="job_tags">
                    <div class="job_status">
                        <?php switch($job['status']) {
                            case "visible" : echo "Visible"; break;
                            case "hidden" : echo "Masquée"; break;
                            case "applied" : echo "Postulée"; break;
                            default : echo "Visible"; break;
                        } ?>
                    </div>

                    <?php if($job['new'] == "true") : ?>
                        <p class="job_status new_offer_tag">New</p>
                    <?php endif ?>
                </div>

            </label>

            <!-- Infos supp de l'annonce -->
            <div class="infos">
                <!-- n'affiche que 800 cara et si dépasse, met "..." après -->
                <p><?= mb_substr($job['description'], 0, 800, 'UTF-8') ?>
                <?= mb_strlen($job['description'], 'UTF-8') > 800 ? '...' : '' ?></p>

                <div class="action_btn">
                    <a href="<?= $job['url'] ?>" target="_blank" class="main_cta">Voir l'annonce</a>
                    <?php if($job['status'] == "visible") : ?>
                        <button type="button" onclick="updateInDBOfferStatus('<?= $job['id'] ?>', 'hidden')" class="second_cta">Masquer l'offre</button>
                    <?php elseif($job['status'] == "hidden") : ?>
                        <button type="button" onclick="updateInDBOfferStatus('<?= $job['id'] ?>', 'visible')" class="second_cta">Ne plus masquer</button>
                    <?php endif ?>
                    <?php if($job['status'] == "visible") : ?>
                        <button type="button" onclick="updateInDBOfferStatus('<?= $job['id'] ?>', 'applied')" class="second_cta">J'ai postulé</button>
                    <?php endif ?>
                </div>
            </div>
        </div>

    <?php endforeach ?>
</div>

