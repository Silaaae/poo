<?php
// delete_employee.php
require_once 'classes/Employee.php';

$employee = new Employee();

if (!isset($_GET['id'])) {
    die('ID de l\'employé manquant');
}

$id = $_GET['id'];

// Supprimer l'employé
$employee->deleteEmployee($id);

// Rediriger vers la liste des employés
header('Location: index.php');
exit();
?>
