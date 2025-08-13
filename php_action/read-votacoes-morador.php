<?php
// Garante sessão (caso o auth.php não tenha chamado ainda)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Se o dashboard já incluiu a conexão, ok; se não, inclua aqui.
if (!isset($conn) || !($conn instanceof mysqli)) {
    include '../config/conexao.php';
}

$votacoesAtivas = [];
$votacoesEncerradas = [];

$idMorador = (int) ($_SESSION['id_usuario'] ?? 0);
if ($idMorador <= 0) {
    // Sem usuário na sessão → arrays vazios
    return;
}

// 1) Descobre o condomínio do morador
$sqlCond = "SELECT id_condominio FROM usuarios WHERE codigo = ? LIMIT 1";
$stmtCond = $conn->prepare($sqlCond);
$stmtCond->bind_param("i", $idMorador);
$stmtCond->execute();
$resCond = $stmtCond->get_result();
$rowCond = $resCond->fetch_assoc();
$stmtCond->close();

$idCondominio = (int) ($rowCond['id_condominio'] ?? 0);
if ($idCondominio <= 0) {
    // Morador sem condomínio definido → arrays vazios
    return;
}

// 2) Pautas ATIVAS do condomínio do morador
$sqlAtivas = "
    SELECT 
        p.id AS id_pauta,
        p.titulo,
        p.descricao,
        p.data_inicio,
        p.data_fim,
        p.status,
        c.nome AS nome_condominio,
        EXISTS(
            SELECT 1
            FROM votos v
            WHERE v.id_pauta = p.id AND v.id_usuario = ?
        ) AS ja_votou
    FROM pautas p
    JOIN usuarios s ON s.codigo = p.id_sindico          -- síndico criador da pauta
    JOIN condominios c ON c.id = s.id_condominio         -- condomínio do síndico
    WHERE s.id_condominio = ? AND p.status = 'ativa'
    ORDER BY p.data_fim ASC
";
$stmtAtivas = $conn->prepare($sqlAtivas);
$stmtAtivas->bind_param("ii", $idMorador, $idCondominio);
$stmtAtivas->execute();
$resAtivas = $stmtAtivas->get_result();
while ($r = $resAtivas->fetch_assoc()) {
    // Normaliza ja_votou pra 0/1 inteiro
    $r['ja_votou'] = (int)$r['ja_votou'];
    $votacoesAtivas[] = $r;
}
$stmtAtivas->close();

// 3) Pautas ENCERRADAS do condomínio do morador
$sqlEnc = "
    SELECT 
        p.id AS id_pauta,
        p.titulo,
        p.descricao,
        p.data_inicio,
        p.data_fim,
        p.status,
        c.nome AS nome_condominio
    FROM pautas p
    JOIN usuarios s ON s.codigo = p.id_sindico
    JOIN condominios c ON c.id = s.id_condominio
    WHERE s.id_condominio = ? AND p.status = 'encerrada'
    ORDER BY p.data_fim DESC
";
$stmtEnc = $conn->prepare($sqlEnc);
$stmtEnc->bind_param("i", $idCondominio);
$stmtEnc->execute();
$resEnc = $stmtEnc->get_result();
while ($r = $resEnc->fetch_assoc()) {
    $votacoesEncerradas[] = $r;
}
$stmtEnc->close();

// Opcional: também disponibiliza num array único
$data = [
    'ativas' => $votacoesAtivas,
    'encerradas' => $votacoesEncerradas,
];
