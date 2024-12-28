<?php
// view_schedule.php
require_once 'classes/Employee.php';
require_once 'classes/Schedule.php';

$employee = new Employee();
$schedule = new Schedule();

if (!isset($_GET['employee_id'])) {
    die('ID de l\'employé manquant');
}

$employee_id = $_GET['employee_id'];
$currentEmployee = $employee->getEmployeeById($employee_id);

if (!$currentEmployee) {
    die('Employé non trouvé');
}

// Définir la semaine actuelle (du lundi au dimanche)
$today = date('Y-m-d');
$start_of_week = date('Y-m-d', strtotime('monday this week', strtotime($today)));
$end_of_week = date('Y-m-d', strtotime('sunday this week', strtotime($today)));

// Récupérer les horaires de la semaine
$schedules = $schedule->getSchedulesByEmployeeAndWeek($employee_id, $start_of_week, $end_of_week);

// Calcul des heures supplémentaires (heures > 40 par semaine)
$total_hours = 0;
foreach ($schedules as $sched) {
    $total_hours += $sched['hours_worked'];
}
$overtime = max(0, $total_hours - 40);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Planning Hebdomadaire</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
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
            background-color: #17a2b8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a.button:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>
    <h2>Planning Hebdomadaire pour <?= htmlspecialchars($currentEmployee['first_name'] . ' ' . $currentEmployee['last_name']) ?></h2>
    <a href="add_schedule.php" class="button">Ajouter des Horaires</a>
    <table>
        <tr>
            <th>Date</th>
            <th>Heures Travaillées</th>
        </tr>
        <?php if (count($schedules) > 0): ?>
            <?php foreach ($schedules as $sched): ?>
                <tr>
                    <td><?= htmlspecialchars($sched['date']) ?></td>
                    <td><?= htmlspecialchars($sched['hours_worked']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Aucun horaire trouvé pour cette semaine.</td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong><?= $total_hours ?> heures</strong></td>
        </tr>
        <tr>
            <td><strong>Heures Supplémentaires</strong></td>
            <td><strong><?= $overtime ?> heures</strong></td>
        </tr>
    </table>
    <br>
    <a href="index.php">Retour à la liste des employés</a>
</body>
</html>
