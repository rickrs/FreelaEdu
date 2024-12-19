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
    header("Location: produtos.php");
    exit();
}

$id = $_GET['id'];

// Busca os dados atuais do produto
$sql = "SELECT * FROM produtos WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: produtos.php");
    exit();
}

$produto = $result->fetch_assoc();

// Atualiza os dados no banco de dados ao submeter o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria_id = $_POST['categoria_id'];
    $nome_produto = $_POST['nome_produto'];
    $sku = $_POST['sku'];
    $status_produto = $_POST['status_produto'];

    $sqlUpdate = "UPDATE produtos 
                  SET categoria_id = '$categoria_id', nome_produto = '$nome_produto', sku = '$sku', status_produto = '$status_produto' 
                  WHERE id = $id";

    if ($conn->query($sqlUpdate) === TRUE) {
        header("Location: produtos.php"); // Redireciona para a listagem
        exit();
    } else {
        echo "Erro ao atualizar produto: " . $conn->error;
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
    <title>Editar Produto</title>
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
                    <h1 class="h3 mb-4 text-gray-800">Editar Produto</h1>

                    <!-- Formulário -->
                    <form action="edit_produto.php?id=<?php echo $id; ?>" method="POST">
                        <div class="form-group">
                            <label for="categoria_id">Categoria</label>
                            <select name="categoria_id" class="form-control" required>
                                <option value="">Selecione uma Categoria</option>
                                <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                                    <option value="<?php echo $categoria['id']; ?>" 
                                        <?php echo ($categoria['id'] == $produto['categoria_id']) ? 'selected' : ''; ?>>
                                        <?php echo $categoria['categoria_nome']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nome_produto">Nome do Produto</label>
                            <input type="text" name="nome_produto" class="form-control" value="<?php echo $produto['nome_produto']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" class="form-control" value="<?php echo $produto['sku']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="status_produto">Status</label>
                            <select name="status_produto" class="form-control" required>
                                <option value="ativo" <?php echo ($produto['status_produto'] == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                                <option value="inativo" <?php echo ($produto['status_produto'] == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
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
