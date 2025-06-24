
<!-- filepath: app/views/ticket_list.php -->
<h1>Liste des tickets</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Instruction</th>
            <th>Date</th>
            <th>Priorité</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= htmlspecialchars($ticket['idTicket']) ?></td>
                <td><?= htmlspecialchars($ticket['nomClient']) ?></td>
                <td><?= htmlspecialchars($ticket['instruction']) ?></td>
                <td><?= htmlspecialchars($ticket['date']) ?></td>
                <td><?= htmlspecialchars($ticket['priorite']) ?></td>
                <td><?= htmlspecialchars($ticket['statut']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>