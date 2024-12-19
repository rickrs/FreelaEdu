<?php
session_start();

// Verifica se o usuário está logado e é admin, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name']) || $_SESSION['tipo_usuario'] != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso não autorizado.']);
    exit();
}

include('db.php'); // Inclua o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $upload_dir = 'uploads/';
        $filename = $_FILES['file']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION); // Obtém a extensão do arquivo
        $unique_filename = uniqid() . '.' . $extension; // Gera um nome de arquivo único
        $uploaded_file = $upload_dir . $unique_filename;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file)) {
            echo json_encode(['status' => 'success', 'file' => $unique_filename]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao fazer upload da imagem.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nenhum arquivo enviado ou erro no envio do arquivo.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}
?>
