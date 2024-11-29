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
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div class="container mt-5">
        <h1 class="text-center">Seleção de Categorias</h1>
        <p class="text-center">Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>! Escolha uma categoria para iniciar o checklist.</p>

        <form action="formulario_categorias.php" method="POST" class="mb-4">
            <!-- Seleção de Loja -->
            <div class="form-group">
                <label for="loja_id">Selecione a Loja:</label>
                <select id="loja_id" name="loja_id" class="form-control" required>
                    <option value="">-- Escolha uma loja --</option>
                    <?php
                    $sql_lojas = "SELECT * FROM lojas ORDER BY nome_loja";
                    $result_lojas = $conn->query($sql_lojas);
                    while ($loja = $result_lojas->fetch_assoc()) {
                        $selected = ($loja['id'] == $loja_id) ? 'selected' : '';
                        echo "<option value='{$loja['id']}' $selected>" . htmlspecialchars($loja['nome_loja']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Campo para Bandeira -->
            <div class="form-group">
                <label for="bandeira">Digite a Bandeira:</label>
                <input type="text" id="bandeira" name="bandeira" class="form-control" value="<?php echo htmlspecialchars($bandeira); ?>" placeholder="Digite a bandeira" required>
            </div>

            <!-- Categorias -->
            <div class="row">
                <?php
                if ($result_categorias->num_rows > 0) {
                    while ($categoria = $result_categorias->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-3">';
                        echo '<div class="card shadow h-100">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title text-center">' . htmlspecialchars($categoria['categoria_nome']) . '</h5>';
                        echo '<button type="submit" name="categoria_id" value="' . $categoria['id'] . '" class="btn btn-primary btn-block">Selecionar Categoria</button>';
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
        <form action="processa_formulario.php" method="POST">
            <button type="submit" class="btn btn-success btn-block mt-3">Finalizar Checklist</button>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
