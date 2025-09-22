<?php
require_once "templates/header.php";
require_once "lib/pdo.php";
require_once "lib/role.php";
require_once "lib/administrator.php"; 

// Vérifier que l'utilisateur est administrateur
verifRole(5);

$employes = getUsersByRole($pdo, 4);
?>

<div class="container mt-4">
    <h1>Liste des employés</h1>

    <?php if (count($employes) === 0) { ?>
        <div class="alert alert-info">Aucun employé enregistré pour l’instant.</div>
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
                <?php foreach ($employes as $employe) { ?>
                    <tr>
                        <td><?= htmlspecialchars($employe['id']) ?></td>
                        <td><?= htmlspecialchars($employe['pseudo']) ?></td>
                        <td><?= htmlspecialchars($employe['email']) ?></td>
                        <td><?= htmlspecialchars($employe['last_name']) ?></td>
                        <td><?= htmlspecialchars($employe['first_name']) ?></td>
                        <td><?= htmlspecialchars($employe['address']) ?></td>
                       <td>
                            <?php if ($employe['status'] === 'active') { ?>
                                <a href="lib/suspend_user.php?id=<?= $employe['id'] ?>" class="btn btn-primary"
                                    onclick="return confirm('Suspendre cet employé ?');">Suspendre</a>
                            <?php } else { ?>
                                <a href="lib/activate_user.php?id=<?= $employe['id'] ?>" class="btn btn-primary"
                                    onclick="return confirm('Réactiver cet employé ?');">Réactiver</a>
                            <?php } ?>
                            <a href="lib/sup_user.php?id=<?= $employe['id'] ?>" class="btn btn-dark"
                                onclick="return confirm('Supprimer définitivement ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <a href="creer_compte_employe.php" class="btn btn-primary">Créer un compte employé</a>
</div>

<?php
require_once "templates/footer.php";
?>
