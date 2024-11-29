<?php
require('fpdf/fpdf.php'); // Inclua a biblioteca FPDF

// Conectar ao banco de dados
include('db.php');

class PDF extends FPDF
{
    // Cabeçalho
    function Header()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, 'Relatório de Nota2s Fiscais', 0, 1, 'C');
        $this->Ln(10);
    }

    // Rodapé
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Ajuste para quebra de texto
    function MultiCellRow($widths, $data)
    {
        // Altura da linha
        $lineHeight = 5;

        // Calcular a altura máxima da linha
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
        }
        $h = $lineHeight * $nb;

        // Emitir uma célula com a altura apropriada
        for ($i = 0; $i < count($data); $i++) {
            $w = $widths[$i];
            $a = 'L';
            // Guardar a posição atual
            $x = $this->GetX();
            $y = $this->GetY();
            // Desenhar o contorno
            $this->Rect($x, $y, $w, $h);
            // Imprimir o texto
            $this->MultiCell($w, $lineHeight, $data[$i], 0, $a);
            // Voltar à posição direita
            $this->SetXY($x + $w, $y);
        }
        // Ir para a próxima linha
        $this->Ln($h);
    }

    // Calcular o número de linhas de uma célula MultiCell
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 9);

// Cabeçalho da tabela
$widths = [20, 30, 40, 30, 30, 20, 40, 30, 30, 30, 30];
$pdf->MultiCellRow($widths, ['ID', 'Nome', 'Email', 'Celular', 'CPF', 'CEP', 'Endereço', 'Produto', 'PolegadaTV', 'Numero Serie', 'Numero Nota']);

$pdf->SetFont('Arial', '', 9);

$relevant_keys = ['name', 'email', 'Celular', 'cpf', 'cep', 'endereco', 'Produto', 'PolegadaTV', 'numero_serie', 'Numero_nota'];
$sql = "SELECT submission_id, `key`, `value` FROM wp_e_submissions_values WHERE `key` IN ('" . implode("', '", $relevant_keys) . "')";
$result = $conn->query($sql);

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

foreach ($data as $submission) {
    $row = [
        $submission['submission_id'],
        $submission['name'],
        $submission['email'],
        $submission['Celular'],
        $submission['cpf'],
        $submission['cep'],
        $submission['endereco'],
        $submission['Produto'],
        $submission['PolegadaTV'],
        $submission['numero_serie'],
        $submission['Numero_nota']
    ];
    $pdf->MultiCellRow($widths, $row);
}

$pdf->Output('D', 'relatorio.pdf');
?>
