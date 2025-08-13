<?php
include '../auth.php';
include '../config/conexao.php';

// Pega ID da votação
$idPauta = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idPauta <= 0) {
    echo "Votação inválida.";
    exit;
}

// Lê dados da votação
$sqlVotacao = "
    SELECT 
        p.*,
        c.nome AS nome_condominio,
        (SELECT COUNT(*) FROM votos v WHERE v.id_pauta = p.id) AS total_votos
    FROM pautas p
    JOIN usuarios u ON u.codigo = p.id_sindico
    JOIN condominios c ON c.id = u.id_condominio
    WHERE p.id = $idPauta
";
$res = mysqli_query($conn, $sqlVotacao);
if (mysqli_num_rows($res) === 0) {
    echo "Votação não encontrada.";
    exit;
}
$votacao = mysqli_fetch_assoc($res);

// Lê resultados da votação
$sqlResultados = "
    SELECT descricao, 
           (SELECT COUNT(*) FROM votos v WHERE v.id_pauta = r.id_pauta AND v.id_opcao = r.id) AS total_votos
    FROM opcoes_voto r
    WHERE r.id_pauta = $idPauta
";
$resResultados = mysqli_query($conn, $sqlResultados);
$resultados = [];
$totalVotos = (int)$votacao['total_votos'];

while ($row = mysqli_fetch_assoc($resResultados)) {
    $porcentagem = $totalVotos > 0 ? round(($row['total_votos'] / $totalVotos) * 100) : 0;
    $row['porcentagem'] = $porcentagem;
    $resultados[] = $row;
}

include '../includes/navbar-sindico.php';
include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 main-content">
            <h1 class="mb-4">Detalhes da Votação</h1>

            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-poll"></i> <?= htmlspecialchars($votacao['titulo']) ?></h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Condomínio:</strong></div>
                        <div class="col-sm-9"><?= htmlspecialchars($votacao['nome_condominio']) ?></div>
                    </div>
                    <?php if ($votacao['descricao']): ?>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Descrição:</strong></div>
                            <div class="col-sm-9"><?= htmlspecialchars($votacao['descricao']) ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Status:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge <?= $votacao['status'] === 'ativa' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ucfirst($votacao['status']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Período:</strong></div>
                        <div class="col-sm-9">
                            De <?= date('d/m/Y H:i', strtotime($votacao['data_inicio'])) ?>
                            até <?= date('d/m/Y H:i', strtotime($votacao['data_fim'])) ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Total de Votos:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge bg-primary fs-6"><?= $totalVotos ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Resultados da Votação</h5>
                </div>
                <div class="card-body">
                    <?php if ($totalVotos > 0): ?>
                        <?php foreach ($resultados as $resItem): ?>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><?= htmlspecialchars($resItem['descricao']) ?></h6>
                                    <div class="text-end">
                                        <span class="badge bg-info"><?= $resItem['total_votos'] ?> votos</span>
                                        <span class="badge bg-success"><?= $resItem['porcentagem'] ?>%</span>
                                    </div>
                                </div>
                                <div class="progress" style="height:30px;">
                                    <div class="progress-bar bg-gradient" role="progressbar"
                                         style="width: <?= $resItem['porcentagem'] ?>%" 
                                         aria-valuenow="<?= $resItem['porcentagem'] ?>" aria-valuemin="0" aria-valuemax="100">
                                        <span class="d-flex justify-content-between align-items-center w-100 px-3">
                                            <span class="text-dark"><?= htmlspecialchars($resItem['descricao']) ?></span>
                                            <span class="fw-bold text-dark"><?= $resItem['total_votos'] ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-vote-yea fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum voto registrado</h5>
                            <p class="text-muted">Esta votação ainda não recebeu votos.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sidebar { min-height: 100vh; background-color: #f8f9fa; }
.main-content { padding: 2rem; }
.card .progress-bar { min-width: 100%; position: relative; overflow: visible; }
.card .progress-bar span { color: white; font-weight: 500; }
.progress { background-color: #e9ecef; border-radius: 0.375rem; }
.bg-gradient { background: linear-gradient(45deg, #007bff, #0056b3); }
</style>

<?php include '../includes/footer.php'; ?>