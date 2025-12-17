<?php
$servename = 'localhost';
$username = 'root';
$password = '';
$db = 'sis_eletronica';

try{
    $conn = new PDO("mysql:host=$servename; dbname=$db", $username, $password);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    die("Erro na conexão: " . $error->getMessage());
}
?>