<?php
require_once 'classes/Employee.php';
require_once 'classes/Evaluation.php';

$employee = new Employee();
$evaluation = new Evaluation();

$selected_employee_id = null;
if (isset($_GET['employee_id'])) {
    $selected_employee_id = $_GET['employee_id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        ':employee_id' => $_POST['employee_id'],
        ':evaluation_date' => $_POST['evaluation_date'],
        ':score' => $_POST['score'],
        ':comments' => $_POST['comments']
    ];
    
    $evaluation->addEvaluation($data);
    header('Location: view_evaluations.php?employee_id=' . $_POST['employee_id']);
    exit();
}

$activeEmployees = $employee->getAllEmployees(['status' => 'actif']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Évaluation</title>
    <style>
        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        input, select, textarea {
            padding: 5px;
            width: 200px;
        }
        textarea {
            resize: vertical;
        }
        button {
            padding: 8px 12px;
            background-color: #6f42c1;
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
    <h2>Ajouter une Évaluation</h2>
    <form method="POST">
        <label>Employé:</label>
        <select name="employee_id" required>
            <option value="">Sélectionner</option>
            <?php foreach ($activeEmployees as $emp): ?>
                <option value="<?= $emp['id'] ?>" <?= ($selected_employee_id == $emp['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        
        <label>Date d'Évaluation:</label>
        <input type="date" name="evaluation_date" required><br>
        
        <label>Score:</label>
        <select name="score" required>
            <option value="">Sélectionner</option>
            <?php for ($i=1; $i<=5; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select><br>
        
        <label>Commentaires:</label><br>
        <textarea name="comments" rows="4" cols="50"></textarea><br>
        
        <button type="submit">Ajouter</button>
        <a href="view_evaluations.php?employee_id=<?= $selected_employee_id ?>">Annuler</a>
    </form>
</body>
</html>
