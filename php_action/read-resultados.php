<?php
include '../config/conexao.php';

header('Content-Type: application/json');

$condominio_id = isset($_GET['condominio_id']) ? intval($_GET['condominio_id']) : '0';

// Buscar condomínios (para o filtro)
$sqlCondominios = "SELECT id, nome FROM condominios ORDER BY nome";
$resCondominios = mysqli_query($conn, $sqlCondominios);
$condominios = [];
while ($row = mysqli_fetch_assoc($resCondominios)) {
    $condominios[] = $row;
}

// Buscar pautas ativas ou encerradas do condomínio selecionado
// Para isso precisamos buscar pautas que possuem votos e que sejam do condomínio em questão.
// Como na sua estrutura pauta não tem id_condominio direto, precisaremos buscar pautas cujos votos foram dados por usuários desse condomínio.

if ($condominio_id > 0) {
    // Buscar pautas relacionadas ao condomínio pelo voto dos usuários
    $sqlPautas = "
        SELECT p.*
        FROM pautas p
        JOIN usuarios s ON s.codigo = p.id_sindico
        WHERE s.id_condominio = $condominio_id
        ORDER BY p.data_fim DESC
";

} else {
    // Se não passou condomínio, pegar todas as pautas (limitando a 10)
    $sqlPautas = "SELECT * FROM pautas ORDER BY data_fim DESC LIMIT 10";
}

$resPautas = mysqli_query($conn, $sqlPautas);
$pautas = [];

while ($pauta = mysqli_fetch_assoc($resPautas)) {
    // Buscar opções e votos para cada pauta
    $idPauta = $pauta['id'];

    // Total de votos na pauta
    $sqlTotalVotos = "SELECT COUNT(*) as total FROM votos WHERE id_pauta = $idPauta";
    $totalVotos = intval(mysqli_fetch_assoc(mysqli_query($conn, $sqlTotalVotos))['total']);

    // Buscar opções e votos por opção
    $sqlOpcoes = "
        SELECT ov.id, ov.descricao, COUNT(v.id) AS votos 
        FROM opcoes_voto ov
        LEFT JOIN votos v ON v.id_opcao = ov.id AND v.id_pauta = $idPauta
        WHERE ov.id_pauta = $idPauta
        GROUP BY ov.id, ov.descricao
    ";
    $resOpcoes = mysqli_query($conn, $sqlOpcoes);
    $opcoes = [];
    while ($op = mysqli_fetch_assoc($resOpcoes)) {
        $opcoes[] = $op;
    }

    $pauta['total_votos'] = $totalVotos;
    $pauta['opcoes'] = $opcoes;

    $pautas[] = $pauta;
}

echo json_encode([
    'condominios' => $condominios,
    'pautas' => $pautas,
]);
