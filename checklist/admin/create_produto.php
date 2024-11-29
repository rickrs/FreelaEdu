<?php
session_start();

// Verifica se o usuário está logado e é admin, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclua o arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria_id = $_POST['categoria_id'];
    $nome_produto = $_POST['nome_produto'];
    $enum_ativo = $_POST['enum_ativo'];
    $foto_produto = '';

    // Upload da foto do produto
    if (isset($_FILES['foto_produto']) && $_FILES['foto_produto']['error'] == 0) {
        $upload_dir = 'uploads/';
        $id_name_file = uniqid() . '_' . basename($_FILES['foto_produto']['name']);
        $uploaded_file = $upload_dir . $id_name_file;
        if (move_uploaded_file($_FILES['foto_produto']['tmp_name'], $uploaded_file)) {
            $foto_produto = $id_name_file;
        } else {
            echo "Erro ao fazer upload da imagem.";
            exit();
        }
    }

    $sql = "INSERT INTO produtos (categoria_id, nome_produto, foto_produto, enum_ativo) VALUES ('$categoria_id', '$nome_produto', '$foto_produto', '$enum_ativo')";

    if ($conn->query($sql) === TRUE) {
        header("Location: produtos.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Adicione o estilo da barra de progresso -->
    <style>
        #progress-bar {
            display: none;
            width: 0%;
            height: 20px;
            background-color: green;
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

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['nome_usuario']; ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
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
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
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
                    <h1 class="h3 mb-4 text-gray-800">Adicionar Produto</h1>

                    <form id="produto-form" action="create_produto.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="categoria_id">Categoria</label>
                            <select name="categoria_id" class="form-control" required>
                                <?php
                                $sql = "SELECT * FROM categorias";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['nome_categoria'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nome_produto">Nome do Produto</label>
                            <input type="text" name="nome_produto" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="foto_produto">Foto do Produto</label>
                            <input type="file" name="foto_produto" class="form-control" required>
                            <div id="progress-bar"></div>
                        </div>
                        <div class="form-group">
                            <label for="enum_ativo">Status</label>
                            <select name="enum_ativo" class="form-control" required>
                                <option value="A">Ativo</option>
                                <option value="I">Inativo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
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
    <!-- Script para a barra de progresso -->
    <script>
        document.querySelector('input[type="file"]').addEventListener('change', function() {
            var file = this.files[0];
            var formData = new FormData();
            formData.append('file', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload.php', true);

            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    var percentComplete = (event.loaded / event.total) * 100;
                    document.getElementById('progress-bar').style.width = percentComplete + '%';
                    document.getElementById('progress-bar').style.display = 'block';
                }
            };

            xhr.onload = function() {
                if (this.status == 200) {
                    var response = JSON.parse(this.response);
                    if (response.status == 'success') {
                        var form = document.getElementById('produto-form');
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'foto_produto';
                        input.value = response.file;
                        form.appendChild(input);
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send(formData);
        });
    </script>

</body>

</html>
