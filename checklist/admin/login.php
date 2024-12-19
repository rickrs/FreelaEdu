<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        /* Altere a cor do botão "Exportar para CSV" */
        .btn-primary {
            background-color: #00B3AC !important; /* cor verde */
            border-color: #00B3AC !important;
        }
        .btn-primary:hover {
            background-color: #00B3AC; /* cor verde escura */
            border-color: #00B3AC;
        }

        /* Altere a cor dos botões de paginação */
        .pagination .page-item .page-link {
            color: #00B3AC; /* cor verde */
        }
        .pagination .page-item.active .page-link {
            color: white;
            background-color: #00B3AC; /* cor verde */
            border-color: #00B3AC;
        }
        .pagination .page-item.active .page-link:hover {
            background-color: #00B3AC; /* cor verde escura */
            border-color: #1e7e34;
        }
        .btn-custom {
            background-color: #28a745; /* cor verde */
            border-color: #28a745;
            color: white; /* cor do texto */
        }
        .btn-custom:hover {
            background-color: #218838; /* cor verde escura */
            border-color: #1e7e34;
            color: white; /* cor do texto */
        }
        .pdf-download {
            color: #00B3AC !important; /* cor personalizada */
        }

        .pdf-download:hover {
            color: #009a92 !important; /* cor personalizada ao passar o mouse */
        }
        .login-container {
            position: relative;
    width: 100%;
    max-width: 600px; /* ajuste conforme necessário */
    margin: 0 auto; /* centralizar horizontalmente */
    padding: 20px; /* ajuste conforme necessário */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* exemplo de sombra */
    border-radius: 10px; /* exemplo de borda arredondada */
    background-color: white; /* fundo branco para contraste */
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
                                    <form class="user" action="process_login.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="user_name" class="form-control form-control-user" id="exampleInputUsername" aria-describedby="usernameHelp" placeholder="Digite seu nome de usuário...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="user_password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Senha">
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
