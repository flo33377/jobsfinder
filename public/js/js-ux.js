
/* AFFICHAGE DE LA BANNER DE NOTIF */

function showNotification(message, type) {
    const banner = document.getElementById('notification_banner');
    banner.textContent = message;
    banner.className = ''; // reset les classes
    banner.classList.add('visible', type); // type = "success" ou "error"

    // Disparition après 3 secondes
    setTimeout(() => {
        banner.classList.remove('visible');
    }, 5000);
}


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

        // si un élément à la class autofocus_target, le focus
        const autofocusEl = popup.querySelector('.autofocus_target');
        if (autofocusEl) autofocusEl.focus();

        // Ouvre l'overlay du menu s'il existe
        /* const menuOverlay = document.getElementById('menu_overlay');
        if (menuOverlay) menuOverlay.classList.add('open'); */

        // Fermeture via tous les éléments .close_popup dans cette popup
        popup.querySelectorAll('.close_popup').forEach(btn => {
            btn.addEventListener('click', () => closePopup(popup));
        });

        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
            // dialog = si clic en dehors de la popup, considéré comme clic sur la popup
            // mais pas sur le content, donc target === popup => clic en dehors
                closePopup(popup);
            }
        });
        // ferme la popup en cas de clic en dehors
});

function closePopup(popup) {
    popup.close();
    /* const menuOverlay = document.getElementById('menu_overlay');
    if (menuOverlay) menuOverlay.classList.remove('open'); */
}


/* Affichage du menu */

let menuButton = document.getElementById("menu_button");
let burgerMenu = document.getElementById("burger_menu");
let header = document.getElementById("header");
let overlay = document.getElementById("menu_overlay");

if(menuButton) {
    menuButton.addEventListener("click", () => {
        let rect = header.getBoundingClientRect();
        const isOpening = !burgerMenu.classList.contains('open');
        
        if (isOpening) {
            // Figer le header à sa position viewport actuelle
            header.style.position = "fixed";
            header.style.top = rect.top + "px";
            header.style.left = rect.left + "px";
            header.style.width = rect.width + "px";
            // rajoute un padding dans le content pour éviter un décalage quand le header disparait
            const headerMarginBottom = parseInt(getComputedStyle(header).marginBottom);
            // => récup la valeur du margin bottom appliqué sur le header
            document.body.style.paddingTop = (rect.height + headerMarginBottom) + "px";
            // => Ajoute d'un padding de la taille du header et de son margin bottom
            
            // Menu et overlay positionnés sous le header
            burgerMenu.style.position = "fixed";
            burgerMenu.style.top = rect.bottom + "px";
            burgerMenu.style.right = (document.documentElement.clientWidth - rect.right) + "px";
            overlay.style.top = rect.bottom + "px";
        }
        
        menuButton.classList.toggle("open");
        burgerMenu.classList.toggle("open");
        overlay.classList.toggle("open");
    });

    // ferme le menu en cas de clic en dehors
    document.addEventListener('click', (e) => {
        // si le menu est fermé, rien à faire
        if (!burgerMenu.classList.contains('open')) return;
    
        // si le clic est ni dans le menu ni sur le bouton -> fermer
        const clickInsideMenu   = burgerMenu.contains(e.target);
        const clickOnMenuButton = menuButton.contains(e.target);
    
        if (!clickInsideMenu && !clickOnMenuButton) {
        closeMenu();
        }
    });
    
    // fermer avec Échap (accessibilité)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });

    function closeMenu() {
        menuButton.classList.remove('open');
        burgerMenu.classList.remove('open');
        overlay.classList.remove('open');
        
        // Relâcher le header
        header.style.position = "";
        header.style.top = "";
        header.style.left = "";
        header.style.width = "";
        // retirer le padding qui masquait le décalage
        document.body.style.paddingTop = "";
    }
}


/* REGEX - ECHAPPEMENT DES CARACTERES SPECIAUX */

function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    // $& => le caractère matché, on le réinjecte précédé d'un \ pour l'échapper
}


/* BOUTON DE RETOUR VERS LE HAUT */

    /* Fonctionnement */

let btnUp = document.getElementById('btn_up')
if(btnUp) {
    document.getElementById('btn_up').addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

        /* Affiché uniquement quand déjà scrollé */

    window.addEventListener('scroll', () => {
        const btn = document.getElementById('btn_up');
        if (window.scrollY > 50) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    });
}

    /* CAROUSEL EN HP */

// récup les éléments
const track = document.getElementById('carouselTrack');

if(track) {
    const inner = document.getElementById('carouselInner');
    const dotsEl = document.getElementById('carouselDots');
    const cards = inner.querySelectorAll('.carousel-card');

    // set les variables d'état par défaut
    let current = 0; // => index de la card affichée
    let touchStartX = 0; // => position du scroll mémorisée au début d'un swipe
    
    // --- Aller à la slide N ---
    function goTo(index) {
        // le modulo permet de gérer le système de cycle (si j'arrive au bout (dernière card), je reviens dedans)
        // Si je sors du nombre de card, le modulo recalcule ma position voulue au sein d'un cycle => 3e card et je vais à droite => me ramène en position 0
        // si je suis dans le nbr de card => 1e card je vais à droite => le modulo s'annule et renvoie le même nbr, soit la cadre que j'essaie de voir
        current = (index + cards.length) % cards.length;
    
        // On déplace le rail : décale l'ensemble de card pour n'afficher que celle qui intéresse
        // possible car 100% = largeur d'une card
        inner.style.transform = `translateX(-${current * 100}%)`;
    
        // Mise à jour des dots
        dotsEl.querySelectorAll('.dot').forEach((dot, i) => {
        const isActive = i === current;
        dot.classList.toggle('active', isActive);
        dot.setAttribute('aria-selected', isActive);
        });
    }
    
    // --- Génération des dots ---
    cards.forEach((_, i) => {
        // _ => l'élément (la card) // i => son index
        const dot = document.createElement('button');
        // class dot + active si son index est 0
        dot.className = 'dot' + (i === 0 ? ' active' : '');
        dot.setAttribute('role', 'tab');
        dot.setAttribute('aria-label', `Aller à la carte ${i + 1}`);
        dot.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
        // au clic, déclenche event pour aller sur la card correspondate
        dot.addEventListener('click', () => goTo(i));
        dotsEl.appendChild(dot);
    });
    
    // --- Swipe tactile ---
    // touchstart : on mémorise la position X du doigt
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        // e.touches[0] => tableau qui contient le nbr de doigts sur l'élément, 0 => le premier doigt
        // clientX => coordonnées X du doigt par rapport au bord gauche de la fenêtre visible sur l'écran
    }, { passive: true });
    // améliore les performances du navig car dit que pas besoin d'attendre retour du JS pour effectuer manip natives
    // utile sur des mécaniques de scroll
    
    // touchend : on compare avec la position finale
    track.addEventListener('touchend', (e) => {
        const dx = e.changedTouches[0].clientX - touchStartX;
        // e.changedTouches => tableau avec les doigts dont l'état vient de changer
        // ici doigt levé = état modifié, récup sa dernière position
        if (Math.abs(dx) > 40) {
            // Math.abs renvoie valeur absolue (prend pas en compte positif ou négatif)
            // permet calculer seuil de 40px que ce soit vers la gauche (négatif) ou la droite (positif)
            // Ici => Seuil de 40px pour éviter les swipes accidentels
        goTo(current + (dx < 0 ? 1 : -1));
        }
    }, { passive: true });
    
    // --- Navigation clavier (accessibilité) ---

    let carouselVisible = false; // par défaut, dit que le carousel n'est pas dans la fenêtre

    const observer = new IntersectionObserver(
      (entries) => { // indique si l'élément "entries" est dans le champ du navigateur
        // entries[0] correspond à l'élément observé
        carouselVisible = entries[0].isIntersecting;
      },
      { threshold: 0.5 } // déclenché quand 50% du carousel est visible
    );
    
    observer.observe(track); // applique le checker sur le carousel
    
    document.addEventListener('keydown', (e) => {
      if (!carouselVisible) return; // on sort immédiatement si le carousel n'est pas visible
      if (e.key === 'ArrowRight') goTo(current + 1);
      if (e.key === 'ArrowLeft')  goTo(current - 1);
    });
}


/* AFFICHAGE DES FILTRES SUPP VIA DETAILS */

const details = document.getElementById('details_options');
const content = document.getElementById('details_options_content');
if(details && content) {
    const summary = details.querySelector('summary');

    summary.addEventListener('click', (e) => {
        e.preventDefault(); // empêche le comportement natif
        if (details.open) {
            content.style.maxHeight = '0';
            content.style.opacity = '0';
            setTimeout(() => details.removeAttribute('open'), 300);
        } else {
            details.setAttribute('open', '');
            content.style.maxHeight = content.scrollHeight + 'px';
            setTimeout(() => content.style.opacity = '1', 300);
        }
    });
}


/* FILTRE DES OFFRES - Select */

const select = document.getElementById("offers_displayed");
if(select) {
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

    if(sortSelect) {
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
    }
}


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


