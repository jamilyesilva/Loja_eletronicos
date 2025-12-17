<?php
session_start();

if (!isset($_SESSION['type_user']) || strtolower(trim($_SESSION['type_user'])) !== 'admin') {
    header("Location: ../public/index.php");
    exit;
}
