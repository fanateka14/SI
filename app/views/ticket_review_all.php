<div class="fana-table-container">
    <h1>Liste des avis sur les tickets</h1>
    <table class="fana-table">
        <tr>
            <th>Ticket</th>
            <th>Sujet</th>
            <th>Note</th>
            <th>Commentaire</th>
            <th>Date</th>
        </tr>
        <?php foreach ($reviews as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id_ticket']) ?></td>
                <td><?= htmlspecialchars($r['subject'] ?? '') ?></td>
                <td style="color: #f1c40f; font-size: 1.2em;">
                    <?php for ($i=0; $i<$r['nb_etoile']; $i++) echo '★'; ?><?php for ($i=$r['nb_etoile']; $i<5; $i++) echo '☆'; ?>
                </td>
                <td><?= htmlspecialchars($r['commentaire']) ?></td>
                <td><?= $r['date_avis'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
