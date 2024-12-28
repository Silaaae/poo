<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xamp/htdocs/erp_rh/error_log.txt'); // Chemin corrigé

require_once 'classes/Employee.php';
require_once 'classes/Bonus.php';

$employee = new Employee();
$bonus = new Bonus();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!filter_var($_POST['employee_id'], FILTER_VALIDATE_INT)) {
        die('ID de l\'employé invalide.');
    }

    $data = [
        ':employee_id' => $_POST['employee_id'],
        ':bonus_amount' => $_POST['bonus_amount'],
        ':bonus_date' => $_POST['bonus_date'],
        ':reason' => trim($_POST['reason'])
    ];

    if ($bonus->addBonus($data)) {
        header('Location: view_bonuses.php?employee_id=' . $_POST['employee_id']);
        exit();
    } else {
        echo '<div class="alert alert-danger">Erreur lors de l\'ajout de la prime.</div>';
    }
}

$employees = $employee->getAllEmployees(['status' => 'actif']);

// Débogage : Affichez la liste des employés
/*
echo '<pre>';
print_r($employees);
echo '</pre>';
*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Prime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        label {
            width: 200px;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter une Prime</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Employé:</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Montant de la Prime (€):</label>
                <input type="number" step="0.01" name="bonus_amount" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date de la Prime:</label>
                <input type="date" name="bonus_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Raison:</label>
                <textarea name="reason" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
