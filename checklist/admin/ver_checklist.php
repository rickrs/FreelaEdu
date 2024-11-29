<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclui o arquivo de conexão com o banco de dados

// Obtém o ID do checklist para visualizar
$id = $_GET['id'];

// Busca os detalhes do checklist
$sql = "SELECT cf.*, ul.nome_usuario, l.nome_loja, cf.issues_report, cf.suggestions_report, cf.opportunity_improve, cf.report_pending_issue, cf.suggestions_advices, cf.opportunities_improve 
        FROM checklist_form cf 
        INNER JOIN user_login ul ON cf.user_login_id = ul.id 
        INNER JOIN lojas l ON cf.nome_loja = l.id 
        WHERE cf.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$checklist = $result->fetch_assoc();

// Busca as imagens relacionadas ao checklist
$sql_images = "SELECT * FROM checklist_imagens WHERE checklist_id = ?";
$stmt_images = $conn->prepare($sql_images);
if ($stmt_images === false) {
    // Exibe o erro relacionado à preparação da consulta
    die('Erro na consulta SQL: ' . $conn->error);
}
$stmt_images->bind_param("i", $id);
$stmt_images->execute();
$result_images = $stmt_images->get_result();
$images = $result_images->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$stmt_images->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Checklist</title>
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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Visualizar Checklist</h1>

                    <!-- Dados do checklist em cards -->
                    <div class="row">
                        <!-- Card Dados Básicos -->
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary pdf-download">Dados do Checklist</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>ID:</strong> <?php echo $checklist['id']; ?></p>
                                    <p><strong>Nome do Usuário:</strong> <?php echo $checklist['nome_usuario']; ?></p>
                                    <p><strong>Nome da Loja:</strong> <?php echo $checklist['nome_loja']; ?></p>
                                    <p><strong>Data de Envio:</strong> <?php echo $checklist['created_at']; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Card HA Area -->
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary pdf-download">HA Area</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>WD em zona de hotspot:</strong> <?php echo $checklist['wd_hotspot'] ? 'Sim' : 'Não'; ?></p>
                                    <p><strong>WD ligado na demo:</strong> <?php echo $checklist['wd_demo'] ? 'Sim' : 'Não'; ?></p>
                                    <p><strong>Produto ligado na loja:</strong> <?php echo $checklist['product_on'] ? 'Sim' : 'Não'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Todos os Produtos -->
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary pdf-download">Todos os Produtos</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Material POP presente:</strong> <?php echo $checklist['pop_material'] ? 'Sim' : 'Não'; ?></p>
                                    <p><strong>Modelos premium:</strong> <?php echo $checklist['premium_models'] ? 'Sim' : 'Não'; ?></p>
                                    <p><strong>Preços visíveis:</strong> <?php echo $checklist['prices_visible'] ? 'Sim' : 'Não'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Imagem da Marca -->
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary pdf-download">Imagem da Marca</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Inventário completo:</strong> <?php echo $checklist['inventory'] ? 'Sim' : 'Não'; ?></p>
                                    <p><strong>Exibição da Hisense:</strong> <?php echo $checklist['hisense_display'] ? 'Sim' : 'Não'; ?></p>
                                    <p><strong>Espaço vazio:</strong> <?php echo $checklist['empty_space'] ? 'Sim' : 'Não'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Promotores e FSM -->
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary pdf-download">Promotores e FSM</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Pedidos pendentes:</strong> <?php echo $checklist['pending_request'] ? 'Sim' : 'Não'; ?></p>
                                    <p><strong>FSM ciente:</strong> <?php echo $checklist['fsm_aware'] ? 'Sim' : 'Não'; ?></p>
                                </div>
                            </div>
                        </div>
                <!-- Issues to Report -->
<div class="col-md-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary pdf-download">Problemas a Reportar</h6>
        </div>
        <div class="card-body">
            <p><strong>Problemas a Reportar:</strong> <?php echo nl2br($checklist['issues_report']); ?></p>
        </div>
    </div>
</div>

<!-- Suggestions -->
<div class="col-md-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary pdf-download">Sugestões</h6>
        </div>
        <div class="card-body">
            <p><strong>Sugestões:</strong> <?php echo nl2br($checklist['suggestions_report']); ?></p>
        </div>
    </div>
</div>

<!-- Opportunities to Improve -->
<div class="col-md-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary pdf-download">Oportunidades de Melhoria</h6>
        </div>
        <div class="card-body">
            <p><strong>Oportunidades de Melhoria:</strong> <?php echo nl2br($checklist['opportunity_improve']); ?></p>
        </div>
    </div>
</div>

                        <!-- Card Pontos de Avaliação -->
                        <!-- Novos Pontos de Avaliação -->
<div class="col-md-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary pdf-download">Pontos de Avaliação</h6>
        </div>
        <div class="card-body">
            <p><strong>Clareza das Fotos Compartilhadas:</strong> <?php echo $checklist['clarity']; ?></p>
            <p><strong>Checklist Enviado:</strong> <?php echo $checklist['check_list_sent']; ?></p>
            <p><strong>Relatório de Problemas Pendentes na Loja:</strong> <?php echo $checklist['report_pending_issue']; ?></p>
            <p><strong>Sugestões e Conselhos:</strong> <?php echo $checklist['suggestions_advices']; ?></p>
            <p><strong>Oportunidades de Melhoria:</strong> <?php echo $checklist['opportunities_improve']; ?></p>
        </div>
    </div>
</div>


                        <!-- Card Galeria de Imagens -->
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary pdf-download">Galeria de Imagens</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php if (count($images) > 0): ?>
                                            <?php foreach ($images as $image): ?>
                                                <div class="col-md-3">
                                                    <a href="uploads/<?php echo $image['imagem_nome']; ?>" target="_blank">
                                                        <img src="uploads/<?php echo $image['imagem_nome']; ?>" class="img-thumbnail" alt="Imagem do checklist">
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>Nenhuma imagem foi enviada para este checklist.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botão Voltar -->
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-body text-center">
                                    <a href="visualizar_checklist.php" class="btn btn-primary mb-4">Voltar</a>
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
