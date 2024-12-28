<?php
require_once 'classes/Employee.php';
require_once 'classes/Evaluation.php';

$employee = new Employee();
$evaluation = new Evaluation();

if (!isset($_GET['employee_id'])) {
    die('ID de l\'employé manquant');
}

$employee_id = $_GET['employee_id'];
$currentEmployee = $employee->getEmployeeById($employee_id);

if (!$currentEmployee) {
    die('Employé non trouvé');
}

$evaluations = $evaluation->getEvaluationsByEmployee($employee_id);
$reports = $evaluation->getReportsByYear($employee_id);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Évaluations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ddd;
        }
        a.button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #6f42c1;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        a.button:hover {
            background-color: #5a32a3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">Évaluations pour <?= htmlspecialchars($currentEmployee['first_name'] . ' ' . $currentEmployee['last_name']) ?></h2>
        <a href="add_evaluation.php?employee_id=<?= $currentEmployee['id'] ?>" class="btn btn-secondary mb-3">Ajouter une Évaluation</a>
        <a href="index.php" class="btn btn-primary mb-3">Retour à la liste des employés</a>
        
        <h3>Historique des Évaluations</h3>
        <table class="table table-bordered table-striped">
            <tr>
                <th>Date</th>
                <th>Score</th>
                <th>Commentaires</th>
            </tr>
            <?php if (count($evaluations) > 0): ?>
                <?php foreach ($evaluations as $eval): ?>
                    <tr>
                        <td><?= htmlspecialchars($eval['evaluation_date']) ?></td>
                        <td><?= htmlspecialchars($eval['score']) ?></td>
                        <td><?= htmlspecialchars($eval['comments']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Aucune évaluation trouvée.</td>
                </tr>
            <?php endif; ?>
        </table>
        
        <h3>Rapports par Année</h3>
        <table class="table table-bordered table-striped">
            <tr>
                <th>Année</th>
                <th>Score Moyen</th>
            </tr>
            <?php if (count($reports) > 0): ?>
                <?php foreach ($reports as $rep): ?>
                    <tr>
                        <td><?= htmlspecialchars($rep['year']) ?></td>
                        <td><?= number_format($rep['average_score'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Aucun rapport disponible.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
