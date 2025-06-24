
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

<h1>Liste des tickets</h1>




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
        </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    
               
                <td><?= getClientName($ticket['fk_soc'], $tiers)?></td>
                <td><?= $ticket['subject'] ?></td>
                <td><?= $ticket['message'] ?></td>
                <td><?= $ticket['date_creation'] ?></td>
                
                <td><?= getStatutLabel($ticket['fk_statut']) ?></td>
                <td><?= $ticket['severity_code'] ?></td>
                 <td>
                        <select name="agent_assign_<?= $ticket['fk_user_assign_string'] ?>">
                            <!-- <option value="">-- Choisir un agent --</option>
                            <option value="agent1">Agent 1</option>
                            <option value="agent2">Agent 2</option>
                            <option value="agent3">Agent 3</option> -->
                             <option value="">-- Choisir un agent --</option>
                            <?php foreach ($agents as $agent): ?>
                                <option value="<?= $agent['id'] ?>">
                                    <?= $agent['lastname'] ?? $agent['login'] ?? 'Agent' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>