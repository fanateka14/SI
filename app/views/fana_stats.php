<div class="fana-table-container">
    <h1>Statistiques Coût Généré par Client</h1>
    <form method="get" action="" class="mb-4">
        <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
            <label for="start">Date début :</label>
            <input type="date" name="start" id="start" value="<?= htmlspecialchars($_GET['start'] ?? '') ?>">
            <label for="end">Date fin :</label>
            <input type="date" name="end" id="end" value="<?= htmlspecialchars($_GET['end'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
    <table class="fana-table">
        <tr>
            <th>Client</th>
            <th>Nombre de tickets</th>
            <th>Coût généré prévisionnel</th>
            <th>Coût généré réel</th>
            <th>Budget</th>
            <th>Écart prévisionnel</th>
            <th>Écart réel</th>
            <th>Voir tickets</th>
        </tr>
        <?php foreach ($stats as $data): ?>
            
            <tr>
                <td><?= htmlspecialchars($data['client_name']) ?></td>
                <td><?= $data['nb_tickets'] ?></td>
                <td><?= number_format($data['cout_genere'], 2, ',', ' ') ?> €</td>
                <td><?= number_format($data['cout_genere_reel'], 2, ',', ' ') ?> €</td>
                <td><?= number_format($data['budget'], 2, ',', ' ') ?> €</td>
                <td class="<?= ($data['cout_genere'] - $data['budget']) < 0 ? 'ecart-negatif' : 'ecart-positif' ?>">
                    <?= number_format(abs($data['cout_genere'] - $data['budget']), 2, ',', ' ') ?> €
                </td>
                <td class="<?= ($data['cout_genere_reel'] - $data['budget']) < 0 ? 'ecart-negatif' : 'ecart-positif' ?>">
                    <?= number_format(abs($data['cout_genere_reel'] - $data['budget']), 2, ',', ' ') ?> €
                </td>
                <td>
                    <a href="#" onclick="alert('Tickets: <?= htmlspecialchars(json_encode($data['tickets'])) ?>')">Voir tickets</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>