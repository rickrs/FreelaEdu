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
$user_login_id = $_SESSION['user_login_id']; // ID do usuário logado

// Consulta para obter contagem de usuários (somente admin)
if ($tipo_usuario == 'admin') {
    $sql_users = "SELECT COUNT(*) as total_usuarios FROM user_login";
    $result_users = $conn->query($sql_users);
    $total_usuarios = $result_users->fetch_assoc()['total_usuarios'];
} else {
    $total_usuarios = 'N/A'; // Usuário comum não vê total de usuários
}

// Consulta para obter contagem de checklists
if ($tipo_usuario == 'admin') {
    $sql_checklists = "SELECT COUNT(*) as total_checklists FROM checklist_form";
} else {
    $sql_checklists = "SELECT COUNT(*) as total_checklists FROM checklist_form WHERE user_login_id = ?";
}

$stmt_checklists = $conn->prepare($sql_checklists);
if ($tipo_usuario != 'admin') {
    $stmt_checklists->bind_param("i", $user_login_id);
}
$stmt_checklists->execute();
$result_checklists = $stmt_checklists->get_result();
$total_checklists = $result_checklists->fetch_assoc()['total_checklists'];

// Consulta para obter contagem de imagens (somente as imagens do usuário comum ou todas para o admin)
if ($tipo_usuario == 'admin') {
    $sql_images = "SELECT COUNT(*) as total_imagens FROM checklist_imagens";
} else {
    $sql_images = "SELECT COUNT(*) as total_imagens FROM checklist_imagens ci
                   INNER JOIN checklist_form cf ON ci.checklist_id = cf.id 
                   WHERE cf.user_login_id = ?";
}

$stmt_images = $conn->prepare($sql_images);
if ($tipo_usuario != 'admin') {
    $stmt_images->bind_param("i", $user_login_id);
}
$stmt_images->execute();
$result_images = $stmt_images->get_result();
$total_imagens = $result_images->fetch_assoc()['total_imagens'];

// Consulta para obter o total de visitas neste mês
$current_month = date('m');
$current_year = date('Y');

if ($tipo_usuario == 'admin') {
    $sql_visitas_mes = "SELECT COUNT(*) as total_visitas_mes FROM checklist_form WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?";
} else {
    $sql_visitas_mes = "SELECT COUNT(*) as total_visitas_mes FROM checklist_form WHERE user_login_id = ? AND MONTH(created_at) = ? AND YEAR(created_at) = ?";
}

$stmt_visitas_mes = $conn->prepare($sql_visitas_mes);
if ($tipo_usuario == 'admin') {
    $stmt_visitas_mes->bind_param("ii", $current_month, $current_year);
} else {
    $stmt_visitas_mes->bind_param("iii", $user_login_id, $current_month, $current_year);
}
$stmt_visitas_mes->execute();
$result_visitas_mes = $stmt_visitas_mes->get_result();
$total_visitas_mes = $result_visitas_mes->fetch_assoc()['total_visitas_mes'];

// Consulta para obter o total de todas as visitas (para todos os meses)
if ($tipo_usuario == 'admin') {
    $sql_visitas_total = "SELECT COUNT(*) as total_visitas FROM checklist_form";
} else {
    $sql_visitas_total = "SELECT COUNT(*) as total_visitas FROM checklist_form WHERE user_login_id = ?";
}

$stmt_visitas_total = $conn->prepare($sql_visitas_total);
if ($tipo_usuario != 'admin') {
    $stmt_visitas_total->bind_param("i", $user_login_id);
}
$stmt_visitas_total->execute();
$result_visitas_total = $stmt_visitas_total->get_result();
$total_visitas = $result_visitas_total->fetch_assoc()['total_visitas'];

$stmt_checklists->close();
$stmt_images->close();
$stmt_visitas_mes->close();
$stmt_visitas_total->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Usuários Card (Apenas para Admin) -->
                        <?php if ($tipo_usuario == 'admin'): ?>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Usuários Registrados</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_usuarios; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Checklists Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Checklists Enviados</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_checklists; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Imagens Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Imagens Carregadas</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_imagens; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-images fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Visitas Mês Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Visitas Este Mês</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_visitas_mes; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Visitas Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Total de Visitas</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_visitas; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
