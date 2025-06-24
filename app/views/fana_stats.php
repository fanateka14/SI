<h1>Statistiques Coût Généré par Client</h1>
<form method="get" action="">
    <label for="start">Date début :</label>
    <input type="date" name="start" id="start" value="<?= htmlspecialchars($_GET['start'] ?? '') ?>">
    <label for="end">Date fin :</label>
    <input type="date" name="end" id="end" value="<?= htmlspecialchars($_GET['end'] ?? '') ?>">
    <button type="submit">Filtrer</button>
</form>
<br>
<table border="1" cellpadding="5">
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
    <?php foreach ($stats as $client => $data): ?>
        <tr>
            <td><?= htmlspecialchars($client) ?></td>
            <td><?= $data['nb_tickets'] ?></td>
            <td><?= number_format($data['cout_genere'], 2, ',', ' ') ?> €</td>
            <td><?= number_format($data['cout_genere_reel'], 2, ',', ' ') ?> €</td>
            <td><?= number_format($data['budget'], 2, ',', ' ') ?> €</td>
            <td><?= number_format(abs($data['cout_genere'] - $data['budget']), 2, ',', ' ') ?> €</td>
            <td><?= number_format(abs($data['cout_genere_reel'] - $data['budget']), 2, ',', ' ') ?> €</td>
            <td>
                <a href="#" onclick="alert('Tickets: <?= htmlspecialchars(json_encode($data['tickets'])) ?>')">Voir tickets</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>