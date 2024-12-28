<?php
// apply_leave.php
require_once 'classes/Employee.php';
require_once 'classes/Leave.php';

$employee = new Employee();
$leave = new Leave();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $data = [
        ':employee_id' => $_POST['employee_id'],
        ':start_date' => $_POST['start_date'],
        ':end_date' => $_POST['end_date'],
        ':type' => $_POST['type']
    ];
    
    // Appliquer pour un congé
    $leave->applyLeave($data);
    
    // Rediriger vers la vue des congés
    header('Location: view_leaves.php?employee_id=' . $_POST['employee_id']);
    exit();
}

$activeEmployees = $employee->getAllEmployees(['status' => 'actif']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Demander un Congé</title>
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
            background-color: #ffc107;
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
    <h2>Demander un Congé</h2>
    <form method="POST">
        <label>Employé:</label>
        <select name="employee_id" required>
            <option value="">Sélectionner</option>
            <?php foreach ($activeEmployees as $emp): ?>
                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        
        <label>Date de Début:</label>
        <input type="date" name="start_date" required><br>
        
        <label>Date de Fin:</label>
        <input type="date" name="end_date" required><br>
        
        <label>Type de Congé:</label>
        <select name="type" required>
            <option value="">Sélectionner</option>
            <option value="annuel">Annuel</option>
            <option value="sick">Maladie</option>
            <option value="maternité">Maternité</option>
            <option value="autre">Autre</option>
        </select><br>
        
        <button type="submit">Soumettre</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>
