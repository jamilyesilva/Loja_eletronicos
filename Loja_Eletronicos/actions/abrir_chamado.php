<?php

require_once __DIR__ . '/../includes/conexao.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/reclame.php');
    exit;
}

$cod_pedido = isset($_POST['cod_pedido']) ? trim($_POST['cod_pedido']) : '';
$problem = isset($_POST['problem']) ? trim($_POST['problem']) : '';
$cod_user = $_SESSION['cod_user'] ?? null;

if (!$cod_user || $cod_pedido === '' || $problem === '') {
    header('Location: ../public/reclame.php?opened=0');
    exit;
}

try {
    $stmtPedido = $conn->prepare('SELECT num_pedido FROM pedido WHERE cod_pedido = :id AND cod_user = :u');
    $stmtPedido->bindValue(':id', $cod_pedido, PDO::PARAM_INT);
    $stmtPedido->bindValue(':u', $cod_user, PDO::PARAM_INT);
    $stmtPedido->execute();

    $row = $stmtPedido->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header('Location: ../public/reclame.php?opened=0');
        exit;
    }
} catch (PDOException $e) {
    header('Location: ../public/reclame.php?opened=0');
    exit;
}

$num_pedido = $row['num_pedido'];

try {
    $sql = 'INSERT INTO chamado (problem, data_abertura, cod_pedido) VALUES (:problem, NOW(), :pedido)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':problem', $problem);
    $stmt->bindValue(':pedido', $cod_pedido, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: ../public/reclame.php?opened=1');
    exit;
} catch (PDOException $e) {
    header('Location: ../public/reclame.php?opened=0');
    exit;
}
