<div class="fana-table-container">
    <h1>Laisser un avis sur le ticket #<?= htmlspecialchars($id_ticket) ?></h1>
    <form method="post" action="<?= Flight::get('flight.base_url') ?>/ticketreview-save">
        <input type="hidden" name="id_ticket" value="<?= htmlspecialchars($id_ticket) ?>">
        <div class="mb-3">
            <label for="nb_etoile">Note :</label>
            <select name="nb_etoile" id="nb_etoile" required>
                <option value="">Choisir...</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> étoile<?= $i > 1 ? 's' : '' ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
    <h2 class="mt-4">Avis existants</h2>
    <ul class="list-group">
        <?php foreach ($reviews as $r): ?>
            <li class="list-group-item">
                <span style="color: #f1c40f; font-size: 1.2em;">
                    <?php for ($i = 0; $i < $r['nb_etoile']; $i++) echo '★'; ?><?php for ($i = $r['nb_etoile']; $i < 5; $i++) echo '☆'; ?>
                </span>
                <strong><?= htmlspecialchars($r['commentaire']) ?></strong>
                <span class="text-muted" style="font-size:0.9em;">(<?= $r['date_avis'] ?>)</span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>