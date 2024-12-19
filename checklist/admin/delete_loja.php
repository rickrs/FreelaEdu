<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclua o arquivo de conexão com o banco de dados

$id = $_GET['id']; // ID da loja a ser deletada

// Consulta para deletar a loja da tabela lojas
$sql = "DELETE FROM lojas WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: lojas.php"); // Redireciona para a página de listagem de lojas após a exclusão
    exit();
} else {
    echo "Erro ao deletar a loja: " . $conn->error;
}
?>
