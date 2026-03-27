
/* FONCTIONNEMENT DES POP UP */

document.addEventListener('click', (e) => {
    // écoute chaque clic sur le DOM
    const trigger = e.target.closest('[data-popup-id]');
    // quand clic, remonte les éléments du DOM au niveau du clic pour voir si
    // lui ou un parent à l'attribut data-popup-id
    if (!trigger) return;
    // si non, ne va pas plus loin

    const popupId = trigger.dataset.popupId;
    const popup = document.getElementById(popupId);
    // récup la data et trouve la popup avec

    if (!popup) {
        console.warn(`Popup introuvable : ${popupId}`);
        return;
        // si popup n'existe pas, avertissement console et stop
    }

    // si clic lié à popup et popup existe :
        popup.showModal();
        popup.style.top = `${(window.innerHeight - popup.offsetHeight) / 2}px`;
        popup.style.left = `${(window.innerWidth - popup.offsetWidth) / 2}px`;
        // ouvre la popup et la place

        document.getElementById('close_popup')?.addEventListener('click', () => {
            popup.close();
        });
        // active le bouton fermeture de la popup

        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
            // dialog = si clic en dehors de la popup, considéré comme clic sur la popup
            // mais pas sur le content, donc target === popup => clic en dehors
                popup.close();
            }
        });
        // ferme la popup en cas de clic en dehors
});


/* REGEX - ECHAPPEMENT DES CARACTERES SPECIAUX */

function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    // $& => le caractère matché, on le réinjecte précédé d'un \ pour l'échapper
}


/* AFFICHAGE DES FILTRES SUPP VIA DETAILS */

const details = document.getElementById('details_options');
const content = document.getElementById('details_options_content');
const summary = details.querySelector('summary');

summary.addEventListener('click', (e) => {
    e.preventDefault(); // empêche le comportement natif
    if (details.open) {
        content.style.maxHeight = '0';
        setTimeout(() => details.removeAttribute('open'), 300);
    } else {
        details.setAttribute('open', '');
        content.style.maxHeight = content.scrollHeight + 'px';
    }
});


/* FILTRE DES OFFRES - Select */

const select = document.getElementById("offers_displayed");
const jobOffers = document.querySelectorAll(".job_card");
const searchInput = document.getElementById('search_content');
const visibleCount = document.getElementById('visible_count');

function highlightTerm(card, term) { // met en surbrillance les termes du search
    // cible tous les éléments texte de la card
    const elements = card.querySelectorAll('h3, h4, h5, p');

    elements.forEach(el => {
        // sauvegarde le contenu original la première fois pour retirer le highlight
        if (!el.dataset.original) {
            el.dataset.original = el.innerHTML;
        }

        // restaure toujours d'abord
        el.innerHTML = el.dataset.original;

        // puis surbrille si un terme est présent
        if (term.length > 1) { // évite de surbriller à chaque lettre dès le 1er caractère
            const safeTerm = escapeRegex(term);
            const regex = new RegExp(safeTerm, 'gi');
            // g => global, toutes les occurences, pas que la première
            // i => insensitive, insensible à la casse
            el.innerHTML = el.dataset.original.replace(
                regex,
                match => `<span class="search_highlight">${match}</span>`
            );
        }
    });
};

function applyFilters() {

    const filter = select.value;
    const term = searchInput.value.toLowerCase().trim();
    let offersDisplayedCntr = 0;

    jobOffers.forEach((card) => {
        const status = card.dataset.status;
        const text = card.textContent.toLowerCase(); // récup l'ensemble des textes de la card

        const matchFilter = filter === "all" || status === filter.replace("_only", "");
        // si vaut all = true, sinon true si le filtre match le statut de la card
        const matchSearch = term === "" || text.includes(term);
        // vaut true si un des textes de la card comporte le terme recherché

        const isVisible = matchFilter && matchSearch; // vaut true si les 2 sont à true
        card.classList.toggle("filtered_out", !isVisible); // si ne match pas les 2 filtres, disparait
        if(isVisible) offersDisplayedCntr ++;

        highlightTerm(card, term); // déclenche le check de surbrillance
    });

    visibleCount.textContent = offersDisplayedCntr;
}

// Applique le filtre au chargement selon la valeur déjà sélectionnée
applyFilters(select.value);

// Puis à chaque changement
select.addEventListener("change", applyFilters);
searchInput.addEventListener("input", applyFilters);


/* SYSTEME DE TRI PAR DATE */

const sortSelect = document.getElementById("sort_offers");

function applySort() {
    const order = sortSelect.value;
    const cards = Array.from(jobOffers);

    cards.sort((a, b) => {
        const dateA = new Date(a.dataset.date);
        const dateB = new Date(b.dataset.date);
        return order === "newest" ? dateB - dateA : dateA - dateB;
    });

    const jobBoard = document.getElementById('job_board');
    cards.forEach(card => jobBoard.appendChild(card));
}

sortSelect.addEventListener("change", applySort);


/* SCROLL VERS OFFRE SELECTIONNEE */

const radios = document.querySelectorAll('.job_card input[type="radio"]');

radios.forEach((radio) => {
    radio.addEventListener("change", (e) => {
        if (e.target.checked) {
            const card = e.target.closest(".job_card"); // va chercher la card correspondante
            setTimeout(() => {
                const top = card.getBoundingClientRect().top + window.scrollY - 20;
                // -20 = marge au dessus de la card
                window.scrollTo({ top: top, behavior: "smooth" });
            }, 50); // laisse le temps au CSS de réafficher le texte
        }
    });
});


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
                    if (nextCard) {
                    const radio = nextCard.querySelector('input[type="radio"]');
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
    const newTag = card.querySelector('.new_offer_tag'); // adapte le sélecteur à ta classe
    if (newTag) newTag.remove();

    // 3. Met à jour le label de statut
    const statusLabels = { visible: "Visible", hidden: "Masquée", applied: "Postulée" };
    card.querySelector(".job_status").textContent = statusLabels[newStatus] || "Visible";

    // 4. Met à jour les boutons
    const actionBtn = card.querySelector(".action_btn");
    const buttonsHTML = {
        visible: `
            <button type="button" onclick="updateInDBOfferStatus('${card.dataset.id}', 'hidden')">Masquer l'offre</button>
            <button type="button" onclick="updateInDBOfferStatus('${card.dataset.id}', 'applied')">J'ai postulé</button>
        `,
        hidden: `
            <button type="button" onclick="updateInDBOfferStatus('${card.dataset.id}', 'visible')">Ne plus masquer</button>
        `,
        applied: ``
    };

    // Reconstruit les boutons en gardant le lien "Voir l'annonce"
    const link = actionBtn.querySelector("a").outerHTML;
    actionBtn.innerHTML = link + (buttonsHTML[newStatus] || "");

    updateCounts();
};


/* Imports et cleanage manuels des offres via le bouton Refresh */

const refreshBtn = document.getElementById('refresh_btn');

refreshBtn.addEventListener('click', async () => {

    // désactive le bouton et affiche l'animation
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<span class="spinner"></span> Recherche en cours...';

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
            }, 60000);
        }

    } catch (error) {
        refreshBtn.textContent = "Erreur lors de l'import";
        console.error(error);

        // réactive le bouton après 60 secondes
        setTimeout(() => {
        refreshBtn.textContent = "Rafraîchir les offres";
        refreshBtn.disabled = false;
    }, 60000);
    }

});


