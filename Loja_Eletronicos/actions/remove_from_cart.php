<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/cart.php');
    exit;
}

$cod = isset($_POST['cod_product']) ? (int) $_POST['cod_product'] : 0;
$decrease = isset($_POST['decrease']);

if ($cod > 0 && isset($_SESSION['cart'][$cod])) {
    if ($decrease) {
        if ($_SESSION['cart'][$cod]['quantity'] > 1) {
            $_SESSION['cart'][$cod]['quantity'] -= 1;
            header('Location: ../public/cart.php?decreased=1');
            exit;
        } else {
            // se for a última unidade, remover completamente
            unset($_SESSION['cart'][$cod]);
            header('Location: ../public/cart.php?removed=1');
            exit;
        }
    } else {
        unset($_SESSION['cart'][$cod]);
        header('Location: ../public/cart.php?removed=1');
        exit;
    }
}

header('Location: ../public/cart.php');
exit;