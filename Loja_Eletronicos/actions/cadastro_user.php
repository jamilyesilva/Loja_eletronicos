<?php
require_once "../includes/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"]);
    $nickname = trim($_POST["nickname"]);
    $email    = trim($_POST["email"]);
    $data_nascimento    = $_POST["data_nascimento"];
    $pass     = $_POST["pass"];

    $passHash = password_hash($pass, PASSWORD_DEFAULT); //criptografando

    try {
        $sql = "INSERT INTO usuarios (name, nickname, email, data_nascimento, pass)
                VALUES (:name, :nickname, :email, :data_nascimento, :pass)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":nickname", $nickname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":data_nascimento", $data_nascimento);
        $stmt->bindParam(":pass", $passHash);

        $stmt->execute();

        header("Location: ../public/index.php?registered=1");
        exit;

    } catch (PDOException $error) {
        error_log('Cadastro usuário falhou: ' . $error->getMessage());
        header('Location: ../public/form_user.php?error=1');
        exit;
    }
}
