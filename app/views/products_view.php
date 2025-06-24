<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Produits</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Liste des Utilisateurs</h1>
    
    <?php if (!empty($products)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>login</th>
                    <th>iplastlogin</th>
                    <!-- Ajoutez d'autres colonnes selon vos besoins -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>
</body>
</html>