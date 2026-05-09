<h1>Journal de logs</h1>

<?php
$logFile = __DIR__ . '/../logs/log_diary.json';

if (!file_exists($logFile)) {
    // si fichier introuvable
    echo "<p>Aucun log disponible.</p>";
    return;
}

$logs = json_decode(file_get_contents($logFile), true);

if (empty($logs)) {
    // si fichier vide
    echo "<p>Aucun import enregistré.</p>";
    return;
}

// Trie les données du plus récent au plus ancien
usort($logs, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
?>

<div id="log_diary">
    <?php foreach ($logs as $import) : ?>
        <div class="log_entry">
            <h3>/* Import du <?= date('d/m/Y à H:i', strtotime($import['date'])) ?> */</h3>

            <?php foreach ($import['sources'] as $source => $keywords) : ?>
                <div class="log_source">
                    <h4><?= ucfirst(str_replace('_', ' ', $source)) ?></h4>
                    <ul>
                        <?php foreach ($keywords as $entry) : ?>
                            <li>
                                <?= htmlspecialchars($entry['keyword']) ?> — 
                                <?= $entry['results'] ?> résultat(s) 
                                <span class="log_code <?= $entry['code'] === 200 ? 'ok' : 'error' ?>">
                                    (Code <?= $entry['code'] ?>)
                                </span>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>
</div>
