
<h2 class="page_title">Utilisateurs</h2>

<?php if(count($users_list) > 0 ) : ?>

    <?php 
    $activeUsers = array_filter($users_list, function($user) {
        return $user['import_status'] !== 'paused';
    });
    ?>
    <p><?= count($activeUsers) ?> utilisateurs actifs sur <?= count($users_list) ?> au total.</p>

    <!-- Champ recherche -->
    <div class="search-wrapper">
        <input type="text" id="search_users" placeholder="Rechercher un utilisateur..." autocomplete="off">
        <div id="search_dropdown" class="search-dropdown" hidden></div>
    </div>

    <!-- Bouton ajout user -->
    <button type="button" data-popup-id="add_user_modal" class="main_cta create_user_btn">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM6 8a6 6 0 1 1 12 0A6 6 0 0 1 6 8zm2 10a3 3 0 0 0-3 3 1 1 0 1 1-2 0 5 5 0 0 1 5-5h8a5 5 0 0 1 5 5 1 1 0 1 1-2 0 3 3 0 0 0-3-3H8z"/>
        </svg>
        Créer un nouvel utilisateur
    </button>

    <!-- Desktop : tableau -->
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Dernière connexion</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users_list as $user) : ?>
                <tr class="admin-table-row" data-url="<?= BASE_URL ?>?mode=users_list&user_id=<?= $user['user_id'] ?>" onclick="window.location=this.dataset.url">
                    <td class="user_identity_bloc">
                        <p class="user-name"><?= htmlspecialchars($user['name'] ?? 'Sans nom') ?></p>
                        <p class="user-email"><?= htmlspecialchars($user['user_email']) ?></p>
                    </td>
                    <td data-label="Rôle">
                        <span class="role-badge <?= $user['role'] === 'admin' ? 'role-admin' : 'role-user' ?>">
                            <?php if($user['role'] == "admin") echo "Admin"; else echo "Utilisateur"; ?>
                        </span>
                    </td>
                    <td class="user-date" data-label="Dernière connexion">
                        <?= $user['last_login_at'] 
                            ? date('d/m/Y à H:i', strtotime($user['last_login_at'])) 
                            : 'Jamais connecté' ?>
                    </td>
                    <td data-label="Statut">
                        <span class="status-badge status-<?= $user['import_status'] ?? 'unknown' ?>">
                            <?= $user['import_status'] ?? '—' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p>Aucun utilisateur en base.</p>
<?php endif ?>


<!-- Modale de création de user -->
<dialog id="add_user_modal">
    <div class="modal_content">
      <button class="close_popup close_desktop_only">X</button>

      <h2>Créer un nouvel utilisateur</h2>
      <form action method="POST">
        <input type="hidden" name="post_add_user">

        <label for="exp_add_name">Email</label>
        <input type="email" name="add_user_email" id="add_user_email" class="autofocus_target" placeholder="ex : xxx@gmail.com" required>

        
        <label for="exp_add_name">Nom</label>
        <input type="text" name="add_user_name" id="add_user_name" placeholder="ex : Mr./Mme X">

        <div class="action_btns">
        <button type="button" class="close_popup">Annuler</button>
            <input type="submit" value="Créer">
        </div>
      </form>

    </div>
</dialog>

