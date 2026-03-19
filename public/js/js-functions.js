
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


/* FILTRE DES OFFRES - Select */

const select = document.getElementById("offers_displayed");
const jobOffers = document.querySelectorAll(".job_card");

function applyFilter(filter) {
    jobOffers.forEach((card) => {
        const status = card.dataset.status;
        let visible = false;

        if (filter === "all") {
            visible = true; // par défaut
        } else if (filter === "visible_only") {
            visible = (status === "visible"); // visible = true si statut = visible
        } else if (filter === "applied_only") {
            visible = status === "applied"; // visible = true si statut = applied
        }

        card.classList.toggle("filtered_out", !visible); // si visible = false, applique class hidden
    });
}

// Applique le filtre au chargement selon la valeur déjà sélectionnée
applyFilter(select.value);

// Puis à chaque changement
select.addEventListener("change", (e) => applyFilter(e.target.value));


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




