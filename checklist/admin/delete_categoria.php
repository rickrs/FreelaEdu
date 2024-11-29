<?php
session_start();

// Verifica se o usuário está logado e é admin, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclua o arquivo de conexão com o banco de dados

$id = $_GET['id'];

$sql = "DELETE FROM categorias WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: categorias.php");
    exit();
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}
?>
