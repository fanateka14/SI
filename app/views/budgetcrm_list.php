<div class="fana-table-container">
    <h1>Liste des Budgets CRM</h1>
    <form action="updateAllBudgetsCrm" method="post">
        <table class="fana-table">
            <tr>
                <th>ID</th>
                <th>ID Département</th>
                <th>Montant</th>
            </tr>
            <?php foreach ($budgets as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['idBudgetCRM']) ?></td>
                    <td><?= htmlspecialchars($b['idDept']) ?></td>
                    <td>
                        <input type="number" name="budgets[<?= $b['idBudgetCRM'] ?>]" value="<?= htmlspecialchars($b['montant']) ?>" required>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit" class="btn btn-primary" style="margin-top:16px;">Mettre à jour tous les budgets</button>
    </form>
</div>