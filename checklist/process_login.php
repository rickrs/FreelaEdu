<?php
session_start();
include('admin/db.php'); // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_password = $_POST['user_password'];

    // Consulta o banco para verificar o nome de usuário
    $sql = "SELECT * FROM user_login WHERE user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($user_password, $row['user_password'])) {
            // Configura as sessões
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['nome_usuario'] = $row['nome_usuario'];
            $_SESSION['tipo_usuario'] = $row['tipo'];
            $_SESSION['user_login_id'] = $row['id'];

            // Redireciona para o ponto inicial do checklist
            header("Location: formulario_categorias.php");
            exit();
        } else {
            // Senha inválida
            $_SESSION['login_error'] = 'Credenciais inválidas.';
        }
    } else {
        // Usuário não encontrado
        $_SESSION['login_error'] = 'Credenciais inválidas.';
    }

    // Redireciona de volta para o login em caso de erro
    header("Location: index.php");
    exit();
}
?>
