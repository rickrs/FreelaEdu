<?php
session_start();

// Ativar a exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php'); // Inclua o arquivo de conexão com o banco de dados

// Definir cabeçalhos para download do CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=notas_fiscais.csv');

// Abrir saída para escrita
$output = fopen('php://output', 'w');

// Escrever cabeçalhos no CSV
fputcsv($output, ['ID da Submissão', 'Nome', 'E-mail', 'Celular', 'CPF', 'CEP', 'Endereço', 'Produto', 'Nota Fiscal']);

// Campos relevantes
$relevant_keys = ['Upload', 'Produto', 'endereco', 'cep', 'cpf', 'Celular', 'email', 'name'];

// Buscar todos os registros, sem limite de paginação
$sql = "SELECT submission_id, `key`, `value`
        FROM wp_e_submissions_values
        WHERE `key` IN ('" . implode("', '", $relevant_keys) . "')
        ORDER BY submission_id";
$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta SQL para obter valores: " . $conn->error);
}

// Organizar os dados em um array associativo
$data = [];
while ($row = $result->fetch_assoc()) {
    $submission_id = $row['submission_id'];
    $key = $row['key'];
    $value = $row['value'];
    
    if (!isset($data[$submission_id])) {
        $data[$submission_id] = array_fill_keys($relevant_keys, '');
        $data[$submission_id]['submission_id'] = $submission_id;
    }
    
    $data[$submission_id][$key] = $value;
}

// Escrever os dados no CSV
foreach ($data as $submission) {
    $csv_row = [
        $submission['submission_id'],
        $submission['name'],
        $submission['email'],
        $submission['Celular'],
        $submission['cpf'],
        $submission['cep'],
        $submission['endereco'],
        $submission['Produto'],
        $submission['Upload'],
    ];
    fputcsv($output, $csv_row);
}

// Fechar a saída
fclose($output);
exit();
?>
