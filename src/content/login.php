
<?php if(!isset($error) || $error == NULL) : // si pas d'incident enregistré
    ?>

<!-- Première section -->
    <div id="login_page_hero">
        <div class="info_tag">
            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                <g>
                    <path d="M18,11a1,1,0,0,1-1,1,5,5,0,0,0-5,5,1,1,0,0,1-2,0,5,5,0,0,0-5-5,1,1,0,0,1,0-2,5,5,0,0,0,5-5,1,1,0,0,1,2,0,5,5,0,0,0,5,5A1,1,0,0,1,18,11Z"/>
                    <path d="M19,24a1,1,0,0,1-1,1,2,2,0,0,0-2,2,1,1,0,0,1-2,0,2,2,0,0,0-2-2,1,1,0,0,1,0-2,2,2,0,0,0,2-2,1,1,0,0,1,2,0,2,2,0,0,0,2,2A1,1,0,0,1,19,24Z"/>
                    <path d="M28,17a1,1,0,0,1-1,1,4,4,0,0,0-4,4,1,1,0,0,1-2,0,4,4,0,0,0-4-4,1,1,0,0,1,0-2,4,4,0,0,0,4-4,1,1,0,0,1,2,0,4,4,0,0,0,4,4A1,1,0,0,1,28,17Z"/>
                </g>
            </svg>
            <p>Actuellement en bêta gratuite</p>
        </div>

        <h1 class="bold">La recherche d'emploi,<br><span class="blue_highlight">enfin à votre rythme</span></h1>
        <h2>Agrégez plusieurs jobboards, cherchez sur plusieurs mots-clés, et ne retombez jamais deux fois sur la même offre.<br>
        Une plateforme pensée pour vous, pas pour les recruteurs.</h2>

        <div class="hero_cta">
            <a class="standard_cta" href="<?= BASE_URL ?>#login_second_part">En savoir plus ↓</a>
        </div>
        <p class="info_notes">Accès sur demande · Aucune CB requise</p>

    </div>

    <!-- Deuxième section -->

        <div id="login_second_part">

            <!-- Carousel des fonctionnalités -->

            <div class="carousel_box">
                <h2 class="login_title_section">Fonctionnalités</h2>
                <div class="carousel-track" id="carouselTrack">
                    <div class="carousel-inner" id="carouselInner">
                
                        <!-- CARD 1 -->
                        <div class="carousel-card" role="group" aria-label="Fonctionnalité 1 sur 5">
                            <div class="card-icon" aria-hidden="true">
                                <svg viewBox="0 0 36 36" version="1.1"  preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <polygon points="9.8 18.8 26.2 18.8 26.2 21.88 27.8 21.88 27.8 17.2 18.8 17.2 18.8 14 17.2 14 17.2 17.2 8.2 17.2 8.2 21.88 9.8 21.88 9.8 18.8"></polygon>
                                    <path d="M14,23H4a2,2,0,0,0-2,2v6a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V25A2,2,0,0,0,14,23ZM4,31V25H14v6Z"></path><path d="M32,23H22a2,2,0,0,0-2,2v6a2,2,0,0,0,2,2H32a2,2,0,0,0,2-2V25A2,2,0,0,0,32,23ZM22,31V25H32v6Z"></path><path d="M13,13H23a2,2,0,0,0,2-2V5a2,2,0,0,0-2-2H13a2,2,0,0,0-2,2v6A2,2,0,0,0,13,13Zm0-8H23v6H13Z"></path>
                                    <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                                </svg>
                            </div>
                            <span class="card-tag">Centralisation</span>
                            <p class="card-title">Plusieurs souces, un tableau de bord</p>
                            <p class="card-body">Agrégez les offres d'emploi de différentes sources en un seul tableau de bord correspondant à votre profil, sans jongler entre les onglets.</p>
                        </div>
                        
                            <!-- CARD 2 -->
                        <div class="carousel-card" role="group" aria-label="Fonctionnalité 2 sur 5">
                            <div class="card-icon" aria-hidden="true">
                                <svg viewBox="-0.04 0 31.793 31.793" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="translate(-609.503 -130.759)">
                                        <path d="M622.914,132.759a11.41,11.41,0,1,1-11.411,11.41,11.424,11.424,0,0,1,11.411-11.41m0-2a13.41,13.41,0,1,0,13.41,13.41,13.41,13.41,0,0,0-13.41-13.41Z"/>
                                        <path d="M640.208,162.552a1,1,0,0,1-.707-.292L631.64,154.4a1,1,0,1,1,1.414-1.414l7.861,7.86a1,1,0,0,1-.707,1.707Z"/>
                                    </g>
                                </svg>
                            </div>
                            <span class="card-tag">Multi-recherche</span>
                            <p class="card-title">Plusieurs mots-clés, zéro doublon</p>
                            <p class="card-body">Chef de projet, Product Owner, Chargé de marketing digital … Jobsfinder interroge toutes vos expressions simultanément et dédoublonne automatiquement. Vous ne voyez chaque offre qu'une seule fois, même si elle correspond à plusieurs de vos recherches.</p>
                        </div>
                        
                        <!-- CARD 3 -->
                        <div class="carousel-card" role="group" aria-label="Fonctionnalité 3 sur 5">
                            <div class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <rect width="48" height="48" fill="none"/>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M32,16H16V32H32ZM28,28H20V20h8Z"/>
                                            <path d="M44,28H40V20h4a2,2,0,0,0,0-4H40V10a2,2,0,0,0-2-2H32V4a2,2,0,0,0-4,0V8H20V4a2,2,0,0,0-4,0V8H10a2,2,0,0,0-2,2v6H4a2,2,0,0,0,0,4H8v8H4a2,2,0,0,0,0,4H8v6a2,2,0,0,0,2,2h6v4a2,2,0,0,0,4,0V40h8v4a2,2,0,0,0,4,0V40h6a2,2,0,0,0,2-2V32h4a2,2,0,0,0,0-4Zm-8,8H12V12H36Z"/>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            </div>
                            <span class="card-tag">Mémoire</span>
                            <p class="card-title">Ne retombez jamais 2 fois sur la même offre</p>
                            <p class="card-body">Consultée, pas intéressante, déjà traitée ? Un clic pour la masquer définitivement. À la prochaine session, votre liste repart de là où vous en étiez, pas du début.</p>
                        </div>

                        <!-- CARD 4 -->
                        <div class="carousel-card" role="group" aria-label="Fonctionnalité 4 sur 5">
                            <div class="card-icon" aria-hidden="true">
                                <svg viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	                                <path d="M17.020 8h-2.045c-0.245-3.194-2.806-5.745-6.004-5.977v-2.062h-1v2.065c-3.172 0.258-5.702 2.799-5.946 5.974h-2.045v1h2.045c0.244 3.175 2.774 5.716 5.945 5.974v2.026h1v-2.023c3.198-0.231 5.759-2.782 6.004-5.977h2.045v-1zM8.971 13.977v-1.977h-1v1.974c-2.621-0.252-4.708-2.35-4.946-4.974h1.955v-1h-1.955c0.238-2.624 2.325-4.722 4.946-4.974v1.935h1v-1.938c2.647 0.227 4.764 2.333 5.004 4.977h-1.955v1h1.955c-0.24 2.644-2.357 4.75-5.004 4.977z"/>
                                </svg>
                            </div>
                            <span class="card-tag">Ciblage</span>
                            <p class="card-title">Excluez ce qui ne vous correspond pas</p>
                            <p class="card-body">Secteur, type de contrat, compétence clé que vous n'avez pas : définissez vos critères excluants une fois pour toutes. Jobsfinder effectue un pré-tri avant même que vous ne voyiez les résultats.</p>
                        </div>
                        
                        <!-- CARD 5 -->
                        <div class="carousel-card" role="group" aria-label="Fonctionnalité 5 sur 5">
                            <div class="card-icon" aria-hidden="true">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 489 489" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path d="M417.4,71.6C371.2,25.4,309.8,0,244.5,0S117.8,25.4,71.6,71.6S0,179.2,0,244.5s25.4,126.7,71.6,172.9S179.2,489,244.5,489
                                                s126.7-25.4,172.9-71.6S489,309.8,489,244.5S463.6,117.8,417.4,71.6z M244.5,462C124.6,462,27,364.4,27,244.5S124.6,27,244.5,27
                                                S462,124.6,462,244.5S364.4,462,244.5,462z"/>
                                            <path d="M301.6,188.1l-84.1,84.2l-30.1-30.1c-5.3-5.3-13.8-5.3-19.1,0c-5.3,5.3-5.3,13.8,0,19.1L208,301c2.6,2.6,6.1,4,9.5,4
                                                s6.9-1.3,9.5-4l93.7-93.7c5.3-5.3,5.3-13.8,0-19.1C315.5,182.8,306.9,182.8,301.6,188.1z"/>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="card-tag">Suivi</span>
                            <p class="card-title">Vos outils au même endroit</p>
                            <p class="card-body">Regroupez vos différents CVs et votre interface de suivi des candidatures pour travailler d'où vous voulez.</p>
                        </div>
                
                    </div>
                    
                    <!-- Dots générés dynamiquement par JS -->
                <div class="carousel-dots" id="carouselDots" role="tablist" aria-label="Navigation du carousel"></div>

                </div>
    
                
            </div>

            <div class="login_description_right">
                <p>Jobs Finder a pour vocation à vous assister dans votre recherche d'emploi, en vous faisant gagner du temps, de la clarté et en vous remettant vous au centre du processus de réflexion, pas les annonceurs qui publient les offres.
            </div>

        </div>

    <!-- Troisième section -->
    <div id="login_third_part">
        <div class="login_third_content">
            <h2 class="login_title_section">Pourquoi JobsFinder ?</h2>
            <h3 class="login_name_section">Les jobboards ne sont pas pensés pour les candidats</h3>

            <div class="bloc_info_card">

                <div class="info_card">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 3h20v18H2V3zm2 2v14h16V9h-8V5H4z"/>
                    </svg>
                    <p class="bold">Trop de sites et d'onglets</p>
                    <p>C'est ce que le candidat doit gérer simultanément pour couvrir une zone de recherche sur plusieurs plateformes avec plusieurs mots clés.</p>
                </div>

                <div class="info_card">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 472.615 472.615" style="enable-background:new 0 0 472.615 472.615;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M355.232,0l-13.525,13.525l65.821,65.821h-279.17c-52.894,0-95.924,43.031-95.924,95.919v59.633h19.128v-59.633
                                    c0-42.343,34.452-76.79,76.796-76.79h279.17l-65.821,65.821l13.525,13.525l88.91-88.91L355.232,0z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path d="M421.053,237.714v59.632c0,42.344-34.452,76.795-76.796,76.795H65.087l65.821-65.825l-13.525-13.525l-88.909,88.914
                                    l88.909,88.91l13.525-13.525L65.087,393.27h279.17c52.895,0,95.924-43.031,95.924-95.924v-59.632H421.053z"/>
                            </g>
                        </g>
                    </svg>
                    <p class="bold">Offres vues et revues</p>
                    <p>Les jobboards ne mémorisent pas ce que vous avez traité, vous vous trouvez à reparcourir les mêmes résultats à chaque session.</p>
                </div>

                <div class="info_card">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.502 2.135A1 1 0 0 1 18 3v4a3.99 3.99 0 0 1 2.981 1.333A3.989 3.989 0 0 1 22 11c0 1.024-.386 1.96-1.019 2.667A3.993 3.993 0 0 1 18 15v4a1 1 0 0 1-1.496.868L10 16.152V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h5.734l6.77-3.868a1 1 0 0 1 .998.003zM10 14a1 1 0 0 1 .496.132L16 17.277V4.723l-5.504 3.145A1 1 0 0 1 10 8H4v6h6zm-4 2v4h2v-4H6zm12-3c.592 0 1.123-.256 1.491-.667.317-.354.509-.82.509-1.333s-.192-.979-.509-1.333A1.993 1.993 0 0 0 18 9v4z"/>
                    </svg>
                    <p class="bold">Sponsorisations qui n'ont pas d'intérêt</p>
                    <p>On vous mets en avant les offres des entreprises qui paient le plus, quitte à les revoir plusieurs fois des offres qui ne vous intéressent pas.</p>
                </div>

                <div class="info_card">
                    <svg viewBox="0 0 36 36" version="1.1"  preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path d="M18.09,20.59A2.41,2.41,0,0,0,17,25.14V28h2V25.23a2.41,2.41,0,0,0-.91-4.64Z"></path>
                        <path d="M26,15V10.72a8.2,8.2,0,0,0-8-8.36,8.2,8.2,0,0,0-8,8.36V15H7V32a2,2,0,0,0,2,2H27a2,2,0,0,0,2-2V15ZM12,10.72a6.2,6.2,0,0,1,6-6.36,6.2,6.2,0,0,1,6,6.36V15H12ZM9,32V17H27V32Z"></path>
                        <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                    </svg>
                    <p class="bold">Une recherche à la fois</p>
                    <p>Certaines plateformes misent tout sur l'algorithme, au point de ne pas vous compliquer la tâche quand il s'agit d'effectuer les recherches sur plusieurs mots-clés à la fois.</p>
                </div>

            </div>

        </div>
    </div>

    <!-- Quatrième section -->
    <div id="login_fourth_part">
        <div class="login_fourth_content">
            <h2 class="login_title_section">Roadmap</h2>
            <h3 class="login_name_section">Les fonctionnalités à venir</h3>

            <div class="bloc_info_card">

                <div class="info_card">
                    <div class="info_tag">A venir</div>
                    <p class="bold">Alerte email</p>
                    <p>Soyez alerté du nombre de nouvelles offres récupérées pour mieux vous organiser dans vos recherches.</p>
                </div>

                <div class="info_card">
                    <div class="info_tag">A venir</div>
                    <p class="bold">Analyse des mots-clés</p>
                    <p>Soyez informés des mots-clés qui marchent ou non pour savoir comment optimiser vos recherches.</p>
                </div>

                <div class="info_card">
                    <div class="info_tag pending">En réflexion</div>
                    <p class="bold">Suivi des candidatures</p>
                    <p>Suivez l'état de chaque candidature directement depuis l'outil.</p>
                </div>

            </div>

        </div>
    </div>

    <!-- Cinquième section - bandeau prise de contact -->
    <div id="login_fifth_part">
        <h2>Prêt à reprendre le contrôle ?</h2>
        <p>Contactez-moi pour la création de votre accès bêta · Aucune carte bancaire requise</p>
        <a href='https://www.linkedin.com/in/florian-neto-751008b9/'>Demander mon accès</a>
    </div>


<?php elseif(isset($error) && ($error === "no_account")) : // Error => compte n'existe pas en DB ?>
    <p class="error_message">Aucun compte associé à cet email.<br>
    Pour demander la création d'un compte de test, contactez le propriétaire du site.
    <div class="unknown_email_btns">
        <div class="standard_cta connect">
            <a href="<?= BASE_URL ?>?mode=login">Se connecter avec un autre compte</a>
        </div>
        <div>
            <a href='https://www.linkedin.com/in/florian-neto-751008b9/' class="standard_cta contact_cta">Prendre contact</a>
        </div>
    </div>
    </p>

<?php elseif(isset($error) && ($error === "forbidden_access")) : //Error => tentative d'accéder à un contenu réservé aux users connectés  ?>
    <p class="error_message">Vous n'avez pas accès à ce contenu.<br>
    Connectez-vous pour utiliser les fonctionnalités Jobs Finder.</p>
    <div class="standard_cta connect">
        <a href="<?= BASE_URL ?>?mode=login">Se connecter avec Google</a>
    </div>

<?php else : // Error => Cas d'erreur qui n'a pas de scénario propre ?>
    <p class="error_message">Une erreur s'est produite.<br>
    Merci de ré-essayer plus tard.</p>


<?php endif ?>
