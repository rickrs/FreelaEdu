<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclua o arquivo de conexão com o banco de dados

$id = $_GET['id']; // ID do usuário a ser editado

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST['nome_usuario'];
    $user_name = $_POST['user_name'];
    $user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT); // Hash da nova senha
    $tipo = $_POST['tipo']; // Tipo de usuário: Admin ou Usuário

    // Atualiza os dados do usuário na tabela user_login
    $sql = "UPDATE user_login SET nome_usuario='$nome_usuario', user_name='$user_name', user_password='$user_password', tipo='$tipo' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: usuarios.php"); // Redireciona após salvar
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Seleciona os dados do usuário com o ID fornecido
    $sql = "SELECT * FROM user_login WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome_usuario = $row['nome_usuario'];
        $user_name = $row['user_name'];
        $tipo = $row['tipo']; // Carrega o tipo de usuário para o formulário
    } else {
        echo "Usuário não encontrado";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <!-- Custom styles for this template-->
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
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['nome_usuario']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
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
                    <h1 class="h3 mb-4 text-gray-800">Editar Usuário</h1>

                    <form action="edit_usuario.php?id=<?php echo $id; ?>" method="POST">
                        <div class="form-group">
                            <label for="nome_usuario">Nome Completo</label>
                            <input type="text" name="nome_usuario" class="form-control" value="<?php echo $nome_usuario; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="user_name">Email</label>
                            <input type="email" name="user_name" class="form-control" value="<?php echo $user_name; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="user_password">Nova Senha</label>
                            <input type="password" name="user_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo de Usuário</label>
                            <select name="tipo" class="form-control" required>
                                <option value="user" <?php echo $tipo == 'user' ? 'selected' : ''; ?>>Usuário</option>
                                <option value="admin" <?php echo $tipo == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
