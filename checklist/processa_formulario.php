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
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>

<style>
    html,
    body {
        min-height: 100vh;
        margin: 0;
        position: relative;
    }

    .footer {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper bg-white">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Navbar -->
                <nav class="navbar navbar-light bg-light">
                    <div class="container align-items-baseline">
                        <div class="navbar-brand py-4">
                            <img src="images/hisense.svg" alt="">
                        </div>
                        <div class="d-flex align-items-baseline">
                            <h5 class="h6 mr-2 font-weight-bold text-dark"><?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></h5>
                            <div class="user-logo bg-info">
                                <img src="vendor/fontawesome-free/svgs/regular/user.svg" width="28" alt="">
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container text-center">
                    <?php if ($success): ?>
                        <div class="alert bg-info text-white mt-5 py-4">
                            <h3><?php echo $feedback_message; ?></h3>
                            <p>Você será redirecionado em 5 segundos...</p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger mt-5 py-4">
                            <h3><?php echo $feedback_message; ?></h3>
                            <p>Você será redirecionado em 5 segundos...</p>
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

            <!-- Rodapé -->
            <div class="text-center bg-dark text-white py-5 footer">
                <p>© Hisense 2025 - Hisense Gorenje do Brasil Importação e Comércio de Eletrodoméstico LTDA.</p>
            </div>

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