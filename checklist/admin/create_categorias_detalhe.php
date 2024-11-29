<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclui a conexão com o banco de dados

// Lógica para inserir o detalhe na tabela categoria_detalhes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria_id = $_POST['categoria_id'];
    $campo = $_POST['campo'];
    $tipo = $_POST['tipo'];
    $opcoes = isset($_POST['opcoes']) ? $_POST['opcoes'] : NULL; // Opções são opcionais
    $ordem = $_POST['ordem'];

    $sql = "INSERT INTO categoria_detalhes (categoria_id, campo, tipo, opcoes, ordem) 
            VALUES ('$categoria_id', '$campo', '$tipo', '$opcoes', '$ordem')";

    if ($conn->query($sql) === TRUE) {
        header("Location: categorias_detalhe.php"); // Redireciona para a listagem
        exit();
    } else {
        echo "Erro ao cadastrar detalhe: " . $conn->error;
    }
}

// Busca as categorias para exibição no select
$sqlCategorias = "SELECT id, categoria_nome FROM categorias ORDER BY categoria_nome";
$resultCategorias = $conn->query($sqlCategorias);

if (!$resultCategorias) {
    die("Erro ao buscar categorias: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Detalhe de Categoria</title>
    <!-- Custom fonts and styles -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
                    <h1 class="h3 mb-4 text-gray-800">Cadastrar Novo Detalhe</h1>

                    <!-- Formulário -->
                    <form action="create_categorias_detalhe.php" method="POST">
                        <div class="form-group">
                            <label for="categoria_id">Categoria</label>
                            <select name="categoria_id" class="form-control" required>
                                <option value="">Selecione uma Categoria</option>
                                <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                                    <option value="<?php echo $categoria['id']; ?>">
                                        <?php echo $categoria['categoria_nome']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="campo">Nome do Campo</label>
                            <input type="text" name="campo" class="form-control" placeholder="Ex.: exibicao_tipo" required>
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo do Campo</label>
                            <select name="tipo" class="form-control" required>
                                <option value="text">Texto</option>
                                <option value="boolean">Sim/Não</option>
                                <option value="select">Seleção Única</option>
                                <option value="checkbox">Seleção Múltipla</option>
                                <option value="decimal">Decimal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="opcoes">Opções (se aplicável)</label>
                            <input type="text" name="opcoes" class="form-control" placeholder="Ex.: Opção1,Opção2,Opção3">
                        </div>

                        <div class="form-group">
                            <label for="ordem">Ordem de Exibição</label>
                            <input type="number" name="ordem" class="form-control" placeholder="Ex.: 1" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="categoria_detalhe.php" class="btn btn-secondary">Cancelar</a>
                    </form>

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

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
