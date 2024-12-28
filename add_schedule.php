<?php
// add_schedule.php
require_once 'classes/Employee.php';
require_once 'classes/Schedule.php';

$employee = new Employee();
$schedule = new Schedule();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $data = [
        ':employee_id' => $_POST['employee_id'],
        ':date' => $_POST['date'],
        ':hours_worked' => $_POST['hours_worked']
    ];
    
    // Ajouter les horaires
    $schedule->addSchedule($data);
    
    // Rediriger vers la vue des horaires
    header('Location: view_schedule.php?employee_id=' . $_POST['employee_id']);
    exit();
}

$activeEmployees = $employee->getAllEmployees(['status' => 'actif']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter des Horaires</title>
    <style>
        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        input, select {
            padding: 5px;
            width: 200px;
        }
        button {
            padding: 8px 12px;
            background-color: #17a2b8;
            color: white;
            border: none;
            border-radius: 4px;
        }
        a {
            text-decoration: none;
            color: #007bff;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h2>Ajouter des Horaires</h2>
    <form method="POST">
        <label>Employé:</label>
        <select name="employee_id" required>
            <option value="">Sélectionner</option>
            <?php foreach ($activeEmployees as $emp): ?>
                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        
        <label>Date:</label>
        <input type="date" name="date" required><br>
        
        <label>Heures Travaillées:</label>
        <input type="number" step="0.1" name="hours_worked" required><br>
        
        <button type="submit">Ajouter</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>
