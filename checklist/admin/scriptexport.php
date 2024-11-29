<?php
// Inclua o arquivo de conexão com o banco de dados
include('db.php');

// Receber parâmetros do DataTables
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 10;
$orderColumnIndex = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 0;
$orderDir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc';

// Mapeie os índices das colunas para os nomes das colunas no banco de dados
$columns = array(
    0 => 'created_at',
    1 => 'name',
    2 => 'email',
    3 => 'Celular',
    4 => 'cpf',
    5 => 'cep',
    6 => 'endereco',
    7 => 'Produto',
    8 => 'PolegadaTV',
    9 => 'numero_serie',
    10 => 'Numero_nota',
    11 => 'Upload'
);

// Nome da coluna para ordenação
$orderColumn = $columns[$orderColumnIndex];

// Campos relevantes
$relevant_keys = ['Upload','Numero_nota','numero_serie','PolegadaTV', 'Produto', 'endereco', 'cep', 'cpf', 'Celular', 'email', 'name'];

// Consulta principal para obter submission_id e created_at limitados e offsetados
$sql_submission_ids = "SELECT DISTINCT sv.submission_id, s.created_at 
                       FROM wp_e_submissions_values sv
                       JOIN wp_e_submissions s ON sv.submission_id = s.id
                       ORDER BY $orderColumn $orderDir
                       LIMIT $length OFFSET $start";
$result_submission_ids = $conn->query($sql_submission_ids);
if (!$result_submission_ids) {
    die("Erro na consulta SQL para obter submission_ids: " . $conn->error);
}

$submission_ids = [];
$submission_dates = [];
while ($row = $result_submission_ids->fetch_assoc()) {
    $submission_ids[] = $row['submission_id'];
    $submission_dates[$row['submission_id']] = $row['created_at'];
}

// Verificar se há submission_ids para evitar erro na próxima consulta
if (count($submission_ids) > 0) {
    $submission_ids_list = implode(',', $submission_ids);

    // Busca de registros com base nos submission_ids obtidos
    $sql = "SELECT submission_id, `key`, `value` 
            FROM wp_e_submissions_values 
            WHERE submission_id IN ($submission_ids_list)
              AND `key` IN ('" . implode("', '", $relevant_keys) . "')
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
            $data[$submission_id]['created_at'] = $submission_dates[$submission_id];
        }
        
        // Atribuir o valor ao campo correto
        if ($key === 'Upload' && !empty($value)) {
            $data[$submission_id]['Upload'] = htmlspecialchars($value);
        } else {
            $data[$submission_id][$key] = htmlspecialchars($value);
        }
    }
} else {
    $data = [];
}

// Contagem total de registros distintos
$sql_count = "SELECT COUNT(DISTINCT submission_id) AS total FROM wp_e_submissions_values";
$result_count = $conn->query($sql_count);
if (!$result_count) {
    die("Erro na consulta SQL de contagem: " . $conn->error);
}
$total_rows = $result_count->fetch_assoc()['total'];

// Contagem de registros filtrados
$sql_count_filtered = "SELECT COUNT(DISTINCT sv.submission_id) AS total 
                       FROM wp_e_submissions_values sv
                       JOIN wp_e_submissions s ON sv.submission_id = s.id
                       WHERE sv.`key` IN ('" . implode("', '", $relevant_keys) . "')";
$result_count_filtered = $conn->query($sql_count_filtered);
if (!$result_count_filtered) {
    die("Erro na consulta SQL de contagem filtrada: " . $conn->error);
}
$total_rows_filtered = $result_count_filtered->fetch_assoc()['total'];

// Formatando os dados para o DataTables
$final_data = [];
foreach ($data as $submission) {
    $final_data[] = [
        $submission['created_at'],
        htmlspecialchars($submission['name']),
        htmlspecialchars($submission['email']),
        htmlspecialchars($submission['Celular']),
        htmlspecialchars($submission['cpf']),
        htmlspecialchars($submission['cep']),
        htmlspecialchars($submission['endereco']),
        htmlspecialchars($submission['Produto']),
        htmlspecialchars($submission['PolegadaTV']),
        htmlspecialchars($submission['numero_serie']),
        htmlspecialchars($submission['Numero_nota']),
        $submission['Upload'] // Apenas a URL, sem HTML
    ];
}

$response = [
    "draw" => $draw,
    "recordsTotal" => $total_rows,
    "recordsFiltered" => $total_rows_filtered,
    "data" => $final_data
];

echo json_encode($response);
?>
