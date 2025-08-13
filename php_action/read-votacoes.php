<?php
include '../config/conexao.php';

$idSindico = (int) $_SESSION['id_usuario'];

// Votações ativas
$sqlAtivas = "
    SELECT 
        p.id AS id_pauta,
        p.titulo,
        p.descricao,
        p.data_fim,
        c.nome AS nome_condominio,
        (SELECT COUNT(*) FROM votos v WHERE v.id_pauta = p.id) AS total_votos_geral
    FROM pautas p
    JOIN usuarios u ON u.codigo = p.id_sindico
    JOIN condominios c ON c.id = u.id_condominio
    WHERE p.status = 'ativa' AND p.id_sindico = $idSindico
    ORDER BY p.data_fim ASC
";

$resAtivas = mysqli_query($conn, $sqlAtivas);
$votacoesAtivas = [];
while ($row = mysqli_fetch_assoc($resAtivas)) {
    $votacoesAtivas[] = $row;
}

// Votações encerradas
$sqlEncerradas = "
    SELECT 
        p.id AS id_pauta,
        p.titulo,
        p.descricao,
        p.data_fim,
        c.nome AS nome_condominio,
        (SELECT COUNT(*) FROM votos v WHERE v.id_pauta = p.id) AS total_votos_geral
    FROM pautas p
    JOIN usuarios u ON u.codigo = p.id_sindico
    JOIN condominios c ON c.id = u.id_condominio
    WHERE p.status = 'encerrada' AND p.id_sindico = $idSindico
    ORDER BY p.data_fim DESC
";

$resEncerradas = mysqli_query($conn, $sqlEncerradas);
$votacoesEncerradas = [];
while ($row = mysqli_fetch_assoc($resEncerradas)) {
    $votacoesEncerradas[] = $row;
}

// Arrays para usar no dashboard.php
$data = [
    'ativas' => $votacoesAtivas,
    'encerradas' => $votacoesEncerradas,
];
