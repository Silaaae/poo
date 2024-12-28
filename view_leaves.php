<?php
// view_leaves.php
require_once 'classes/Employee.php';
require_once 'classes/Leave.php';

$employee = new Employee();
$leave = new Leave();

if (!isset($_GET['employee_id'])) {
    die('ID de l\'employé manquant');
}

$employee_id = $_GET['employee_id'];
$currentEmployee = $employee->getEmployeeById($employee_id);

if (!$currentEmployee) {
    die('Employé non trouvé');
}

// Récupérer les congés validés
$leaves = $leave->getLeavesByEmployee($employee_id);

// Calculer le solde des congés (simplifié)
$balance = [
    'annuel' => 20, // Exemple de solde initial
    'sick' => 10,
    'maternité' => 90,
    'autre' => 5
];

foreach ($leaves as $lv) {
    if ($lv['status'] === 'validé' && isset($balance[$lv['type']])) {
        // Calcul simplifié en soustrayant le nombre de jours
        $start = new DateTime($lv['start_date']);
        $end = new DateTime($lv['end_date']);
        $interval = $start->diff($end);
        $days = $interval->days + 1; // Inclure le jour de fin
        $balance[$lv['type']] -= $days;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solde des Congés</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin-top: 20px;
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
            margin-top: 20px;
            background-color: #ffc107;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a.button:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <h2>Solde des Congés pour <?= htmlspecialchars($currentEmployee['first_name'] . ' ' . $currentEmployee['last_name']) ?></h2>
    <table>
        <tr>
            <th>Type de Congé</th>
            <th>Solde Restant (jours)</th>
        </tr>
        <?php foreach ($balance as $type => $remaining): ?>
            <tr>
                <td><?= htmlspecialchars(ucfirst($type)) ?></td>
                <td><?= htmlspecialchars($remaining) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="apply_leave.php" class="button">Demander un Congé</a>
    <a href="index.php" class="button">Retour à la liste des employés</a>
</body>
</html>
