<?php
include '../config/conexao.php';

header('Content-Type: application/json');

$condominio_id = $_SESSION['usuario']['id_condominio'] ?? 0;

// Buscar condomínios (para o filtro)
$sqlCondominios = "SELECT id, nome FROM condominios ORDER BY nome";
$resCondominios = mysqli_query($conn, $sqlCondominios);
$condominios = [];
while ($row = mysqli_fetch_assoc($resCondominios)) {
    $condominios[] = $row;
}

// Buscar pautas apenas do condomínio selecionado
$pautas = [];
if ($condominio_id > 0) {
    $sqlPautas = "
        SELECT p.*
        FROM pautas p
        JOIN usuarios u ON u.codigo = p.id_sindico
        WHERE u.id_condominio = 2
        ORDER BY p.data_fim DESC
    ";
    $resPautas = mysqli_query($conn, $sqlPautas);

    while ($pauta = mysqli_fetch_assoc($resPautas)) {
        $idPauta = $pauta['id'];

        // Total de votos
        $sqlTotalVotos = "SELECT COUNT(*) AS total FROM votos WHERE id_pauta = $idPauta";
        $totalVotos = intval(mysqli_fetch_assoc(mysqli_query($conn, $sqlTotalVotos))['total']);

        // Opções de voto
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
}

// Retorna JSON
echo json_encode([
    'condominios' => $condominios,
    'pautas' => $pautas
]);
