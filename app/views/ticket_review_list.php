<div class="fana-table-container">
    <h1>Tickets Fermés à Noter</h1>
    <table class="fana-table">
        <tr>
            <th>Ticket</th>
            <th>Client</th>
            <th>Sujet</th>
            <th>Action</th>
        </tr>
        <?php foreach ($tickets as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['id']) ?></td>
                <td><?= htmlspecialchars($t['fk_soc'] ?? '') ?></td>
                <td><?= htmlspecialchars($t['subject'] ?? '') ?></td>
                <td>
                    <a class="btn btn-primary" href="<?= Flight::get('flight.base_url') ?>/ticketreview-avis/<?= $t['id'] ?>">Donner un avis</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
