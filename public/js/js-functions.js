
/* Mise à jour des status des offres par l'utilisateur*/

function updateCounts() {
    const total = document.querySelectorAll(".job_card").length;
    const visible = document.querySelectorAll(".job_card.visible").length;

    document.getElementById("visible_count").textContent = visible;
    document.getElementById("total_count").textContent = total;
};


async function updateInDBOfferStatus(id, newStatus) {
    // récup les infos pour remplir le fetch
    const formdata = new FormData();
    formdata.append("id", id);
    formdata.append("status", newStatus);

    // set par défaut le résultat de l'opé à false
    let success = false;

    // fetch le fichier d'update et récupère la réponse
    try {
        const response = await fetch("./src/api/updateStatusTrigger.php", {
            method: "POST",
            body: formdata
        });

        const text = await response.text();
        /* console.log("Réponse PHP :", text); */

        success = text.trim() === "success";

    } catch (error) {
        console.error("Erreur fetch :", error);
    }

    // Stop ici si la requête a échoué
    if (!success) {
        showNotification("Une erreur s'est produite, veuillez réessayer.", "error");
        return;
    }

    // Récupère la card et le filtre actif
    const card = document.querySelector(`[data-id="${id}"]`);
    const currentFilter = document.getElementById("offers_displayed").value;

    const shouldDisappear = 
        currentFilter === "visible_only" && 
        (newStatus === "hidden" || newStatus === "applied");

    // Trouve la prochaine card visible dans le DOM pour aller l'ouvrir
    const allCards = Array.from(document.querySelectorAll('.job_card:not(.filtered_out)'));
    const currentIndex = allCards.indexOf(card);
    const nextCard = allCards[currentIndex + 1];
    // s'il y a une prochaine card, nextCard vaut quelque chose => cardToOpenNext prend sa valeur
    // si pas de prochaine card, nextCard vaut null ou undefined et donc prend la valeur de fallback (la 2e)
    const cardToOpenNext = nextCard || allCards[currentIndex - 1];

    if (shouldDisappear) {
        // Animation de disparition puis mise à jour
        card.style.transition = "opacity 0.3s ease";
        card.style.opacity = "0";

        setTimeout(() => {
            card.style.transition = "height 0.4s ease, padding 0.4s ease, margin 0.4s ease";
            card.style.overflow = "hidden";
            card.style.height = card.offsetHeight + "px"; // fixe la hauteur avant animation
            
            requestAnimationFrame(() => {
                card.style.height = "0";
                card.style.padding = "0";
                card.style.margin = "0";
            });

            setTimeout(() => {
                updateCardDOM(card, newStatus);
                card.classList.add("filtered_out"); // masquage visuel séparé
                // Remet les styles inline pour ne pas bloquer un futur réaffichage
                card.style = "";

                // Ouvre la card suivante
                if (cardToOpenNext) {
                const radio = cardToOpenNext.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
                }
            }, 400);

        }, 300);

    } else {
        // Pas d'animation, juste mise à jour du DOM
        updateCardDOM(card, newStatus);
    }
};


function updateCardDOM(card, newStatus) {

    // 1. Met à jour data-status
    card.dataset.status = newStatus;

    // 2. Met à jour la classe de la card
    card.classList.remove("visible", "hidden", "applied");
    card.classList.add(newStatus);

    // Supprime le tag "new" si présent
    const newTag = card.querySelector('.new_offer_tag'); // adapte le sélecteur à la classe
    if (newTag) newTag.remove();

    // 3. Met à jour le label de statut
    const statusLabels = { visible: "Visible", hidden: "Masquée", applied: "Postulée" };
    card.querySelector(".job_status").textContent = statusLabels[newStatus] || "Visible";

    // 4. Met à jour les boutons
    const actionBtn = card.querySelector(".action_btn");
    const buttonsHTML = {
        visible: `
            <button type="button" class="second_cta" onclick="updateInDBOfferStatus('${card.dataset.id}', 'hidden')">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M20.0980654,15.8909586 L18.6838245,14.4767177 C19.3180029,13.8356474 19.9009094,13.1592525 20.4222529,12.4831239 C20.5528408,12.3137648 20.673512,12.1521776 20.7838347,12 C20.673512,11.8478224 20.5528408,11.6862352 20.4222529,11.5168761 C19.8176112,10.7327184 19.1301624,9.94820254 18.37596,9.21885024 C16.2825083,7.1943753 14.1050769,6 12,6 C11.4776994,6 10.9509445,6.07352686 10.4221233,6.21501656 L8.84014974,4.63304296 C9.8725965,4.22137709 10.9270589,4 12,4 C14.7275481,4 17.3356792,5.4306247 19.76629,7.78114976 C20.5955095,8.58304746 21.3456935,9.43915664 22.0060909,10.2956239 C22.4045936,10.8124408 22.687526,11.2189945 22.8424353,11.4612025 L23.1870348,12 L22.8424353,12.5387975 C22.687526,12.7810055 22.4045936,13.1875592 22.0060909,13.7043761 C21.4349259,14.4451181 20.7965989,15.1855923 20.0980652,15.8909583 L20.0980654,15.8909586 Z M17.0055388,18.4197523 C15.3942929,19.4304919 13.7209154,20 12,20 C9.27245185,20 6.66432084,18.5693753 4.23371003,16.2188502 C3.40449054,15.4169525 2.65430652,14.5608434 1.99390911,13.7043761 C1.59540638,13.1875592 1.31247398,12.7810055 1.15756471,12.5387975 L0.812965202,12 L1.15756471,11.4612025 C1.31247398,11.2189945 1.59540638,10.8124408 1.99390911,10.2956239 C2.65430652,9.43915664 3.40449054,8.58304746 4.23371003,7.78114976 C4.6043191,7.42275182 4.9790553,7.0857405 5.35771268,6.77192624 L1.29289322,2.70710678 L2.70710678,1.29289322 L22.7071068,21.2928932 L21.2928932,22.7071068 L17.0055388,18.4197523 Z M6.77972015,8.19393371 C6.39232327,8.50634201 6.00677809,8.84872289 5.62403997,9.21885024 C4.86983759,9.94820254 4.18238879,10.7327184 3.57774714,11.5168761 C3.44715924,11.6862352 3.32648802,11.8478224 3.21616526,12 C3.32648802,12.1521776 3.44715924,12.3137648 3.57774714,12.4831239 C4.18238879,13.2672816 4.86983759,14.0517975 5.62403997,14.7811498 C7.71749166,16.8056247 9.89492315,18 12,18 C13.1681669,18 14.3586152,17.6321975 15.5446291,16.9588426 L14.0319673,15.4461809 C13.4364541,15.7980706 12.7418086,16 12,16 C9.790861,16 8,14.209139 8,12 C8,11.2581914 8.20192939,10.5635459 8.55381909,9.96803265 L6.77972015,8.19393371 Z M10.0677432,11.4819568 C10.0235573,11.6471834 10,11.8208407 10,12 C10,13.1045695 10.8954305,14 12,14 C12.1791593,14 12.3528166,13.9764427 12.5180432,13.9322568 L10.0677432,11.4819568 Z"/>
            </svg>
            <p>Masquer</p></button>
            <button type="button" class="second_cta" onclick="updateInDBOfferStatus('${card.dataset.id}', 'applied')">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10,18a1,1,0,0,1-.71-.29l-5-5a1,1,0,0,1,1.42-1.42L10,15.59l8.29-8.3a1,1,0,1,1,1.42,1.42l-9,9A1,1,0,0,1,10,18Z"></path></svg>
            <p>J'ai postulé</p></button>
        `,
        hidden: `
            <button type="button" class="second_cta" onclick="updateInDBOfferStatus('${card.dataset.id}', 'visible')">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18c-3.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z"/></svg>
            <p>Ne plus masquer</p></button>
        `,
        applied: ``
    };

    // Reconstruit les boutons en gardant le lien "Voir l'annonce"
    const link = actionBtn.querySelector("a").outerHTML;
    actionBtn.innerHTML = link + (buttonsHTML[newStatus] || "");

    updateCounts();
};


/* Imports et cleanage manuels des offres via le bouton Refresh */

    // set l'API WakeLock pour empêcher le verrouillage de l'écran pendant l'import
let wakeLock = null;

async function requestWakeLock() {
    try {
        wakeLock = await navigator.wakeLock.request('screen');
    } catch (err) {
        console.log('Wake Lock non disponible : ', err);
    }
}

function releaseWakeLock() {
    if (wakeLock) {
        wakeLock.release();
        wakeLock = null;
    }
}

const refreshBtn = document.getElementById('refresh_btn');
if(refreshBtn) {
    refreshBtn.addEventListener('click', async () => {

        // désactive le bouton et affiche l'animation
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<span class="spinner"></span> Recherche en cours...';

        await requestWakeLock(); // empêche le verrouillage d'écran

        try {
            const response = await fetch('./src/api/globalDBUpdate.php');
            const data = await response.json();

            // Si le serveur a renvoyé une erreur métier (catch côté PHP)
            if (data.error) {
                // throw une erreur, donc arrête le script et part dans le catch
                throw new Error(data.error);
            }

            const newOffers = data.inserted;
            if(newOffers >= 1) {
                refreshBtn.textContent = `✓ ${data.inserted} nouvelles offres trouvées`;
                // ajoute un lien discret en dessous
                const reloadLink = document.createElement('a');
                reloadLink.href = '';
                reloadLink.textContent = 'Actualiser la page pour les voir';
                refreshBtn.insertAdjacentElement('afterend', reloadLink);
                // augmente taille du bloc pour afficher
                const blocContent = document.getElementById('details_options_content');
                blocContent.style.maxHeight = content.scrollHeight + 'px';
            } else {
                refreshBtn.textContent = `Aucune nouvelle offre`;
                // réactive le bouton après 60 secondes
                setTimeout(() => {
                    refreshBtn.textContent = "Rafraîchir les offres";
                    refreshBtn.disabled = false;
                }, 20000);
            }

        } catch (error) {
            refreshBtn.textContent = "Erreur lors de l'import";
            console.error(error);

            // réactive le bouton après 60 secondes
            setTimeout(() => {
            refreshBtn.textContent = "Rafraîchir les offres";
            refreshBtn.disabled = false;
        }, 20000);
        } finally {
            releaseWakeLock(); // retire le blocage du verrouillage d'écran
        }

    });
}

    /* Mise à jour des expressions clés et blacklist en DB */

/* Injection dynamique de l'id d'une expression dans popup suppression */

function injectExpIdToErase(id) {
    document.getElementById('post_erase_exp_id').setAttribute('value', id);
}

/* Injection dynamique du type d'expression à créer dans popup ajout 
et du texte */
function injectExpTypeToAdd(type) {
    typeExpToCreateInput = document.getElementById('post_add_type');
    typeExpToCreateInput.setAttribute('value', type);
}

// Check de la longueur de l'expression entrée
const expInput = document.getElementById('exp_add_name');
const submitBtn = document.querySelector('#add_exp_modal input[type="submit"]');
const errorMsg = document.getElementById('exp_add_error');

if(expInput) {
    expInput.addEventListener('input', () => {
        const val = expInput.value.trim();
        
        if (val.length > 0 && val.length < 3) {
            errorMsg.textContent = "L'expression doit contenir au moins 3 caractères.";
            errorMsg.hidden = false;
            submitBtn.disabled = true;
        } else if (val.length > 50) {
            errorMsg.textContent = "L'expression ne peut pas dépasser 50 caractères.";
            errorMsg.hidden = false;
            submitBtn.disabled = true;
        } else {
            errorMsg.hidden = true;
            submitBtn.disabled = false;
        }
    });
}

/* ATTENTION 

// OBSOLETE => Ancienne fonction de suppression en requête AJAX

 ATTENTION */
async function eraseExpressionInDB(id) { 
    // la fonction doit être async pour intégrer await
    const formdata = new FormData();
    formdata.append("id", id);

    // Bloque les interactions pendant l'opération
    document.getElementById('keywords_container').classList.add('loading');
    document.getElementById('blacklist_container').classList.add('loading');

    try {
        const response = await fetch("./src/api/eraseExpressionTrigger.php", {
            method: "POST",
            body: formdata
        });
        const text = await response.text();

        if (text.trim() === "wrongUser") {
            showNotification("Une erreur s'est produite. Merci de ré-essayer plus tard.", "error");
        } else if (text.trim() === "success") {
            // Supprime l'élément du DOM
            const item = document.querySelector(`[data-id="${id}"]`);
            if (item) {
                item.classList.add('fade-away');
                setTimeout(() => {
                    item.remove();
                    item.classList.remove('fade-away');
                }, 500);
            }
            showNotification("Expression supprimée avec succès.", "success");
        } else if (text.trim() === "error") {
            showNotification("Une erreur s'est produite. Merci de ré-essayer plus tard.", "error");
        }

    } catch(error) {
        console.error("Erreur fetch :", error);
        showNotification("Une erreur réseau s'est produite.", "error");
    } finally {
        // finally s'exécute toujours, succès ou erreur
        document.getElementById('keywords_container').classList.remove('loading');
        document.getElementById('blacklist_container').classList.remove('loading');
    }
}


/* Activation de la saisie dans le champ URL de suivi + Espace CV (page paramètres) */

reportingURLInput = document.getElementById('reporting_link');
if(reportingURLInput) {
    reportingUrlChangeBtn = document.getElementById('change_reporting_url_btn');

    reportingUrlChangeBtn.addEventListener('click', (e) => {
        // Si le champ est désactivé, on s'apprète à modifier => on empêche le submit
        if (reportingURLInput.disabled) {
            e.preventDefault();
            reportingURLInput.disabled = false;
            reportingURLInput.focus();
            reportingUrlChangeBtn.value = 'Sauvegarder';
        }
        // Si le champ est actif, on laisse le submit se faire normalement
    });
}

CvURLInput = document.getElementById('cv_link');
if(CvURLInput) {
    CvUrlChangeBtn = document.getElementById('change_cv_link_btn');

    CvUrlChangeBtn.addEventListener('click', (e) => {
        // Si le champ est désactivé, on s'apprète à modifier => on empêche le submit
        if (CvURLInput.disabled) {
            e.preventDefault();
            CvURLInput.disabled = false;
            CvURLInput.focus();
            CvUrlChangeBtn.value = 'Sauvegarder';
        }
        // Si le champ est actif, on laisse le submit se faire normalement
    });
}


/* Fonctions pour l'Espace CV*/

    // Injection dynamique du nom du cv à supprimer dans popup suppression
function injectCvNameToDelete(CvName) {
    cvNameToDeleteInput = document.getElementById('post_delete_cv_name');
    cvNameToDeleteInput.setAttribute('value', CvName);
}

    // Injection du nom du fichier une fois uploadé dans le champ file + le texte --> ajout de cv
const fileInputAdd = document.getElementById('cv_upload_file');
if(fileInputAdd) {
    const fileNameDisplay = document.getElementById('cv_uploaded_name');
    const customNameInput = document.getElementById('cv_upload_name');
    const fileInputAddBloc = document.getElementById('input_file_bloc');

    fileInputAdd.addEventListener('change', function () {

        if (this.files.length === 0) return;

        const file = this.files[0];
        let fileName = file.name;

        // Affichage dans le <p>
        fileNameDisplay.textContent = fileName;

        // Ajout de la classe filled
        fileInputAddBloc.classList.add('completed');

        // Enleve l'extension pour pré-remplir le champ
        let nameWithoutExtension = fileName.replace(/\.[^/.]+$/, "");

        if (!customNameInput.value) {
            customNameInput.value = nameWithoutExtension;
        }
    });
}

    // Injection dynamique du nom du cv à remplacer par un autre dans popup d'update
function injectCvNameToUpdate(CvName) {
    cvNameToUpdateInput = document.getElementById('post_update_cv_name');
    cvNameToUpdateInput.setAttribute('value', CvName);
    cvNameWithoutStoreExt = CvName.replace('.store', '');
    document.getElementById('cv_to_update').textContent = cvNameWithoutStoreExt;
}

// Injection du nom du fichier une fois uploadé dans le champ file --> update de cv
const fileInputUpdate = document.getElementById('cv_update_file');
if(fileInputUpdate) {
    const fileInputUpdateBloc = document.getElementById('input_file_update_bloc');
    const fileNameDisplay = document.getElementById('cv_update_name');

    fileInputUpdate.addEventListener('change', function () {

        if (this.files.length === 0) return;

        const file = this.files[0];
        let fileName = file.name;

        // Affichage dans le <p>
        fileNameDisplay.textContent = fileName;

        // Ajout de la classe filled
        fileInputUpdateBloc.classList.add('completed');
    });
}

/* Mécanisme de download de CV */
let pendingDownloadFilename = ""; // stocke le vrai nom de fichier entre l'ouverture et le clic

function prepareDownloadModal(button) {
    // récupère le nom de base du fichier
    pendingDownloadFilename = button.dataset.filename;
    // injecte ce nom dans l'input type text
    document.getElementById('download_cv_name_input').value = button.dataset.displayname;
}

// Au clic sur le bouton de confirmation dans la popup => construit le lien
downloadCVConfirmButton = document.getElementById('download_cv_confirm_btn');
if(downloadCVConfirmButton) {
    downloadCVConfirmButton.addEventListener('click', () => {
        const chosenName = document.getElementById('download_cv_name_input').value;
        const url = "src/api/view_cv.php?file=" 
            + encodeURIComponent(pendingDownloadFilename) 
            + "&action=download&name=" 
            + encodeURIComponent(chosenName);
        
        document.getElementById('download_cv_confirm_btn').setAttribute('href', url);

        // + fermeture de la popup et affiche une banner
        downloadPopup = document.getElementById('download_cv_modal');
        closePopup(downloadPopup);
        showNotification("Téléchargement en cours", "success");
    });
}




