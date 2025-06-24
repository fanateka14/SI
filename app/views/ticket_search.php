<style>
    .navigationTable {
    display: flex;
    flex-direction: column;
    background: #f8f9fa;
    padding: 18px 24px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin: 20px 0;
}

.navigationTable h1 {
    font-size: 22px;
    color: #333;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
}

.navigationTable h1 i {
    margin-right: 10px;
    color: #6c63ff;
}

.navigationTable form {
    display: flex;
    gap: 14px;
    align-items: center;
}

.navigationTable select,
.navigationTable button {
    padding: 8px 14px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.navigationTable button {
    background: #6c63ff;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.navigationTable button:hover {
    background: #574bd7;
}

.ticketList {
    margin: 30px auto;
    width: 95%;
}

.ticketList table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}

.ticketList th, .ticketList td {
    padding: 12px 10px;
    border: 1px solid #e0e0e0;
    text-align: left;
    font-size: 15px;
}

.ticketList th {
    background: #6c63ff;
    color: #fff;
    font-weight: 600;
}

.ticketList tr:nth-child(even) {
    background: #f6f6fa;
}
</style>
<div class="navigationTable">
    <h1><i class="fas fa-search"></i> Recherche Tickets</h1>
    <form method="get" action="tri" class="controls" style="display:flex;gap:10px;">
        <select name="idClient">
            <option value="">Tous les clients</option>
            <?php foreach ($clients as $client): ?>
                <option value="<?= $client['idClient'] ?>" <?= ($selectedClient == $client['idClient']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($client['nomClient']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="statut">
            <option value="">Tous statuts</option>
            <option value="ouvert" <?= ($selectedStatut == 'ouvert') ? 'selected' : '' ?>>Ouvert</option>
            <option value="ferme" <?= ($selectedStatut == 'ferme') ? 'selected' : '' ?>>Ferme</option>
        </select>
        <select name="priorite">
            <option value="">Toutes priorites</option>
            <option value="basse" <?= ($selectedPriorite == 'basse') ? 'selected' : '' ?>>Basse</option>
            <option value="moyenne" <?= ($selectedPriorite == 'moyenne') ? 'selected' : '' ?>>Moyenne</option>
            <option value="haute" <?= ($selectedPriorite == 'haute') ? 'selected' : '' ?>>Haute</option>
        </select>
        <button type="submit">Rechercher</button>
    </form>
</div>
<section class="ticketList">
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Instruction</th>
                <th>Date</th>
                <th>Priorite</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['nomClient']) ?></td>
                    <td><?= htmlspecialchars($ticket['instruction']) ?></td>
                    <td><?= htmlspecialchars($ticket['date']) ?></td>
                    <td><?= htmlspecialchars($ticket['priorite']) ?></td>
                    <td><?= htmlspecialchars($ticket['statut']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>