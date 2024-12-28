<?php
require_once 'classes/Employee.php';
require_once 'classes/Department.php';
require_once 'classes/Position.php';

$employee = new Employee();
$department = new Department();
$position = new Position();

$filters = [];
if (isset($_GET['department']) && !empty($_GET['department'])) {
    $filters['department'] = $_GET['department'];
}
if (isset($_GET['position']) && !empty($_GET['position'])) {
    $filters['position'] = $_GET['position'];
}
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $filters['status'] = $_GET['status'];
}

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$employees = $employee->getAllEmployees($filters, $limit, $offset);
$total_employees = $employee->countEmployees($filters);
$total_pages = ceil($total_employees / $limit);

$departments = $department->getAllDepartments();
$positions = $position->getAllPositions();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Employés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">Liste des Employés</h2>
        <a href="add_employee.php" class="btn btn-success mb-3">Ajouter un Employé</a>
        <a href="manage_leaves.php" class="btn btn-primary mb-3">Gérer les Congés</a>
        
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label>Département:</label>
                <select name="department" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>" <?= (isset($filters['department']) && $filters['department'] == $dept['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Poste:</label>
                <select name="position" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach ($positions as $pos): ?>
                        <option value="<?= $pos['id'] ?>" <?= (isset($filters['position']) && $filters['position'] == $pos['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pos['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Statut:</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="actif" <?= (isset($filters['status']) && $filters['status'] == 'actif') ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= (isset($filters['status']) && $filters['status'] == 'inactif') ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                <a href="index.php" class="btn btn-secondary">Réinitialiser</a>
            </div>
        </form>
        
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Département</th>
                    <th>Poste</th>
                    <th>Statut</th>
                    <th>Date d'embauche</th>
                    <th>Salaire (€)</th>
                    <th>Conditions</th>
                    <th>Manager</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($employees) > 0): ?>
                    <?php foreach ($employees as $emp): ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['id']) ?></td>
                            <td><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                            <td><?= htmlspecialchars($emp['email']) ?></td>
                            <td><?= htmlspecialchars($emp['phone']) ?></td>
                            <td><?= htmlspecialchars($emp['department']) ?></td>
                            <td><?= htmlspecialchars($emp['position']) ?></td>
                            <td><?= htmlspecialchars($emp['status']) ?></td>
                            <td><?= htmlspecialchars($emp['date_hired']) ?></td>
                            <td><?= number_format($emp['salary'], 2) ?></td>
                            <td><?= htmlspecialchars($emp['conditions']) ?></td>
                            <td>
                                <?php 
                                    if ($emp['manager_first_name'] && $emp['manager_last_name']) {
                                        echo htmlspecialchars($emp['manager_first_name'] . ' ' . $emp['manager_last_name']);
                                    } else {
                                        echo 'Aucun';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="edit_employee.php?id=<?= $emp['id'] ?>" class="btn btn-warning btn-sm">Modifier</a> | 
                                <a href="delete_employee.php?id=<?= $emp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">Supprimer</a> | 
                                <a href="view_evaluations.php?employee_id=<?= $emp['id'] ?>" class="btn btn-info btn-sm">Voir les Évaluations</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center">Aucun employé trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($_GET['department']) ? '&department=' . $_GET['department'] : '' ?><?= !empty($_GET['position']) ? '&position=' . $_GET['position'] : '' ?><?= !empty($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>">Précédent</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= !empty($_GET['department']) ? '&department=' . $_GET['department'] : '' ?><?= !empty($_GET['position']) ? '&position=' . $_GET['position'] : '' ?><?= !empty($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($_GET['department']) ? '&department=' . $_GET['department'] : '' ?><?= !empty($_GET['position']) ? '&position=' . $_GET['position'] : '' ?><?= !empty($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>">Suivant</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
