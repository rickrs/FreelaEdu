<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('admin/db.php'); // Inclua o arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_login_id = $_SESSION['user_login_id']; // ID do usuário logado
    $nome_loja = intval($_POST['nome_loja']); // Converte o valor para inteiro
    $wd_hotspot = isset($_POST['wd_hotspot']) ? 1 : 0;
    $wd_demo = isset($_POST['wd_demo']) ? 1 : 0;
    $product_on = isset($_POST['product_on']) ? 1 : 0;
    $pop_material = isset($_POST['pop_material']) ? 1 : 0;
    $premium_models = isset($_POST['premium_models']) ? 1 : 0;
    $prices_visible = isset($_POST['prices_visible']) ? 1 : 0;
    $flooring = isset($_POST['flooring']) ? 1 : 0;
    $inventory = isset($_POST['inventory']) ? 1 : 0;
    $hisense_display = isset($_POST['hisense_display']) ? 1 : 0;
    $empty_space = isset($_POST['empty_space']) ? 1 : 0;
    $premium_presence = isset($_POST['premium_presence']) ? 1 : 0;
    $stronger_presence = isset($_POST['stronger_presence']) ? 1 : 0;
    $promoter = isset($_POST['promoter']) ? 1 : 0;
    $pending_request = isset($_POST['pending_request']) ? 1 : 0;
    $fsm_aware = isset($_POST['fsm_aware']) ? 1 : 0;

    // Novos campos de avaliação (inputs de range)
    $clarity = intval($_POST['clarity']);
    $check_list_sent = intval($_POST['check_list_sent']);
    $report_pending_issue = intval($_POST['report_pending_issue']);
    $suggestions_advices = intval($_POST['suggestions_advices']);
    $opportunities_improve = intval($_POST['opportunities_improve']);

    // Novos campos de texto (textarea)
    $issues_report = $conn->real_escape_string($_POST['issues_report']);
    $suggestions_report = $conn->real_escape_string($_POST['suggestions_report']);
    $opportunity_improve = $conn->real_escape_string($_POST['opportunity_improve']);

    // Inserindo os dados na tabela checklist_form
    $sql = "INSERT INTO checklist_form (
        user_login_id, 
        nome_loja, 
        wd_hotspot, 
        wd_demo, 
        product_on, 
        pop_material, 
        premium_models, 
        prices_visible, 
        flooring, 
        inventory, 
        hisense_display, 
        empty_space, 
        premium_presence, 
        stronger_presence, 
        promoter, 
        pending_request, 
        fsm_aware, 
        clarity, 
        check_list_sent, 
        report_pending_issue, 
        suggestions_advices, 
        opportunities_improve, 
        issues_report, 
        suggestions_report, 
        opportunity_improve) 
    VALUES (
        '$user_login_id', 
        '$nome_loja', 
        '$wd_hotspot', 
        '$wd_demo', 
        '$product_on', 
        '$pop_material', 
        '$premium_models', 
        '$prices_visible', 
        '$flooring', 
        '$inventory', 
        '$hisense_display', 
        '$empty_space', 
        '$premium_presence', 
        '$stronger_presence', 
        '$promoter', 
        '$pending_request', 
        '$fsm_aware', 
        '$clarity', 
        '$check_list_sent', 
        '$report_pending_issue', 
        '$suggestions_advices', 
        '$opportunities_improve', 
        '$issues_report', 
        '$suggestions_report', 
        '$opportunity_improve')";

    if ($conn->query($sql) === TRUE) {
        $checklist_id = $conn->insert_id; // Obtém o ID do checklist inserido

        // Verifica se imagens foram enviadas
        if (isset($_FILES['imagens'])) {
            $total_files = count($_FILES['imagens']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                $imagem_nome = $_FILES['imagens']['name'][$i];
                $imagem_tmp_name = $_FILES['imagens']['tmp_name'][$i];

                // Define o caminho para salvar a imagem
                $target_dir = "admin/uploads/";
                $target_file = $target_dir . basename($imagem_nome);

                // Move o arquivo enviado para o diretório de uploads
                if (move_uploaded_file($imagem_tmp_name, $target_file)) {
                    // Insere o nome da imagem na tabela checklist_imagens
                    $sql_img = "INSERT INTO checklist_imagens (checklist_id, imagem_nome) 
                                VALUES ('$checklist_id', '$imagem_nome')";
                    $conn->query($sql_img);
                }
            }
        }

        // Redireciona após o sucesso
        header("Location: success.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>
