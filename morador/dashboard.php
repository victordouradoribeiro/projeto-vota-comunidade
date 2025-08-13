<?php
include 'auth.php'; // deve garantir session_start() e perfil == 3
include '../config/conexao.php';

// carrega as votações do condomínio do morador logado
include '../php_action/read-votacoes-morador.php';

include '../includes/header.php';
include '../includes/navbar-morador.php';
?>

<div class="container mt-4">
    <!-- Pautas ativas -->
    <h2 class="mb-4">Pautas ativas</h2>

    <?php if (empty($votacoesAtivas)): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center text-muted">
                <i class="fas fa-vote-yea fa-2x mb-2 opacity-50"></i>
                <div>Nenhuma votação ativa no momento.</div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($votacoesAtivas as $p): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= htmlspecialchars($p['titulo']) ?></h5>
                        <p class="card-text mb-1"><?= htmlspecialchars($p['descricao'] ?? '') ?></p>
                        <p class="card-text mb-0">
                            <small class="text-muted">Encerra em: <?= date('d/m/Y', strtotime($p['data_fim'])) ?></small>
                        </p>
                    </div>
                    <?php if ((int)$p['ja_votou'] === 1): ?>
                        <a href="detalhes-votacao.php?id=<?= (int)$p['id_pauta'] ?>" class="btn btn-secondary">Ver detalhes</a>
                    <?php else: ?>
                        <a href="votar.php?id=<?= (int)$p['id_pauta'] ?>" class="btn btn-primary btn-votar">Votar</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Pautas encerradas -->
    <h2 class="mb-4">Pautas encerradas</h2>

    <?php if (empty($votacoesEncerradas)): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center text-muted">
                <i class="fas fa-history fa-2x mb-2 opacity-50"></i>
                <div>Nenhuma votação encerrada.</div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($votacoesEncerradas as $p): ?>
            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= htmlspecialchars($p['titulo']) ?></h5>
                        <p class="card-text mb-1"><?= htmlspecialchars($p['descricao'] ?? '') ?></p>
                        <p class="card-text mb-0">
                            <small class="text-muted">Encerrada em: <?= date('d/m/Y', strtotime($p['data_fim'])) ?></small>
                        </p>
                    </div>
                    <a href="detalhes-votacao.php?id=<?= (int)$p['id_pauta'] ?>" class="btn btn-dark">Detalhes</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .card-title { font-weight: 600; color: #333; }
    .card-text { color: #555; margin-bottom: 0.5rem; }
    .text-muted { font-size: 0.875em; }
    .btn-votar {
        background-color: #4338CA;
        border-color: #4338CA;
        color: #fff;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }
    .btn-votar:hover { background-color: #3f31b8; border-color: #3f31b8; }
    .btn-dark {
        background-color: #343a40;
        border-color: #343a40;
        color: #fff;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }
    .btn-dark:hover { background-color: #23272b; border-color: #1d2124; }
</style>

<?php include '../includes/footer.php'; ?>
