<?php
require_once 'classes/Employee.php';
require_once 'classes/Department.php';
require_once 'classes/Position.php';

$employee = new Employee();
$department = new Department();
$position = new Position();

if (!isset($_GET['id'])) {
    die('ID de l\'employé manquant');
}

$id = $_GET['id'];
$currentEmployee = $employee->getEmployeeById($id);

if (!$currentEmployee) {
    die('Employé non trouvé');
}

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
    
    $employee->updateEmployee($id, $data);
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
    <title>Modifier un Employé</title>
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
        <h2>Modifier un Employé</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Prénom:</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($currentEmployee['first_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nom:</label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($currentEmployee['last_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($currentEmployee['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone:</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($currentEmployee['phone']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Département:</label>
                <select name="department" class="form-select" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>" <?= ($currentEmployee['department_id'] == $dept['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Poste:</label>
                <select name="position" class="form-select" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($positions as $pos): ?>
                        <option value="<?= $pos['id'] ?>" <?= ($currentEmployee['position_id'] == $pos['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pos['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Statut:</label>
                <select name="status" class="form-select" required>
                    <option value="actif" <?= ($currentEmployee['status'] == 'actif') ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= ($currentEmployee['status'] == 'inactif') ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Date d'embauche:</label>
                <input type="date" name="date_hired" class="form-control" value="<?= htmlspecialchars($currentEmployee['date_hired']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Salaire (€):</label>
                <input type="number" step="0.01" name="salary" class="form-control" value="<?= htmlspecialchars($currentEmployee['salary']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Conditions de Travail:</label>
                <textarea name="conditions" class="form-control"><?= htmlspecialchars($currentEmployee['conditions']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Manager:</label>
                <select name="manager" class="form-select">
                    <option value="">Aucun</option>
                    <?php foreach ($managers as $mgr): ?>
                        <?php if ($mgr['id'] != $currentEmployee['id']) { ?>
                            <option value="<?= $mgr['id'] ?>" <?= ($currentEmployee['manager_id'] == $mgr['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mgr['first_name'] . ' ' . $mgr['last_name']) ?>
                            </option>
                        <?php } ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Mettre à Jour</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
            <a href="add_bonus.php?employee_id=<?= $currentEmployee['id'] ?>" class="btn btn-success">Ajouter une Prime</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
