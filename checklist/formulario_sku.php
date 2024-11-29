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
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Container -->
    <div class="container mt-5">
        <h1 class="text-center">Seleção de SKU</h1>
        <p class="text-center">Escolha um SKU para a categoria selecionada.</p>

        <div class="row">
            <?php
            if ($result_skus->num_rows > 0) {
                while ($sku = $result_skus->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card shadow h-100">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title text-center">' . $sku['nome_produto'] . '</h5>';
                    echo '<p class="card-text text-center"><strong>SKU:</strong> ' . $sku['sku'] . '</p>';
                    echo '<form action="formulario_sku.php" method="POST">';
                    echo '<input type="hidden" name="sku_id" value="' . $sku['id'] . '">';
                    echo '<button type="submit" class="btn btn-primary btn-block">Selecionar SKU</button>';
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
        <div class="text-center mt-4">
            <a href="formulario_categorias.php" class="btn btn-secondary">Voltar para Categorias</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
