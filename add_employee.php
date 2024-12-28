<?php
require_once 'classes/Employee.php';
require_once 'classes/Department.php';
require_once 'classes/Position.php';

$employee = new Employee();
$department = new Department();
$position = new Position();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die('Adresse email invalide.');
    }

    $data = [
        ':first_name' => trim($_POST['first_name']),
        ':last_name'  => trim($_POST['last_name']),
        ':email'      => trim($_POST['email']),
        ':phone'      => trim($_POST['phone']),
        ':department_id' => $_POST['department'],
        ':position_id'   => $_POST['position'],
        ':status'        => $_POST['status'],
        ':date_hired'    => $_POST['date_hired'],
        ':salary'        => !empty($_POST['salary']) ? $_POST['salary'] : null,
        ':conditions'    => trim($_POST['conditions']),
        ':manager_id'    => !empty($_POST['manager']) ? $_POST['manager'] : null
    ];
    
    $employee->addEmployee($data);
    header('Location: index.php');
    exit();
}

$departments = $department->getAllDepartments();
$positions = $position->getAllPositions();
$managers = $employee->getAllManagers();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Employé</title>
    <style>
        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
            vertical-align: top;
        }
        input, select, textarea {
            padding: 5px;
            width: 300px;
            margin-bottom: 10px;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        button {
            padding: 8px 12px;
            background-color: #28a745;
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
    <h2>Ajouter un Employé</h2>
    <form method="POST">
        <label>Prénom:</label>
        <input type="text" name="first_name" required><br>
        
        <label>Nom:</label>
        <input type="text" name="last_name" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br>
        
        <label>Téléphone:</label>
        <input type="text" name="phone"><br>
        
        <label>Département:</label>
        <select name="department" required>
            <option value="">Sélectionner</option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        
        <label>Poste:</label>
        <select name="position" required>
            <option value="">Sélectionner</option>
            <?php foreach ($positions as $pos): ?>
                <option value="<?= $pos['id'] ?>"><?= htmlspecialchars($pos['title']) ?></option>
            <?php endforeach; ?>
        </select><br>
        
        <label>Statut:</label>
        <select name="status" required>
            <option value="actif">Actif</option>
            <option value="inactif">Inactif</option>
        </select><br>
        
        <label>Date d'embauche:</label>
        <input type="date" name="date_hired" required><br>
        
        <label>Salaire (€):</label>
        <input type="number" step="0.01" name="salary"><br>
        
        <label>Conditions de Travail:</label>
        <textarea name="conditions"></textarea><br>
        
        <label>Manager:</label>
        <select name="manager">
            <option value="">Aucun</option>
            <?php foreach ($managers as $mgr): ?>
                <option value="<?= $mgr['id'] ?>"><?= htmlspecialchars($mgr['first_name'] . ' ' . $mgr['last_name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        
        <button type="submit">Ajouter</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>
