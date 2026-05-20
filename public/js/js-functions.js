
/* Mise à jour des status des offres par l'utilisateur*/

function updateCounts() {
    const total = document.querySelectorAll(".job_card").length;
    const visible = document.querySelectorAll(".job_card.visible").length;

    document.getElementById("visible_count").textContent = visible;
    document.getElementById("total_count").textContent = total;
};

function updateInDBOfferStatus(id, newStatus) {
    // récup les infos pour remplir le fetch
    const formdata = new FormData();
    formdata.append("id", id);
    formdata.append("status", newStatus);

    // fetch le fichier d'update et récupère la réponse
    fetch("./src/api/updateStatusTrigger.php", {
        method: "POST",
        body: formdata
    })
    .then(response => response.text())
    .then(text => {
        console.log("Réponse PHP :", text);

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
    })
    .catch(error => console.error("Erreur fetch :", error));
}


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
            <button type="button" class="second_cta" onclick="updateInDBOfferStatus('${card.dataset.id}', 'hidden')">Masquer l'offre</button>
            <button type="button" class="second_cta" onclick="updateInDBOfferStatus('${card.dataset.id}', 'applied')">J'ai postulé</button>
        `,
        hidden: `
            <button type="button" class="second_cta" onclick="updateInDBOfferStatus('${card.dataset.id}', 'visible')">Ne plus masquer</button>
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

/* Suppression d'une expression clé ou blacklist en DB */

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



