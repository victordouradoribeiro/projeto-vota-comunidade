<?php
include 'auth.php'; // Inclui a verificação de autenticação
include '../config/conexao.php'; // Inclui a conexão com o banco de dados

// // Estatísticas
// $totalUsuarios = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM usuarios"))[0];
// $votacoesAtivas = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pautas WHERE status='ativa'"))[0];
// $totalCondominios = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM condominios"))[0];

// // Ações recentes (exemplo simples)
// $atividadesRecentes = mysqli_query($conn, "SELECT descricao, created_at FROM atividades ORDER BY created_at DESC LIMIT 10");
include '../includes/header.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrativo - Vota Comunidade</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <style>
        .card-statistic { background-color: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-align: left; height: 100%; }
        .card-statistic h5 { color: #555; font-size: 1rem; margin-bottom: 10px; }
        .card-statistic p { color: #333; font-size: 2rem; font-weight: 700; margin-bottom: 0; }
        .section-title { color: #333; font-size: 1.5rem; font-weight: 600; margin-bottom: 25px; margin-top: 35px; }
        .admin-button { background-color: #fff; border: 1px solid #ddd; border-radius: 12px; padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; transition: all 0.3s ease; text-decoration: none; color: #333; }
        .admin-button:hover { background-color: #e9ecef; border-color: #c0c0c0; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); color: #333; }
        .admin-button i { font-size: 2.5rem; color: #4338CA; margin-bottom: 10px; }
        .admin-button span { font-weight: 500; font-size: 1.1rem; }
        .table-recent-activities { background-color: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; }
        .table-recent-activities th { background-color: #f8f9fa; color: #555; font-weight: 600; padding: 15px; }
        .table-recent-activities td { padding: 15px; color: #333; }
        .table-recent-activities tbody tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>

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
            <a href="sindicos.php" class="admin-button">
                <i class="fas fa-user-tie"></i>
                <span>Gerenciar Síndicos</span>
            </a>
        </div>
        <div class="col">
            <a href="moradores.php" class="admin-button">
                <i class="fas fa-users"></i>
                <span>Gerenciar Moradores</span>
            </a>
        </div>
        <div class="col">
            <a href="condominios.php" class="admin-button">
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

</body>
</html>
