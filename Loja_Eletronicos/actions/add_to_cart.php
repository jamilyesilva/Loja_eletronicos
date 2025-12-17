<?php
ob_start();
session_start();
require_once __DIR__ . '/../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/produtos.php');
    exit;
}
$cod = isset($_POST['cod_product']) ? (int) $_POST['cod_product'] : 0;
if ($cod <= 0) {
    header('Location: ../public/produtos.php');
    exit;
}

try {
    $stmt = $conn->prepare('SELECT cod_product, p_name, description, valor, quant FROM products WHERE cod_product = :id');
    $stmt->bindValue(':id', $cod, PDO::PARAM_INT);
    $stmt->execute();
    $p = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header('Location: ../public/produtos.php');
    exit;
}

if (!$p) {
    header('Location: ../public/produtos.php');
    exit;
}

$price = (float) $p['valor'];
$stock = (int) $p['quant'];
$currentQty = isset($_SESSION['cart'][$cod]) ? (int) $_SESSION['cart'][$cod]['quant'] : 0;
if ($stock <= 0 || ($currentQty + 1) > $stock) {
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../public/produtos.php';
    $redirect = strtok($redirect, '?'); //limpa query string
    header('Location: ' . $redirect . '?out_of_stock=1');
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$cod])) {
    $_SESSION['cart'][$cod]['quant'] += 1;
} else {
    $_SESSION['cart'][$cod] = [
        'cod_product' => (int) $p['cod_product'],
        'p_name' => $p['p_name'],
        'description' => $p['description'],
        'valor' => $price,
        'quant' => 1
    ];
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '../public/produtos.php';
$redirect = strtok($redirect, '?');
header('Location: ' . $redirect . '?added=1');
exit;