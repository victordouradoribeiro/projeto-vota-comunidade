<?php
include 'auth.php'; // Inclui a verificação de autenticação
include '../config/conexao.php'; // Inclui a conexão com o banco de dados

// // Estatísticas
$totalUsuarios = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM usuarios"))[0];
// $votacoesAtivas = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pautas WHERE status='ativa'"))[0];
$totalCondominios = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM condominios"))[0];

// // Ações recentes (exemplo simples)
// $atividadesRecentes = mysqli_query($conn, "SELECT descricao, created_at FROM atividades ORDER BY created_at DESC LIMIT 10");
include '../includes/navbar-admin.php'; // Inclui a barra de navegação
include '../includes/header.php'; // Inclui o cabeçalho HTML
?>

<div class="container mt-4">
    <h3 class="section-title">Estatísticas</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <div class="col">
            <div class="card-statistic">
                <h5>Total de Usuários</h5>
                <p><?= $totalUsuarios ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card-statistic">
                <h5>Votações Ativas</h5>
                <p><?= $votacoesAtivas ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card-statistic">
                <h5>Total de Condomínios</h5>
                <p><?= $totalCondominios ?></p>
            </div>
        </div>
    </div>

    <h3 class="section-title">Dashboard do Administrador</h3>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mb-5">
        <div class="col">
            <a href="gerenciar-sindicos.php" class="admin-button">
                <i class="fas fa-user-tie"></i>
                <span>Gerenciar Síndicos</span>
            </a>
        </div>
        <div class="col">
            <a href="gerenciar-moradores.php" class="admin-button">
                <i class="fas fa-users"></i>
                <span>Gerenciar Moradores</span>
            </a>
        </div>
        <div class="col">
            <a href="gerenciar-condominios.php" class="admin-button">
                <i class="fas fa-building"></i>
                <span>Gerenciar Condomínios</span>
            </a>
        </div>
        <div class="col">
            <a href="resultados.php" class="admin-button">
                <i class="fas fa-chart-bar"></i>
                <span>Visualizar Resultados</span>
            </a>
        </div>
    </div>

    <h3 class="section-title">Ações Recentes</h3>
    <div class="table-responsive table-recent-activities">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th scope="col">Atividade</th>
                    <th scope="col">Data/Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($atividadesRecentes) > 0): ?>
                    <?php while ($a = mysqli_fetch_assoc($atividadesRecentes)): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['descricao']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted">Nenhuma atividade recente</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
