<?php
require_once "../includes/protect_adm.php";
require_once "../includes/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['p_name']);
    $valor = $_POST['valor'];
    $quant = $_POST['quant'];
    $desc  = $_POST['description'];
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        header('Location: ../adm/form_prod.php?error=invalid_image');
        exit;
    }
    $imgName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];

    if (!in_array($ext, $allowed)) {
        header('Location: ../adm/form_prod.php?error=invalid_image');
        exit;
    }
    
    $novoNome = uniqid() . "." . $ext;
    $destinoDir = __DIR__ . "/../public/assets/produtos/";
    if (!is_dir($destinoDir) || !is_writable($destinoDir)) {
        header('Location: ../adm/form_prod.php?error=upload_dir');
        exit;
    }

    $destino = $destinoDir . $novoNome;

    if (move_uploaded_file($tmpName, $destino)) {
        try{
        $sql = "INSERT INTO products (p_name, valor, quant, description, image)
                VALUES (:name, :valor, :quant, :descr, :img)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':name', $nome);
        $stmt->bindValue(':valor', $valor);
        $stmt->bindValue(':quant', $quant);
        $stmt->bindValue(':descr', $desc);
        $stmt->bindValue(':img', $novoNome);
        $stmt->execute();

            header("Location: ../adm/produtos.php?added=1");
            exit;
        } catch (PDOException $error) {
            error_log('Erro ao cadastrar produto: ' . $error->getMessage());
            header('Location: ../adm/form_prod.php?error=1');
            exit;
        }
    } else {
        header('Location: ../adm/form_prod.php?error=upload_failed');
        exit;
    }
}
