<?php

require './vendor/autoload.php';
include 'DB/conexao.php';

use Dompdf\Options;
use Dompdf\Dompdf;

$options = new Options();
$options->set('chroot', __DIR__);
$dompdf = new Dompdf($options);
$consulta = new Consulta();

$mes = $_POST['mes'];
$campus = $_POST['campus'];
$primeiroDiaMes = $_POST['dia'];
$arquivo = isset($_FILES['arquivo']);
$nomes[] = isset($_POST['nome']) ? $_POST['nome'] : "";
$qtdDiasMes = cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
$funcao = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : 'UERN';
$nomeMes = [
    '', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
];

if($funcao == 'Servidor' || $funcao == 'Terceirizado' || $funcao == 'Estagiario')
    $nomes = $consulta->listarNomesUsuariosFuncao($funcao, $campus);
else if($arquivo == true) {
    $arquivo = $_FILES['arquivo']['tmp_name'];
    $formatoArquivo = $_FILES['arquivo']['type'];
    
    if($formatoArquivo == "text/csv"){
        $conteudo = fopen($arquivo, 'r');

        $i = 0;
        while(($linha = fgetcsv($conteudo, 1000)) !== FALSE) {
            if($i > 0)
                $nomes[$i-1] = $linha[0];

            $i++;
        }
        fclose($conteudo);
    } else
        die("<script>
                alert('Formato de Arquivo Inválido! Tente Novamente usando um arquivo CSV.')
                window.close()
            </script>");
}

if(count($nomes) > 0) {
    ob_start();

    foreach($nomes as $nome) {
        if(is_array($nome))
            $nome = $nome['nome'];

        include 'montar_pdf.php';
    }

    $dompdf->loadHtml(ob_get_clean());

    $dompdf->setPaper('A4');

    $dompdf->render();

    $dompdf->stream('Folha_'.$nomeMes[$mes].'_'.$funcao.'_'.$campus.'.pdf', ["Attachment" => false]);
} else
    echo "<script>
            alert('Nenhum Usuário $funcao Cadastrado até o momento!')
            window.close()
        </script>";
