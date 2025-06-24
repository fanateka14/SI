<style>
.discussion-container {
    max-width: 800px;
    margin: 30px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 32px 40px;
}
.discussion-container h2 {
    text-align: center;
    color: #2d3a4b;
    margin-bottom: 24px;
    letter-spacing: 1px;
}
.discussion-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 32px;
}
.discussion-form label {
    font-weight: 500;
    color: #3a4a5d;
}
.discussion-form select,
.discussion-form textarea,
.discussion-form input[type="text"] {
    border: 1px solid #d1d9e6;
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 1rem;
    background: #f7f9fb;
    transition: border 0.2s;
}
.discussion-form select:focus,
.discussion-form textarea:focus,
.discussion-form input[type="text"]:focus {
    border-color: #4f8cff;
    outline: none;
}
.discussion-form button {
    background: #4f8cff;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 12px 0;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
}
.discussion-form button:hover {
    background: #2563eb;
}
.discussion-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
    background: #f7f9fb;
    border-radius: 8px;
    overflow: hidden;
}
.discussion-table th, .discussion-table td {
    padding: 12px 10px;
    text-align: left;
}
.discussion-table th {
    background: #e9eef6;
    color: #2d3a4b;
    font-weight: 600;
    border-bottom: 2px solid #d1d9e6;
}
.discussion-table tr:nth-child(even) {
    background: #f1f5fa;
}
.discussion-table tr:hover {
    background: #e3eaf6;
}
@media (max-width: 600px) {
    .discussion-container {
        padding: 16px 4px;
    }
    .discussion-table th, .discussion-table td {
        padding: 8px 4px;
    }
}
</style>

<div class="discussion-container">
    <h2>Ajouter une discussion</h2>
    <form class="discussion-form" method="post" action="">
        <label for="idTier">Tiers :</label>
        <select name="idTier" id="idTier" required>
            <option value="">-- Sélectionner un tiers --</option>
            <?php if (!empty($tiers)): ?>
                <?php foreach ($tiers as $tier): ?>
                    <option value="<?= htmlspecialchars($tier['id'] ?? '') ?>">
                        <?= htmlspecialchars($tier['name'] ?? $tier['nom'] ?? $tier['label'] ?? 'Tiers') ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <label for="message">Message :</label>
        <textarea name="message" id="message" rows="3" required></textarea>

        <button type="submit">Ajouter</button>
    </form>

    <h2>Liste des discussions</h2>
    <table class="discussion-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiers</th>
                <th>Message</th>
                <th>Réponse</th>
                <th>Date/Heure</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($discussions)): ?>
                <?php foreach ($discussions as $discussion): ?>
                    <tr>
                        <td><?= htmlspecialchars($discussion['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($discussion['idTier'] ?? '') ?></td>
                        <td><?= htmlspecialchars($discussion['message'] ?? '') ?></td>
                        <td>
                            <?= htmlspecialchars($discussion['reponse'] ?? '') ?>
                            <?php if (empty($discussion['reponse'])): ?>
                                <button type="button" onclick="toggleReponseForm(<?= $discussion['id'] ?>)">Répondre</button>
                                <form method="post" action="" style="display:none;margin-top:8px;" id="reponse-form-<?= $discussion['id'] ?>">
                                    <input type="hidden" name="discussion_id" value="<?= $discussion['id'] ?>">
                                    <input type="text" name="reponse" placeholder="Votre réponse" required>
                                    <button type="submit" name="submit_reponse">Envoyer</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($discussion['dateHeure'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Aucune discussion trouvée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function toggleReponseForm(id) {
    var form = document.getElementById('reponse-form-' + id);
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
}
</script>