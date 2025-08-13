<?php
include 'auth.php';
include '../config/conexao.php';

$idSindico = (int) $_SESSION['id_usuario'];

// Estatísticas
$totalCondominios = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM condominios"))[0];

// Pega votações usando o arquivo read-votacoes.php
include '../php_action/read-votacoes.php';
$votacoesAtivas = $data['ativas'];
$votacoesEncerradas = $data['encerradas'];
$totalAtivas = count($votacoesAtivas);
$totalEncerradas = count($votacoesEncerradas);

include '../includes/header.php';
include '../includes/navbar-sindico.php';
?>

    <!-- Votações Ativas -->
    <h3 class="section-title">Pautas Ativas</h3>
    <?php if ($totalAtivas === 0): ?>
        <div class="pauta-card text-center text-muted">
            <i class="fas fa-vote-yea fa-3x mb-3 opacity-50"></i>
            <h5>Nenhuma votação ativa</h5>
            <p>Não há pautas de votação ativas no momento.</p>
        </div>
    <?php else: ?>
        <?php foreach ($votacoesAtivas as $votacao): ?>
            <div class="pauta-card">
                <h5><?= htmlspecialchars($votacao['titulo']) ?></h5>
                <p><?= htmlspecialchars($votacao['descricao']) ?></p>
                <p><small>Condomínio: <?= htmlspecialchars($votacao['nome_condominio']) ?></small></p>
                <p class="mb-2"><small>Encerra em: <?= date('d/m/Y H:i', strtotime($votacao['data_fim'])) ?></small></p>
                <a href="detalhes-votacao.php?id=<?= $votacao['id_pauta'] ?>" class="btn-details">Detalhes</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Votações Encerradas -->
    <h3 class="section-title">Pautas Encerradas</h3>
    <?php if ($totalEncerradas === 0): ?>
        <div class="pauta-card text-center text-muted">
            <i class="fas fa-history fa-3x mb-3 opacity-50"></i>
            <h5>Nenhuma votação encerrada</h5>
            <p>Não há pautas de votação encerradas.</p>
        </div>
    <?php else: ?>
        <?php foreach ($votacoesEncerradas as $votacao): ?>
            <div class="pauta-card d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-2"><?= htmlspecialchars($votacao['titulo']) ?></h5>
                    <p class="mb-1"><?= htmlspecialchars($votacao['descricao']) ?></p>
                    <p class="mb-1"><small>Condomínio: <?= htmlspecialchars($votacao['nome_condominio']) ?></small></p>
                    <p class="mb-0"><small>Encerrada em: <?= date('d/m/Y H:i', strtotime($votacao['data_fim'])) ?></small></p>
                </div>
                <a href="detalhes-votacao.php?id=<?= $votacao['id_pauta'] ?>" class="btn-details">Detalhes</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .card-statistic {
        background-color: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        text-align: left;
        height: 100%;
    }
    .section-title { 
        margin-left: 30px;
    }
    .card-statistic h5 { color:#555; font-size:1rem; margin-bottom:10px; }
    .card-statistic p { color:#333; font-size:2rem; font-weight:700; margin-bottom:0; }
    .section-title { color:#333; font-size:1.5rem; font-weight:600; margin-bottom:25px; margin-top:35px; }
    .pauta-card {
        background-color:#fff;
        border-radius:12px;
        box-shadow:0 4px 15px rgba(0,0,0,0.08);
        border:none;
        margin-bottom:20px;
        padding:20px;
        width:97%;
        margin-left: 30px;
    }
    .pauta-card h5 { color:#333; font-size:1.25rem; font-weight:600; margin-bottom:10px; }
    .pauta-card p { color:#555; margin-bottom:8px; line-height:1.5; }
    .pauta-card small { color:#777; font-size:0.875rem; }
    .btn-details {
        background-color: #4338CA;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration:none;
        margin-top: 18px;
    }
    .btn-details:hover {
        background-color: #3730A3;
        transform: translateY(-1px);
        box-shadow:0 4px 12px rgba(67,56,202,0.3);
        color:white;
    }
</style>

<?php include '../includes/footer.php'; ?>
