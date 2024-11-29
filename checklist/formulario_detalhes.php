<?php
session_start();
include('admin/db.php');

// Ativa exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configura a conexão para usar UTF-8
$conn->set_charset("utf8");

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

// Verifica se a categoria, SKU, loja e bandeira foram selecionados
if (!isset($_SESSION['categoria_id']) || !isset($_SESSION['sku_id']) || !isset($_SESSION['loja_id']) || !isset($_SESSION['bandeira'])) {
    header("Location: formulario_categorias.php");
    exit();
}

// Debug da sessão (opcional para verificar organização dos dados)


$categoria_id = $_SESSION['categoria_id'];
$sku_id = $_SESSION['sku_id'];
$loja_id = $_SESSION['loja_id'];
$bandeira = $_SESSION['bandeira'];

// Testa conexão com o banco de dados
if (!$conn) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Busca o nome da categoria
$sql_categoria = "SELECT categoria_nome FROM categorias WHERE id = ?";
$stmt_categoria = $conn->prepare($sql_categoria);
$stmt_categoria->bind_param("i", $categoria_id);
$stmt_categoria->execute();
$result_categoria = $stmt_categoria->get_result();
$categoria_nome = $result_categoria->fetch_assoc()['categoria_nome'] ?? 'Categoria Desconhecida';

// Busca o nome do produto (SKU)
$sql_sku = "SELECT nome_produto, sku FROM produtos WHERE id = ?";
$stmt_sku = $conn->prepare($sql_sku);
$stmt_sku->bind_param("i", $sku_id);
$stmt_sku->execute();
$result_sku = $stmt_sku->get_result();
$produto = $result_sku->fetch_assoc();
$nome_produto = $produto['nome_produto'] ?? 'Produto Desconhecido';
$sku = $produto['sku'] ?? 'SKU Desconhecido';

// Busca os campos dinâmicos da categoria
$sql_detalhes = "SELECT * FROM categoria_detalhes WHERE categoria_id = ? ORDER BY ordem";
$stmt_detalhes = $conn->prepare($sql_detalhes);
$stmt_detalhes->bind_param("i", $categoria_id);
$stmt_detalhes->execute();
$result_detalhes = $stmt_detalhes->get_result();

// Inicializa `$_SESSION['checklist']` com estrutura organizada
if (!isset($_SESSION['checklist'])) {
    $_SESSION['checklist'] = [];
}

// Inicializa a categoria atual, se não existir
if (!isset($_SESSION['checklist'][$categoria_id])) {
    $_SESSION['checklist'][$categoria_id] = [];
}

// Inicializa o SKU atual dentro da categoria, se não existir
if (!isset($_SESSION['checklist'][$categoria_id][$sku_id])) {
    $_SESSION['checklist'][$categoria_id][$sku_id] = [
        'respostas' => []
    ];
}

// Processa o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'campo_') === 0) {
            $_SESSION['checklist'][$categoria_id][$sku_id]['respostas'][$key] = $value;
        }
    }

    // Processa uploads de arquivos
    foreach ($_FILES as $key => $file) {
        if (strpos($key, 'campo_') === 0 && $file['error'] == UPLOAD_ERR_OK) {
            $diretorio_upload = 'uploads/';
            if (!is_dir($diretorio_upload)) {
                mkdir($diretorio_upload, 0777, true);
            }
            $nome_arquivo = uniqid() . '_' . basename($file['name']);
            $caminho_arquivo = $diretorio_upload . $nome_arquivo;

            if (move_uploaded_file($file['tmp_name'], $caminho_arquivo)) {
                $_SESSION['checklist'][$categoria_id][$sku_id]['respostas'][$key] = $caminho_arquivo;
            }
        }
    }

    echo "<script>
        alert('Detalhes salvos com sucesso!');
        window.location.href = 'formulario_categorias.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do SKU</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div class="container mt-5">
        <h1 class="text-center">Detalhes do SKU</h1>
        <p class="text-center">Preencha os detalhes para o SKU selecionado.</p>

        <div class="alert alert-info">
            <strong>Categoria:</strong> <?php echo $categoria_nome; ?><br>
            <strong>Produto:</strong> <?php echo $nome_produto; ?><br>
            <strong>SKU:</strong> <?php echo $sku; ?>
        </div>

        <form action="formulario_detalhes.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <?php
                if ($result_detalhes->num_rows > 0) {
                    $result_detalhes->data_seek(0);
                    while ($detalhe = $result_detalhes->fetch_assoc()) {
                        $campo_id = 'campo_' . $detalhe['id'];
                        $valor = $_SESSION['checklist'][$categoria_id][$sku_id]['respostas'][$campo_id] ?? '';
                        echo '<div class="col-md-12 mb-3">';
                        echo '<label for="' . $campo_id . '">' . htmlspecialchars($detalhe['campo'], ENT_QUOTES, 'UTF-8') . '</label>';

                        if ($detalhe['tipo'] == 'text') {
                            echo '<input type="text" class="form-control" id="' . $campo_id . '" name="' . $campo_id . '" value="' . htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') . '" required>';
                        } elseif ($detalhe['tipo'] == 'decimal') {
                            $valor_decimal = is_numeric($valor) ? number_format($valor, 2, '.', '') : '';
                            echo '<input type="number" step="0.01" class="form-control" id="' . $campo_id . '" name="' . $campo_id . '" value="' . htmlspecialchars($valor_decimal, ENT_QUOTES, 'UTF-8') . '" required>';
                        } elseif ($detalhe['tipo'] == 'select') {
                            echo '<select class="form-control" id="' . $campo_id . '" name="' . $campo_id . '" required>';
                            $opcoes = explode(',', $detalhe['opcoes']);
                            foreach ($opcoes as $opcao) {
                                $selected = ($valor == trim($opcao)) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars(trim($opcao), ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars(trim($opcao), ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                            echo '</select>';
                        } elseif ($detalhe['tipo'] == 'checkbox') {
                            $opcoes = explode(',', $detalhe['opcoes']);
                            foreach ($opcoes as $opcao) {
                                $checked = (is_array($valor) && in_array(trim($opcao), $valor)) ? 'checked' : '';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" id="' . $campo_id . '_' . htmlspecialchars(trim($opcao), ENT_QUOTES, 'UTF-8') . '" name="' . $campo_id . '[]" value="' . htmlspecialchars(trim($opcao), ENT_QUOTES, 'UTF-8') . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $campo_id . '_' . htmlspecialchars(trim($opcao), ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(trim($opcao), ENT_QUOTES, 'UTF-8') . '</label>';
                                echo '</div>';
                            }
                        } elseif ($detalhe['tipo'] == 'boolean') {
                            echo '<div class="form-check">';
                            echo '<input class="form-check-input" type="radio" id="' . $campo_id . '_sim" name="' . $campo_id . '" value="1" ' . ($valor == '1' ? 'checked' : '') . ' required>';
                            echo '<label class="form-check-label" for="' . $campo_id . '_sim">Sim</label>';
                            echo '</div>';
                            echo '<div class="form-check">';
                            echo '<input class="form-check-input" type="radio" id="' . $campo_id . '_nao" name="' . $campo_id . '" value="0" ' . ($valor == '0' ? 'checked' : '') . '>';
                            echo '<label class="form-check-label" for="' . $campo_id . '_nao">Não</label>';
                            echo '</div>';
                        } elseif ($detalhe['tipo'] == 'file') {
                            echo '<input type="file" class="form-control" id="' . $campo_id . '" name="' . $campo_id . '" accept="image/*">';
                            if (!empty($valor)) {
                                echo '<p>Arquivo enviado: <a href="' . htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') . '" target="_blank">Ver arquivo</a></p>';
                            }
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-center">Nenhum detalhe configurado para esta categoria.</p>';
                }
                ?>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Salvar Detalhes</button>
            </div>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
