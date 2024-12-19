<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclui a conexão com o banco de dados

// Verifica se o ID foi fornecido
if (!isset($_GET['id'])) {
    header("Location: categorias_detalhes.php");
    exit();
}

$id = $_GET['id'];

// Busca os dados atuais do detalhe
$sql = "SELECT * FROM categoria_detalhes WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: categorias_detalhes.php");
    exit();
}

$categoriaDetalhe = $result->fetch_assoc();

// Atualiza os dados no banco de dados ao submeter o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria_id = $_POST['categoria_id'];
    $campo = $_POST['campo'];
    $tipo = $_POST['tipo'];
    $opcoes = isset($_POST['opcoes']) ? $_POST['opcoes'] : NULL;
    $ordem = $_POST['ordem'];

    $sqlUpdate = "UPDATE categoria_detalhes 
                  SET categoria_id = '$categoria_id', campo = '$campo', tipo = '$tipo', opcoes = '$opcoes', ordem = '$ordem' 
                  WHERE id = $id";

    if ($conn->query($sqlUpdate) === TRUE) {
        header("Location: categorias_detalhe.php"); // Redireciona para a listagem
        exit();
    } else {
        echo "Erro ao atualizar detalhe: " . $conn->error;
    }
}

// Busca todas as categorias para exibir no select
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
    <title>Editar Detalhe de Categoria</title>
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
                    <h1 class="h3 mb-4 text-gray-800">Editar Detalhe de Categoria</h1>

                    <!-- Formulário -->
                    <form action="edit_categorias_detalhe.php?id=<?php echo $id; ?>" method="POST">
                        <div class="form-group">
                            <label for="categoria_id">Categoria</label>
                            <select name="categoria_id" class="form-control" required>
                                <option value="">Selecione uma Categoria</option>
                                <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                                    <option value="<?php echo $categoria['id']; ?>" 
                                        <?php echo ($categoria['id'] == $categoriaDetalhe['categoria_id']) ? 'selected' : ''; ?>>
                                        <?php echo $categoria['categoria_nome']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="campo">Nome do Campo</label>
                            <input type="text" name="campo" class="form-control" value="<?php echo $categoriaDetalhe['campo']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo do Campo</label>
                            <select name="tipo" class="form-control" required>
                                <option value="text" <?php echo ($categoriaDetalhe['tipo'] == 'text') ? 'selected' : ''; ?>>Texto</option>
                                <option value="boolean" <?php echo ($categoriaDetalhe['tipo'] == 'boolean') ? 'selected' : ''; ?>>Sim/Não</option>
                                <option value="select" <?php echo ($categoriaDetalhe['tipo'] == 'select') ? 'selected' : ''; ?>>Seleção Única</option>
                                <option value="checkbox" <?php echo ($categoriaDetalhe['tipo'] == 'checkbox') ? 'selected' : ''; ?>>Seleção Múltipla</option>
                                <option value="decimal" <?php echo ($categoriaDetalhe['tipo'] == 'decimal') ? 'selected' : ''; ?>>Decimal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="opcoes">Opções (se aplicável)</label>
                            <input type="text" name="opcoes" class="form-control" value="<?php echo $categoriaDetalhe['opcoes']; ?>" placeholder="Ex.: Opção1,Opção2,Opção3">
                        </div>

                        <div class="form-group">
                            <label for="ordem">Ordem de Exibição</label>
                            <input type="number" name="ordem" class="form-control" value="<?php echo $categoriaDetalhe['ordem']; ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="categorias_detalhes.php" class="btn btn-secondary">Cancelar</a>
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
