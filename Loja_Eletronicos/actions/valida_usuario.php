<?php
session_start();
require_once "../includes/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nickname = trim($_POST["nickname"]);
    $pass     = $_POST["pass"];

    try {
        $sql = "SELECT cod_user, name, nickname, pass, type_user 
                FROM usuarios 
                WHERE nickname = :nickname 
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nickname", $nickname);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $stored = $usuario["pass"];
            $verified = false;

            if (password_verify($pass, $stored)) {
                $verified = true;
            } else {
                if (hash_equals($stored, $pass)) {
                    $verified = true;
                    // Re-hash e atualiza o banco
                    try {
                        $newHash = password_hash($pass, PASSWORD_DEFAULT);
                        $upd = $conn->prepare('UPDATE usuarios SET pass = :pass WHERE cod_user = :id');
                        $upd->bindValue(':pass', $newHash);
                        $upd->bindValue(':id', $usuario['cod_user'], PDO::PARAM_INT);
                        $upd->execute();
                    } catch (PDOException $e) {
                    }
                }
            }

            if ($verified) {
                $_SESSION["cod_user"]       = $usuario["cod_user"];
                $_SESSION["name"]     = $usuario["name"];
                $_SESSION["nickname"] = $usuario["nickname"];
                $type = strtolower(trim($usuario['type_user'] ?? 'user'));
                $_SESSION["type_user"] = $type;

                if ($type === "admin") {
                    header("Location: ../adm/produtos.php");
                    exit;
                } else {
                    header("Location: ../public/produtos.php");
                    exit;
                }

            } else {
                header('Location: ../public/index.php?error=credentials');
                exit;
            }

        } else {
            header('Location: ../public/index.php?error=credentials');
            exit;
        }

    } catch (PDOException $error) {
        error_log($error->getMessage());
        header('Location: ../public/index.php?error=1');
        exit;
    }
}
