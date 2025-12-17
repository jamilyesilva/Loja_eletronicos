<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/cart.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: ../public/cart.php');
    exit;
}

$cod_user = $_SESSION['cod_user'] ?? null;
if (!$cod_user) {
    header('Location: ../public/cart.php?ordered=0&need_login=1');
    exit;
}

try {
    $conn->beginTransaction();
    $numPedido = "ES" . random_int(100000, 999999);
    // criar pedido
    $stmt = $conn->prepare("INSERT INTO pedido (cod_user, data_pedido, num_pedido) VALUES (:u, NOW(), :num)");
    $stmt->bindValue(':u', $cod_user, PDO::PARAM_INT);
    $stmt->bindValue(':num', $numPedido);
    $stmt->execute();
    $pedidoId = (int) $conn->lastInsertId();

    foreach ($cart as $item) {
        $prodId = (int) $item['cod_product'];
        $qty = (int) $item['quant'];
        $price = (float) $item['valor'];

        $stmtChk = $conn->prepare('SELECT quant FROM products WHERE cod_product = :id FOR UPDATE');
        $stmtChk->bindValue(':id', $prodId, PDO::PARAM_INT);
        $stmtChk->execute();
        $row = $stmtChk->fetch(PDO::FETCH_ASSOC);
        $stock = $row ? (int)$row['quant'] : 0;

        if ($stock < $qty) {
            $conn->rollBack();
            header('Location: ../public/cart.php?ordered=0&out_of_stock=1');
            exit;
        }


        $stmtIns = $conn->prepare('INSERT INTO itens_pedido (cod_pedido, cod_product, quantidade, preco_unitario) VALUES (:pedido, :prod, :qty, :preco)');
        $stmtIns->bindValue(':pedido', $pedidoId, PDO::PARAM_INT);
        $stmtIns->bindValue(':prod', $prodId, PDO::PARAM_INT);
        $stmtIns->bindValue(':qty', $qty, PDO::PARAM_INT);
        $stmtIns->bindValue(':preco', $price);
        $stmtIns->execute();


        $stmtUpd = $conn->prepare('UPDATE products SET quant = quant - :qty WHERE cod_product = :id');
        $stmtUpd->bindValue(':qty', $qty, PDO::PARAM_INT);
        $stmtUpd->bindValue(':id', $prodId, PDO::PARAM_INT);
        $stmtUpd->execute();
    }

    $conn->commit();


    unset($_SESSION['cart']);
    header('Location: ../public/cart.php?sucess');
    exit;
} catch (PDOException $e) {
    if ($conn->inTransaction()) {$conn->rollBack();}
    header('Location: ../public/cart.php?ordered=0');
    exit;
}