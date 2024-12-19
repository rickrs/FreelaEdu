<?php
session_start();

$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Remove a mensagem após exibição
}


// Remove todas as variáveis da sessão
session_unset();

// Destrói a sessão
session_destroy();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
</head>

<style>
    @import url('https://fonts.cdnfonts.com/css/hisense-sans-alfabet');

    body {
        max-width: 100vw;
        overflow-x: hidden !important;
        font-family: 'Hisense Sans Alfabet', sans-serif;
        background-color: #21767d !important;
    }

    .login-image {
        object-fit: cover;
        scale: 120%;
    }

    .failed {
        position: absolute;
        bottom: 2%;
        right: 2%;
        background-color: #f74f4f;
        color: white;
        padding: 2rem;
        border-radius: 1rem;
    }
</style>

<body class="d-flex h-auto justify-content-center align-items-center min-vh-100 position-relative">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-9">
                <div class="o-hidden border-0 bg-white shadow-lg">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div>
                                        <img src="images/hisense.svg" alt="" srcset="">
                                    </div>
                                    <div class="mt-5 pt-4 mb-4">
                                        <h1 class="h1 text-gray-900 font-weight-bold">Login</h1>
                                        <p>Bem-vindo(a), faça login para acessar o painel</p>
                                    </div>
                                    <form class="user pr-lg-4" action="process_login.php" method="POST">
                                        <div class="form-group">
                                            <span class="">Usuário</span>
                                            <input type="text" name="user_name" class="form-control bg-light rounded-0 border-0 mt-1 py-2" id="exampleInputUsername" placeholder="Digite seu nome de usuário" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <span class="">Senha</span>
                                            <input type="password" name="user_password" class="form-control bg-light rounded-0 border-0 mt-1" id="exampleInputPassword" placeholder="Digite sua senha" required>
                                        </div>
                                        <button type="submit" class="btn btn-info btn-block font-weight-bold py-2 mt-4 mb-5">
                                            Fazer login
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block bg-dark overflow-hidden">
                                <div>
                                    <img class="w-100 login-image" src="images/Q6-KV-V-AP_fundo.png" alt="" srcset="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exibe a mensagem de erro se houver -->
    <?php if (!empty($error_message)) : ?>
        <div class="failed shadow-lg" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
</body>


<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>

</html>