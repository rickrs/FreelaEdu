<?php
session_start();
include('admin/db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

$feedback_message = "";
$success = false;

// Processa os dados da sessão e salva no banco de dados
if (isset($_SESSION['checklist']) && !empty($_SESSION['checklist'])) {
    $user_login_id = $_SESSION['user_login_id'];
    $loja_id = $_SESSION['loja_id'];
    $bandeira = $_SESSION['bandeira'];

    $conn->begin_transaction();
    try {
        // Insere os dados no checklist_form
        $sql_insert_checklist_form = "INSERT INTO checklist_form (user_login_id, loja_id, bandeira) VALUES (?, ?, ?)";
        $stmt_form = $conn->prepare($sql_insert_checklist_form);
        $stmt_form->bind_param("iis", $user_login_id, $loja_id, $bandeira);
        $stmt_form->execute();
        $checklist_id = $stmt_form->insert_id;

        // Itera pelas categorias e SKUs no checklist
        foreach ($_SESSION['checklist'] as $categoria_id => $skus) {
            foreach ($skus as $sku_id => $dados) {
                foreach ($dados['respostas'] as $campo => $valor) {
                    if (is_array($valor)) {
                        $valor = json_encode($valor); // Converte arrays em JSON para salvar no banco
                    }
                    $sql_insert_detalhes = "INSERT INTO checklist_detalhes (checklist_id, sku_id, campo, valor) VALUES (?, ?, ?, ?)";
                    $stmt_detalhes = $conn->prepare($sql_insert_detalhes);
                    $stmt_detalhes->bind_param("iiss", $checklist_id, $sku_id, $campo, $valor);
                    $stmt_detalhes->execute();
                }
            }
        }

        // Finaliza a transação
        $conn->commit();
        $feedback_message = "Checklist salvo com sucesso!";
        $success = true;
    } catch (Exception $e) {
        // Em caso de erro, reverte a transação
        $conn->rollback();
        $feedback_message = "Erro ao salvar o checklist: " . $e->getMessage();
    }
} else {
    $feedback_message = "Erro: Nenhum dado de checklist encontrado na sessão.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processando Checklist</title>
    <!-- Custom styles for SBAdmin2 -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['nome_usuario']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Page Content -->
                <div class="container-fluid text-center">
                    <?php if ($success): ?>
                        <div class="alert alert-success mt-5">
                            <h3><?php echo $feedback_message; ?></h3>
                            <p>Você será redirecionado em 5 segundos...</p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger mt-5">
                            <h3><?php echo $feedback_message; ?></h3>
                        </div>
                    <?php endif; ?>
                </div>

                <script>
                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 5000);
                </script>

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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
