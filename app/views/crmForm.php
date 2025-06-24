<div class="crmForm" id="crmForm">
    <form action="saveCRM" method="POST">
        <fieldset>
            <h1>Effectuer une action CRM</h1>
            <label for="dept">Type</label>
            <select name="idDept" id="dept">
                <?php foreach ($depts as $dept) { ?>
                    <option value="<?= $dept->getIdDept() ?>"><?= $dept->getNomDept() ?></option>
                <?php } ?>

            </select>

            <label for="action">Action CRM</label>
            <input type="text" name="action" id="action" placeholder="Entrer un action">
           

            <label for="crm">Reaction CRM</label>
            <select name="idCrm" id="crm">
                <?php foreach ($crms as $crm) { ?>
                    <?php foreach ($crms as $crm) { ?>
                        <option value="<?= $crm->getIdCrm() ?>"><?= $crm->getLabel() ?></option>
                    <?php } ?>
                <?php } ?>

            </select>

            <label for="valeur">Valeur : </label>
            <input type="number" name="valeur" id="valeur" placeholder="Ex: 1000000">

            <label for="dateCrm">Date : </label>
            <input type="date" name="dateCrm" id="dateCrm">

            <button type="submit">Ajouter</button>

            <?php
            if (isset($erreur)) { ?>
                <div class="error">
                    <?= $erreur; ?>
                </div>
            <?php } ?>
        </fieldset>
    </form>
</div>