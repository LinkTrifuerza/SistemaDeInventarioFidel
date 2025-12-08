<?php
session_start();
if (isset($_SESSION['id_empleado'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
