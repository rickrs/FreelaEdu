<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclui o arquivo de conexão com o banco de dados

// Obtém o tipo de usuário e o ID do usuário logado
$tipo_usuario = $_SESSION['tipo_usuario'];
$user_login_id = $_SESSION['user_login_id'];

// Define a consulta SQL dependendo do tipo de usuário
if ($tipo_usuario == 'admin') {
    // Se for admin, visualiza todos os checklists
    $sql = "SELECT cf.*, ul.nome_usuario, l.nome_loja 
            FROM checklist_form cf 
            INNER JOIN user_login ul ON cf.user_login_id = ul.id
            INNER JOIN lojas l ON cf.nome_loja = l.id"; // Adiciona a junção com a tabela de lojas
} else {
    // Se for um usuário comum, visualiza apenas os checklists dele
    $sql = "SELECT cf.*, ul.nome_usuario, l.nome_loja 
            FROM checklist_form cf 
            INNER JOIN user_login ul ON cf.user_login_id = ul.id 
            INNER JOIN lojas l ON cf.nome_loja = l.id
            WHERE cf.user_login_id = ?";
}


$stmt = $conn->prepare($sql);

// Se for um usuário comum, passamos o ID do usuário logado na consulta
if ($tipo_usuario != 'admin') {
    $stmt->bind_param("i", $user_login_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Checklists</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
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

    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include('menu.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['nome_usuario']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Checklists Enviados</h1>

                    <!-- Conteúdo Principal da Página -->
                    <div class="card shadow mb-4">
                    <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary pdf-download">Lista de Checklists</h6>
                        </div>
                        <div class="card-body">
                            <!-- Exibe os checklists -->
                            <?php if ($result->num_rows > 0) : ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome da Loja</th>
                                            <th>Data de Envio</th>
                                            <th>Enviado por</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['nome_loja']; ?></td>
                                                <td><?php echo $row['created_at']; ?></td>
                                                <td><?php echo $row['nome_usuario']; ?></td>
                                                <td>
                                                    <a href="ver_checklist.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Ver</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>Nenhum checklist encontrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
