<?php
session_start();

if (!isset($_SESSION['id_empleado'])) {
    header('Location: login.php');
    exit;
}

function require_role($role) {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $role) {
        header('Location: no_autorizado.php');
        exit;
    }
}
