<?php
require_once "../includes/protect_adm.php";
require_once "../includes/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    header('Location: ../adm/produtos.php');
    exit;
}

$cod = isset($_POST['cod_product']) ? (int) $_POST['cod_product'] : 0;
$nome = $_POST['p_name'] ?? '';
$valor = $_POST['valor'] ?? 0;
$quant = $_POST['quant'] ?? 0;
$desc = $_POST['description'] ?? '';
$oldImage = $_POST['old_image'] ?? null;

// checar produto
$stmt = $conn->prepare('SELECT * FROM products WHERE cod_product = :id');
$stmt->bindValue(':id', $cod, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header('Location: ../adm/produtos.php');
    exit;
}

$newImageName = $oldImage;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['image']['tmp_name'];
    $size = $_FILES['image']['size'] ?? 0;

    $mime = mime_content_type($tmpName);
    $allowedMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!in_array($mime, array_keys($allowedMimes))) {
        header('Location: ../adm/editar_prod_form.php?edit=' . $cod . '&error=invalid_image');
        exit;
    }

    $novoNome = uniqid() . '.' . $allowedMimes[$mime];
    $destino = __DIR__ . "/../public/assets/produtos/" . $novoNome;
    if (move_uploaded_file($tmpName, $destino)) {
        if (!empty($oldImage) && file_exists(__DIR__ . '/../public/assets/produtos/' . $oldImage)) {
            @unlink(__DIR__ . '/../public/assets/produtos/' . $oldImage);
        }
        $newImageName = $novoNome;
    }
}

try {
    $sql = "UPDATE products SET p_name = :name, valor = :valor, quant = :quant, description = :descr, image = :img WHERE cod_product = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':name', $nome);
    $stmt->bindValue(':valor', $valor);
    $stmt->bindValue(':quant', $quant);
    $stmt->bindValue(':descr', $desc);
    $stmt->bindValue(':img', $newImageName);
    $stmt->bindValue(':id', $cod);
    $stmt->execute();

    header('Location: ../adm/produtos.php?edited=1');
    exit;
} catch (PDOException $e) {
    error_log('Erro ao atualizar produto: ' . $e->getMessage());
    header('Location: ../adm/editar_prod_form.php?edit=' . $cod . '&error=1');
    exit;
}
