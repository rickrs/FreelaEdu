<?php
session_start();
include('admin/db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

// Debug da sessão para verificação (remova em produção)


// Verifica se a categoria, loja e bandeira foram selecionadas
if (!isset($_SESSION['loja_id']) || !isset($_SESSION['bandeira'])) {
    header("Location: formulario_categorias.php");
    exit();
}

$categoria_id = $_SESSION['categoria_id'];
$loja_id = $_SESSION['loja_id'];
$bandeira = $_SESSION['bandeira'];

// Inicializa o array `checklist` na sessão, se ainda não existir
if (!isset($_SESSION['checklist'])) {
    $_SESSION['checklist'] = [];
}

// Verifica se a categoria já está configurada no `checklist`
if (!isset($_SESSION['checklist'][$categoria_id])) {
    $_SESSION['checklist'][$categoria_id] = [];
}

// Busca os SKUs disponíveis para a categoria selecionada
$sql_skus = "SELECT * FROM produtos WHERE categoria_id = ?";
$stmt = $conn->prepare($sql_skus);
$stmt->bind_param("i", $categoria_id);
$stmt->execute();
$result_skus = $stmt->get_result();

// Processa o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sku_id'])) {
    $sku_id = (int)$_POST['sku_id'];

    // Inicializa o SKU dentro da categoria no `checklist`, se ainda não existir
    if (!isset($_SESSION['checklist'][$categoria_id][$sku_id])) {
        $_SESSION['checklist'][$categoria_id][$sku_id] = [
            'respostas' => [] // Inicializa vazio para respostas do SKU

        ];
    }

    // Atualiza o SKU selecionado na sessão
    $_SESSION['sku_id'] = $sku_id;

    // Redireciona para o formulário de detalhes
    header("Location: formulario_detalhes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de SKU</title>
    <!-- Custom fonts and styles -->
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

    <!-- Breadcrumb -->
    <nav class="bg-light pt-3 pb-1" aria-label="breadcrumb">
        <div class="container px-2">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="formulario_categorias.php">Categorias</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Seleção de SKU</li>
            </ol>
        </div>
    </nav>

    <!-- Container -->
    <div class="container py-5">

        <div class="mb-5">
            <h1 class="text-dark font-weight-bold">Seleção de SKU</h1>
            <p class="">Escolha um SKU para a categoria selecionada</p>
        </div>

        <div class="row">
            <?php
            if ($result_skus->num_rows > 0) {
                while ($sku = $result_skus->fetch_assoc()) {
                    echo '<div class="col-md-6 col-lg-3 mb-3">';
                    echo '<div class="border border-info rounded-lg h-100 bg-info shadow">';
                    echo '<div class="card-body py-5 d-flex flex-column justify-content-center">';
                    echo '<img class="w-50 ml-auto mr-auto" src="images/appliance.png" width="28">';
                    echo '<h5 class="card-title text-center font-weight-bold text-white mt-4">' . $sku['nome_produto'] . '</h5>';
                    echo '<p class="card-text text-center text-white text-monospace"><strong>SKU:</strong> ' . $sku['sku'] . '</p>';
                    echo '<form action="formulario_sku.php" method="POST">';
                    echo '<input type="hidden" name="sku_id" value="' . $sku['id'] . '">';
                    echo '<button type="submit" class="btn-select btn bg-white text-dark btn-block rounded-lg mt-2 shadow">Selecionar SKU</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">Nenhum SKU disponível para esta categoria.</p>';
            }
            ?>
        </div>

        <!-- Botão para retornar à seleção de categorias -->
        <div class="mt-5">
            <a href="formulario_categorias.php" class="btn-submit btn btn-info font-weight-bold px-5 py-3">Voltar para Categorias</a>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="text-center bg-dark text-white py-5">
        <p>© Hisense 2025 - Hisense Gorenje do Brasil Importação e Comércio de Eletrodoméstico LTDA.</p>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>