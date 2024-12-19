<?php
session_start();
include('admin/db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

// Debug da sessão para verificação (opcional, remova em produção)


// Recupera os valores de loja e bandeira da sessão
$loja_id = $_SESSION['loja_id'] ?? null;
$bandeira = $_SESSION['bandeira'] ?? null;

// Inicializa o array `checklist` na sessão, se ainda não existir
if (!isset($_SESSION['checklist'])) {
    $_SESSION['checklist'] = [];
}

// Busca todas as categorias no banco de dados
$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);

// Processa o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['categoria_id'])) {
    $categoria_id = (int) $_POST['categoria_id'];
    $loja_id = (int) $_POST['loja_id'];
    $bandeira = $_POST['bandeira'];

    // Atualiza os valores na estrutura de sessão
    if (!isset($_SESSION['checklist'][$categoria_id])) {
        $_SESSION['checklist'][$categoria_id] = []; // Inicializa a categoria, se necessário
    }

    // Salva loja_id e bandeira globalmente e na categoria
    $_SESSION['loja_id'] = $loja_id;
    $_SESSION['bandeira'] = $bandeira;
    $_SESSION['categoria_id'] = $categoria_id;

    // Redireciona para o formulário de SKUs
    header("Location: formulario_sku.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Categorias</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body id="page-top">

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

    <!-- Hero Image -->
    <div class="text-center hero-container">
        <img src="images/checklist.png" alt="Hero Image" class="hero-image">
    </div>

    <div class="container my-5">
        <div class="mb-5">
            <h1 class="text-dark font-weight-bold">Seleção de Categorias</h1>
            <p class="">Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>! Escolha uma categoria para iniciar o checklist</p>
        </div>

        <form action="formulario_categorias.php" method="POST" class="mb-4">
            <div class="row mb-5">
                <!-- Seleção de Loja -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="loja_id">Selecione a Loja:</label>
                    <select id="loja_id" name="loja_id" class="form-control" required>
                        <option value="">-- Escolha uma loja --</option>
                        <?php
                        $sql_lojas = "SELECT * FROM lojas ORDER BY nome_loja";
                        $result_lojas = $conn->query($sql_lojas);
                        while ($loja = $result_lojas->fetch_assoc()) {
                            $selected = ($loja['id'] == $loja_id) ? 'selected' : '';
                            echo "<option class='teste' value='{$loja['id']}' $selected>" . htmlspecialchars($loja['nome_loja']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Campo para Bandeira -->
                <div class="form-group col-md-6 col-sm-12">
                    <label for="bandeira">Digite a Bandeira:</label>
                    <input type="text" id="bandeira" name="bandeira" class="form-control" value="<?php echo htmlspecialchars($bandeira); ?>" placeholder="Digite a bandeira" required>
                </div>
            </div>

            <!-- Categorias -->
            <div class="row">
                <?php
                if ($result_categorias->num_rows > 0) {
                    while ($categoria = $result_categorias->fetch_assoc()) {
                        echo '<div class="col-md-6 col-lg-3 mb-3">';
                        echo '<div class="border border-info rounded-lg h-100 bg-info shadow">';
                        echo '<div class="card-body py-5 d-flex flex-column justify-content-center">';
                        echo '<img class="w-50 ml-auto mr-auto" src="images/appliance.png" width="28">';
                        echo '<h5 class="card-title text-center font-weight-bold text-white mt-4">' . htmlspecialchars($categoria['categoria_nome']) . '</h5>';
                        echo '<button type="submit" name="categoria_id" value="' . $categoria['id'] . '" class="btn-select btn bg-white text-dark btn-block rounded-lg mt-2 shadow">Selecionar Categoria</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-center">Nenhuma categoria disponível.</p>';
                }
                ?>
            </div>
        </form>

        <!-- Botão de Finalizar -->
        <form class="mt-5" action="processa_formulario.php" method="POST">
            <button type="submit" class="btn-submit btn btn-info font-weight-bold px-5 py-3">Finalizar Checklist</button>
        </form>
    </div>

    <!-- Rodapé -->
    <div class="text-center bg-dark text-white py-5">
        <p>© Hisense 2025 - Hisense Gorenje do Brasil Importação e Comércio de Eletrodoméstico LTDA.</p>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>