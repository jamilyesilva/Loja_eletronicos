<?php
require_once "../includes/protect_adm.php";
require_once "../includes/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../adm/produtos.php');
    exit;
}

$cod = isset($_POST['cod_product']) ? (int) $_POST['cod_product'] : 0;
$image = $_POST['image'] ?? null;

try {
    $stmt = $conn->prepare('DELETE FROM products WHERE cod_product = :id');
    $stmt->bindValue(':id', $cod, PDO::PARAM_INT);
    $stmt->execute();

    if (!empty($image) && file_exists(__DIR__ . '/../public/assets/produtos/' . $image)) {
        @unlink(__DIR__ . '/../public/assets/produtos/' . $image);
    }

    header('Location: ../adm/produtos.php?deleted=1');
    exit;
} catch (PDOException $e) {
    error_log('Erro ao excluir produto: ' . $e->getMessage());
    header('Location: ../adm/produtos.php?error=1');
    exit;
}
