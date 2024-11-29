<?php
// Inclui o arquivo com os textos e a conexão com o banco de dados
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

// Conexão com o banco de dados
include 'admin/db.php';

// Inclui o arquivo com os textos
include 'languages.php';

// Verifica qual idioma foi selecionado, se nenhum for, define PT-BR como padrão
$selected_language = isset($_GET['lang']) ? $_GET['lang'] : 'pt-br';

// Pega os textos correspondentes ao idioma selecionado
$texts = $languages[$selected_language];

// Busca as lojas do banco de dados
$sql_lojas = "SELECT id, nome_loja FROM lojas";
$result_lojas = $conn->query($sql_lojas);

?>

<!DOCTYPE html>
<html lang="<?php echo $selected_language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['form_title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    background-color: #f4f6f9; /* Ajusta a cor de fundo do body */
}

.wrapper {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 10px;
    box-sizing: border-box;
}

.content-wrapper {
    max-width: 1000px;
    width: 100%;
    margin: 0 auto;
    padding: 15px;
    position: relative;
    background-color: #f4f6f9;
}

.header {
    display: flex;
    align-items: center; /* Centraliza verticalmente o logo e o título */
    justify-content: space-between; /* Espaço entre o logo e o seletor de idioma */
    margin-bottom: 20px;
    width: 100%;
}

.logo-title-container {
    display: flex;
    align-items: center;
}

.logo-container img {
    max-width: 150px;
    height: auto;
}

h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-left: 15px; /* Espaçamento entre o logo e o título */
}

.language-select {
    display: flex;
    align-items: center;
}

.language-select select {
    padding: 5px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.content {
    width: 100%;
}

.btn-primary {
    background-color: #00b3ac;
    border-color: #00b3ac;
}

.btn-primary:hover {
    background-color: #009d97;
    border-color: #009d97;
}

.card-primary .card-header {
    background-color: #00b3ac !important;
    border-color: #00b3ac !important;
}

.custom-file-input ~ .custom-file-label::after {
    background-color: #00b3ac;
    color: white;
    border-left: 1px solid #00b3ac;
}

.custom-file-input ~ .custom-file-label:hover::after {
    background-color: #009d97;
    color: white;
}

@media (max-width: 768px) {
    .header {
        flex-direction: column; /* Em telas menores, o logo e o título ficam em colunas */
        align-items: flex-start;
    }

    h1 {
        margin-left: 0;
        margin-top: 10px;
    }
}


    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <!-- Cabeçalho com logo e seleção de idioma -->
                    <div class="header">
                        <!-- Logo -->
                        <div class="logo-container">
                            <img src="https://hisense.com.br/wp-content/uploads/2024/03/Vector2.svg" alt="Logo Hisense">
                        </div>
                        <!-- Seleção de Idioma -->
                        <div class="language-select">
                            <label for="language"><?php echo $texts['select_language']; ?>:</label>
                            <select id="language" onchange="changeLanguage(this.value)">
                                <option value="pt-br" <?php if ($selected_language == 'pt-br') echo 'selected'; ?>>PT-BR</option>
                                <option value="en-us" <?php if ($selected_language == 'en-us') echo 'selected'; ?>>EN-US</option>
                            </select>
                        </div>
                    </div>

                    <!-- Título -->
                    <h1><?php echo $texts['form_title']; ?></h1>

                    <!-- Formulário -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo $texts['shop_evaluation']; ?></h3>
                                </div>
                                <form action="post_checklist.php" method="POST" name='myform' enctype="multipart/form-data">
                                    <div class="card-body">
                                        <!-- Select para o Nome da Loja -->
                                        <div class="form-group">
                                            <label for="nome_loja"><?php echo $texts['store_name']; ?></label>
                                            <select class="form-control" id="nome_loja" name="nome_loja" required>
                                                <option value=""><?php echo $texts['select_store']; ?></option>
                                                <?php
                                                // Exibe as lojas disponíveis no select
                                                if ($result_lojas->num_rows > 0) {
                                                    while ($row_loja = $result_lojas->fetch_assoc()) {
                                                        echo '<option value="' . $row_loja['id'] . '">' . $row_loja['nome_loja'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">Nenhuma loja cadastrada</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <h4><?php echo $texts['ha_area']; ?></h4>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="wd_hotspot" name="wd_hotspot">
                                                <label for="wd_hotspot" class="custom-control-label"><?php echo $texts['wd_hotspot']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="wd_demo" name="wd_demo">
                                                <label for="wd_demo" class="custom-control-label"><?php echo $texts['wd_demo']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="product_on" name="product_on">
                                                <label for="product_on" class="custom-control-label"><?php echo $texts['product_on']; ?></label>
                                            </div>
                                        </div>

                                        <h4><?php echo $texts['all_products']; ?></h4>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="pop_material" name="pop_material">
                                                <label for="pop_material" class="custom-control-label"><?php echo $texts['pop_material']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="premium_models" name="premium_models">
                                                <label for="premium_models" class="custom-control-label"><?php echo $texts['premium_models']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="prices_visible" name="prices_visible">
                                                <label for="prices_visible" class="custom-control-label"><?php echo $texts['prices_visible']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="flooring" name="flooring">
                                                <label for="flooring" class="custom-control-label"><?php echo $texts['flooring']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="inventory" name="inventory">
                                                <label for="inventory" class="custom-control-label"><?php echo $texts['inventory']; ?></label>
                                            </div>
                                        </div>

                                        <h4><?php echo $texts['brand_image']; ?></h4>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="hisense_display" name="hisense_display">
                                                <label for="hisense_display" class="custom-control-label"><?php echo $texts['hisense_display']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="empty_space" name="empty_space">
                                                <label for="empty_space" class="custom-control-label"><?php echo $texts['empty_space']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="premium_presence" name="premium_presence">
                                                <label for="premium_presence" class="custom-control-label"><?php echo $texts['premium_presence']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="stronger_presence" name="stronger_presence">
                                                <label for="stronger_presence" class="custom-control-label"><?php echo $texts['stronger_presence']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="promoter" name="promoter">
                                                <label for="promoter" class="custom-control-label"><?php echo $texts['promoter']; ?></label>
                                            </div>
                                        </div>

                                        <h4><?php echo $texts['promoters_fsm']; ?></h4>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="pending_request" name="pending_request">
                                                <label for="pending_request" class="custom-control-label"><?php echo $texts['pending_request']; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="fsm_aware" name="fsm_aware">
                                                <label for="fsm_aware" class="custom-control-label"><?php echo $texts['fsm_aware']; ?></label>
                                            </div>
                                        </div>
                                        <!-- Issues to report -->
<div class="form-group">
    <label for="issues_report"><?php echo $texts['issues_report']; ?></label>
    <textarea class="form-control" id="issues_report" name="issues_report" placeholder="<?php echo $texts['issues_report_placeholder']; ?>"></textarea>
</div>

<!-- Suggestions -->
<div class="form-group">
    <label for="suggestions_report"><?php echo $texts['suggestions_report']; ?></label>
    <textarea class="form-control" id="suggestions_report" name="suggestions_report" placeholder="<?php echo $texts['suggestions_report_placeholder']; ?>"></textarea>
</div>

<!-- Opportunity to improve -->
<div class="form-group">
    <label for="opportunity_improve"><?php echo $texts['opportunity_improve']; ?></label>
    <textarea class="form-control" id="opportunity_improve" name="opportunity_improve" placeholder="<?php echo $texts['opportunity_improve_placeholder']; ?>"></textarea>
</div>


                                        <!-- Evaluation Points -->
<h4><?php echo $texts['evaluation_points']; ?></h4>

<div class="form-group">
    <label for="clarity"><?php echo $texts['clarity']; ?></label>
    <input type="range" class="form-control-range" id="clarity" name="clarity" min="0" max="20" value="0" oninput="document.getElementById('clarityValue').innerText = this.value">
    <span id="clarityValue">0</span>
</div>

<div class="form-group">
    <label for="check_list_sent"><?php echo $texts['check_list_sent']; ?></label>
    <input type="range" class="form-control-range" id="check_list_sent" name="check_list_sent" min="0" max="20" value="0" oninput="document.getElementById('checklistValue').innerText = this.value">
    <span id="checklistValue">0</span>
</div>

<div class="form-group">
    <label for="report_pending_issue"><?php echo $texts['report_pending_issue']; ?></label>
    <input type="range" class="form-control-range" id="report_pending_issue" name="report_pending_issue" min="0" max="20" value="0" oninput="document.getElementById('reportPendingValue').innerText = this.value">
    <span id="reportPendingValue">0</span>
</div>

<div class="form-group">
    <label for="suggestions_advices"><?php echo $texts['suggestions_advices']; ?></label>
    <input type="range" class="form-control-range" id="suggestions_advices" name="suggestions_advices" min="0" max="20" value="0" oninput="document.getElementById('suggestionsValue').innerText = this.value">
    <span id="suggestionsValue">0</span>
</div>

<div class="form-group">
    <label for="opportunities_improve"><?php echo $texts['opportunities_improve']; ?></label>
    <input type="range" class="form-control-range" id="opportunities_improve" name="opportunities_improve" min="0" max="20" value="0" oninput="document.getElementById('opportunitiesValue').innerText = this.value">
    <span id="opportunitiesValue">0</span>
</div>


                                        <!-- Upload de imagens -->
                                        <div class="form-group">
                                            <label for="imagens"><?php echo $texts['upload_images']; ?></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="imagens" name="imagens[]" multiple>
                                                    <label class="custom-file-label" for="imagens"><?php echo $texts['choose_files']; ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary"><?php echo $texts['submit_button']; ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal de Loading -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Carregando...</span>
                </div>
                <p>Aguarde, processando...</p>
            </div>
        </div>
    </div>
</div>
    <script>
        function changeLanguage(language) {
            window.location.href = '?lang=' + language;
        }
    </script>

<script>
$(document).ready(function(){
    // Quando o formulário for enviado
    $('#myForm').on('submit', function(e) {
        // Exibe o modal de carregamento
        $('#loadingModal').modal('show');
    });
});
</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>
