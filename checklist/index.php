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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .btn-primary {
            background-color: #00B3AC !important;
            border-color: #00B3AC !important;
        }

        .login-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: white;
        }

        .bg-login-image {
            background: url('https://hisense.com.br/wp-content/uploads/2024/03/Vector2.svg') no-repeat center center;
            background-size: contain;
            width: 90%;
            height: 90%;
            margin-left: 20%;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="bg-login-image"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bem-vindo de volta!</h1>
                                    </div>
                                    <!-- Exibe a mensagem de erro se houver -->
                                    <?php if (!empty($error_message)) : ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error_message; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form class="user" action="process_login.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="user_name" class="form-control form-control-user" id="exampleInputUsername" placeholder="Digite seu nome de usuário..." required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="user_password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Digite sua senha..." required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
