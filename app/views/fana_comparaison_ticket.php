<div class="fana-table-container">
    <h1>Comparaison Dépense vs Budget par Ticket</h1>
    <table class="fana-table">
        <tr>
            <th>Ticket</th>
            <th>Client</th>
            <th>Sujet</th>
            <th>Dépense réelle</th>
            <th>Budget</th>
            <th>Écart réel</th>
        </tr>
        <?php foreach ($stats as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['ticket_id']) ?></td>
                <td><?= htmlspecialchars($row['client']) ?></td>
                <td><?= htmlspecialchars($row['sujet']) ?></td>
                <td><?= number_format($row['montantPrevu'] * $row['dureeReel'], 2, ',', ' ') ?> €</td>
                <td><?= number_format($row['montantPrevu'] * $row['duree'], 2, ',', ' ') ?> €</td>
                <td class="<?= (($row['montantPrevu'] * $row['dureeReel']) - ($row['montantPrevu'] * $row['duree'])) < 0 ? 'ecart-negatif' : 'ecart-positif' ?>">
                    <?= number_format(abs(($row['montantPrevu'] * $row['dureeReel']) - ($row['montantPrevu'] * $row['duree'])), 2, ',', ' ') ?> €
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>