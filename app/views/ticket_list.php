<?php

function getStatutLabel($code) {
    switch ($code) {
        case 0: return 'Brouillon';
        case 1: return 'Ouvert';
        case 2: return 'En cours de traitement';
        case 3: return 'Fermé (ou Résolu)';
        case -1: return 'Annulé';
        default: return 'Inconnu';
    }
}
function getClientName($fk_soc, $tiers) {
    foreach ($tiers as $tier) {
        if ($tier['id'] == $fk_soc) {
            return $tier['name'] ?? $tier['nom'] ?? $tier['lastname'] ?? $tier['login'] ?? $fk_soc;
        }
    }
    return $fk_soc;
}
?>
<?php
// Filtrage avancé côté PHP
$ticketsFiltered = [];
foreach ($tickets as $ticket) {
    if (
        (empty($_GET['client']) || $ticket['fk_soc'] == $_GET['client']) &&
        (empty($_GET['statut']) || $ticket['fk_statut'] == $_GET['statut']) &&
        (empty($_GET['priorite']) || $ticket['severity_code'] == $_GET['priorite']) &&
        (empty($_GET['agent']) || $ticket['fk_user_assign'] == $_GET['agent'])
    ) {
        $ticketsFiltered[] = $ticket;
    }
}
?>
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

.edit-btn, .save-btn, .cancel-btn {
    padding: 6px 12px;
    border-radius: 5px;
    border: none;
    margin: 2px;
    cursor: pointer;
    font-size: 15px;
    transition: background 0.2s;
}
.edit-btn {
    background: #f1c40f;
    color: #fff;
}
.edit-btn:hover {
    background: #d4ac0d;
}
.save-btn {
    background: #27ae60;
    color: #fff;
}
.save-btn:hover {
    background: #219150;
}
.cancel-btn {
    background: #e74c3c;
    color: #fff;
}
.cancel-btn:hover {
    background: #c0392b;
}
</style>

<h1>Liste des tickets</h1>

<form method="get" class="navigationTable" style="margin-bottom:20px;">
    <div>
        <label>Client :</label>
        <select name="client">
            <option value="">Tous</option>
            <?php foreach ($tiers as $tier): ?>
                <option value="<?= $tier['id'] ?>" <?= isset($_GET['client']) && $_GET['client'] == $tier['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tier['name'] ?? $tier['nom'] ?? $tier['lastname'] ?? $tier['login'] ?? $tier['id']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Statut :</label>
        <select name="statut">
            <option value="">Tous</option>
            <option value="0" <?= (isset($_GET['statut']) && $_GET['statut'] === "0") ? 'selected' : '' ?>>Brouillon</option>
            <option value="1" <?= (isset($_GET['statut']) && $_GET['statut'] === "1") ? 'selected' : '' ?>>Ouvert</option>
            <option value="2" <?= (isset($_GET['statut']) && $_GET['statut'] === "2") ? 'selected' : '' ?>>En cours de traitement</option>
            <option value="3" <?= (isset($_GET['statut']) && $_GET['statut'] === "3") ? 'selected' : '' ?>>Fermé (ou Résolu)</option>
            <option value="-1" <?= (isset($_GET['statut']) && $_GET['statut'] === "-1") ? 'selected' : '' ?>>Annulé</option>
        </select>

        <label>Priorité :</label>
        <select name="priorite">
            <option value="">Toutes</option>
            <option value="1" <?= (isset($_GET['priorite']) && $_GET['priorite'] === "1") ? 'selected' : '' ?>>Basse</option>
            <option value="2" <?= (isset($_GET['priorite']) && $_GET['priorite'] === "2") ? 'selected' : '' ?>>Moyenne</option>
            <option value="3" <?= (isset($_GET['priorite']) && $_GET['priorite'] === "3") ? 'selected' : '' ?>>Haute</option>
        </select>

        <label>Agent :</label>
        <select name="agent">
            <option value="">Tous</option>
            <?php foreach ($agents as $agent): ?>
                <option value="<?= $agent['id'] ?>" <?= isset($_GET['agent']) && $_GET['agent'] == $agent['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($agent['lastname'] ?? $agent['login'] ?? 'Agent') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrer</button>
    </div>
</form>




<section class="ticketList">
    <table>
        <thead>
            <tr>
            <th>Client</th>
            <th>Sujet</th>
            <th>Instruction</th>
            <th>Date</th>
            <th>Statut</th>

            <th>Priorité</th>
            <th>Affecter a un agent</th>
            <th>Montant Prévu</th>
            <th>Durée Estimée (h)</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
<?php foreach ($ticketsFiltered as $ticket): ?>
    <!-- Affichage normal -->
    <tr id="display-row-<?= $ticket['id'] ?>">
        <td><?= getClientName($ticket['fk_soc'], $tiers)?></td>
        <td><?= $ticket['subject'] ?></td>
        <td><?= $ticket['message'] ?></td>
        <td><?= date('Y-m-d H:i:s', $ticket['date_creation']) ?></td>
        <td><?= getStatutLabel($ticket['fk_statut']) ?></td>
        <td>
            <?php
                $prioriteLabel = 'Basse';
                if ($ticket['severity_code'] == 2) $prioriteLabel = 'Moyenne';
                if ($ticket['severity_code'] == 3) $prioriteLabel = 'Haute';
                echo $prioriteLabel;
            ?>
        </td>
        <td>
            <?php
                $agentNom = '';
                foreach ($agents as $agent) {
                    if ($ticket['fk_user_assign'] == $agent['id']) {
                        $agentNom = $agent['lastname'] ?? $agent['login'] ?? 'Agent';
                        break;
                    }
                }
                echo $agentNom ?: 'Non assigné';
            ?>
        </td>
        <td>
            <?= isset($ticket['montantPrevu']) && $ticket['montantPrevu'] !== null ? htmlspecialchars($ticket['montantPrevu']) : '' ?>
        </td>
        <td>
            <?= isset($ticket['duree']) && $ticket['duree'] !== null ? htmlspecialchars($ticket['duree']) : '' ?>
        </td>
        <td>
            <button class="edit-btn" type="button" onclick="showEditForm(<?= $ticket['id'] ?>)">
                <i class="fas fa-pen"></i>
            </button>
        </td>
    </tr>
    <!-- Formulaire de modification caché -->
    <tr id="edit-row-<?= $ticket['id'] ?>" style="display:none;">
        <form method="post" action="updateTicket">
        <td><?= getClientName($ticket['fk_soc'], $tiers)?></td>
        <td><?= $ticket['subject'] ?></td>
        <td><?= $ticket['message'] ?></td>
        <td><?= date('Y-m-d H:i:s', $ticket['date_creation']) ?></td>
        <td>
            <select name="statut">
                <option value="0" <?= $ticket['fk_statut'] == 0 ? 'selected' : '' ?>>Brouillon</option>
                <option value="1" <?= $ticket['fk_statut'] == 1 ? 'selected' : '' ?>>Ouvert</option>
                <option value="2" <?= $ticket['fk_statut'] == 2 ? 'selected' : '' ?>>En cours de traitement</option>
                <option value="3" <?= $ticket['fk_statut'] == 3 ? 'selected' : '' ?>>Fermé (ou Résolu)</option>
                <option value="-1" <?= $ticket['fk_statut'] == -1 ? 'selected' : '' ?>>Annulé</option>
            </select>
        </td>
        <td>
            <select name="priorite">
                <option value="1" <?= $ticket['severity_code'] == 1 ? 'selected' : '' ?>>Basse</option>
                <option value="2" <?= $ticket['severity_code'] == 2 ? 'selected' : '' ?>>Moyenne</option>
                <option value="3" <?= $ticket['severity_code'] == 3 ? 'selected' : '' ?>>Haute</option>
            </select>
        </td>
        <td>
            <select name="agent">
                <option value="">-- Choisir un agent --</option>
                <?php foreach ($agents as $agent): ?>
                    <option value="<?= $agent['id'] ?>" <?= $ticket['fk_user_assign'] == $agent['id'] ? 'selected' : '' ?>>
                        <?= $agent['lastname'] ?? $agent['login'] ?? 'Agent' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="number" step="0.01" name="montantPrevu" value="<?= isset($ticket['montantPrevu']) ? htmlspecialchars($ticket['montantPrevu']) : '' ?>" placeholder="Montant prévu" required>
        </td>
        <td>
            <input type="number" step="0.01" name="duree" value="<?= isset($ticket['duree']) ? htmlspecialchars($ticket['duree']) : '' ?>" placeholder="Temps estimé (h)" required>
        </td>
        <td>
            <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
            <button class="save-btn" type="submit">Enregistrer</button>
            <button class="cancel-btn" type="button" onclick="hideEditForm(<?= $ticket['id'] ?>)">Annuler</button>
        </td>
        </form>
    </tr>
<?php endforeach; ?>
</tbody>
    </table>
</section>

<script>
function showEditForm(id) {
    document.getElementById('edit-row-' + id).style.display = '';
    document.getElementById('display-row-' + id).style.display = 'none';
}
function hideEditForm(id) {
    document.getElementById('edit-row-' + id).style.display = 'none';
    document.getElementById('display-row-' + id).style.display = '';
}
</script>

