<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/Employee.php';
require_once 'classes/Bonus.php';

$employee = new Employee();
$bonus = new Bonus();

if (!isset($_GET['employee_id'])) {
    die('ID de l\'employé manquant');
}

$employee_id = $_GET['employee_id'];
$currentEmployee = $employee->getEmployeeById($employee_id);

if (!$currentEmployee) {
    die('Employé non trouvé');
}

$bonuses = $bonus->getBonusesByEmployee($employee_id);
$total_bonuses = $bonus->getTotalBonuses($employee_id);

// Débogage : Affichez les données récupérées
/*
echo 'Employee ID: ' . htmlspecialchars($employee_id) . '<br>';
echo '<pre>';
print_r($currentEmployee);
echo '</pre>';
echo '<pre>';
print_r($bonuses);
echo '</pre>';
*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Primes de <?= htmlspecialchars($currentEmployee['first_name'] . ' ' . $currentEmployee['last_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Primes de <?= htmlspecialchars($currentEmployee['first_name'] . ' ' . $currentEmployee['last_name']) ?></h2>
        <a href="add_bonus.php?employee_id=<?= $currentEmployee['id'] ?>" class="btn btn-success mb-3">Ajouter une Prime</a>
        <a href="index.php" class="btn btn-secondary mb-3">Retour à la liste des employés</a>
        
        <h4>Total des Primes: <?= number_format($total_bonuses, 2) ?> €</h4>
        
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Montant (€)</th>
                    <th>Date</th>
                    <th>Raison</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($bonuses) > 0): ?>
                    <?php foreach ($bonuses as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['id']) ?></td>
                            <td><?= number_format($b['bonus_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($b['bonus_date']) ?></td>
                            <td><?= htmlspecialchars($b['reason']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune prime trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
