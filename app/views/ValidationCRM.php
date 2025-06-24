<div class="navigationTable">
    <h1><i class="fas fa-check-circle"></i> Validation</h1>
    <div class="controls">
        <input type="text" placeholder="Rechercher..." class="search-input">
        <select  class="sort-select">
            <option value="">Trier par</option>
            <option value="nom">Nom Departement</option>
            <option value="date">Date</option>
            <option value="montant">Montant</option>
        </select>
        <button class="pdf-btn"><i class="fas fa-file-pdf"></i> Exporter en PDF</button>
    </div>
</div>

<section class="validationList">
    <table>
        <thead>
            <tr>
                <th>Departement</th>
                <th>Action</th>
                <th>Reaction</th>
                <th>Date</th>
                <th>Montant</th>
                <th class="thAction">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($validations as $validation) { ?>
                <tr>
                    <td><?= htmlspecialchars($validation['idCrm']) ?></td>
                    <td><?= htmlspecialchars($validation['action']) ?></td>
                    <td><?= htmlspecialchars($validation['label']) ?></td>
                    <td><?= htmlspecialchars($validation['dateCrm']) ?></td>

                    <td><?= number_format($validation['valeur'], 2, ',', ' ') ?> MGA</td>
                    <td class="action-buttons">
                        <a href="#"><button class="edit-btn">Details</button></a>
                        <a href="<?= Flight::get('flight.base_url') ?>/validationCrm/valider/<?= $validation['idCrm'] ?>">
                            <button class="edit-btn">Valider</button>
                        </a>
                        <a href="<?= Flight::get('flight.base_url') ?>/validationCrm/refuser/<?= $validation['idCrm'] ?>">
                            <button class="delete-btn">Refuser</button>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>