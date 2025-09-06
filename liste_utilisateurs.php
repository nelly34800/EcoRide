<?php
require_once "templates/header.php";
require_once "lib/pdo.php";
require_once "lib/administrator.php";
require_once "lib/role.php";

// Vérifier que l'utilisateur est administrateur
verifRole(5);

$utilisateurs = getUsersByRole($pdo, [2, 3, 6]);
?>

<div class="container mt-4">
    <h1>Liste des utilisateurs (clients)</h1>

    <?php if (count($utilisateurs) === 0) { ?>
        <div class="alert alert-info">Aucun utilisateur enregistré pour l’instant.</div>
    <?php } else { ?>
        <table class="table table-striped table-bordered align-middle mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $user) { ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['pseudo']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['last_name']) ?></td>
                        <td><?= htmlspecialchars($user['first_name']) ?></td>
                        <td><?= htmlspecialchars($user['address']) ?></td>
                        <td>
                            <?php if ($user['status'] === 'active') { ?>
                                <a href="lib/suspend_user.php?id=<?= $user['id'] ?>" class="btn btn-primary" 
                                   onclick="return confirm('Suspendre cet utilisateur ?');">Suspendre</a>
                            <?php } else { ?>
                                <a href="lib/activate_user.php?id=<?= $user['id'] ?>" class="btn btn-primary"
                                   onclick="return confirm('Réactiver cet utilisateur ?');">Réactiver</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<?php
require_once "templates/footer.php";
?>
