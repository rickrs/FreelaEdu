<?php
session_start();

include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $user_password = $_POST['user_password'];

    $sql = "SELECT * FROM user_login WHERE user_name = '$user_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($user_password, $row['user_password'])) {
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['nome_usuario'] = $row['nome_usuario'];
            $_SESSION['tipo_usuario'] = $row['tipo'];
            $_SESSION['user_login_id'] = $row['id'];
            header("Location: home.php");
            exit();
        } else {
            echo "Senha inválida";
        }
    } else {
        echo "Usuário não encontrado";
    }
}
?>
