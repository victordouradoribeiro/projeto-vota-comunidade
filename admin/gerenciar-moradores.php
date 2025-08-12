<?php
if (isset($_SESSION['sucesso'])) {
    echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['sucesso']).'</div>';
    unset($_SESSION['sucesso']);
}
if (isset($_SESSION['erro'])) {
    echo '<div class="alert alert-danger">'.htmlspecialchars($_SESSION['erro']).'</div>';
    unset($_SESSION['erro']);
}

include 'auth.php';
include '../config/conexao.php';
$currentPage = 'moradores';
include '../includes/navbar-admin.php';
include '../includes/header.php';

// Parâmetros de pesquisa e paginação
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Moradores</h2>
        <a href="adicionar-morador.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Adicionar Morador
        </a>
    </div>

    <form method="GET" action="gerenciar-moradores.php" class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" name="pesquisa" placeholder="Pesquisar por nome, e-mail, CPF ou condomínio..." value="<?= htmlspecialchars($pesquisa) ?>">
            <button class="btn btn-outline-primary" type="submit">Pesquisar</button>
            <?php if (!empty($pesquisa)): ?>
                <a href="gerenciar-moradores.php" class="btn btn-outline-danger">Limpar</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col">NOME</th>
                            <th scope="col">E-MAIL</th>
                            <th scope="col">CPF</th>
                            <th scope="col">CONDOMÍNIO</th>
                            <th scope="col">AÇÕES</th>
                        </tr>
                    </thead>
                    <?php include '../php_action/read-moradores.php'; ?>
                </table>
            </div>
        </div>
    </div>

    <?php
    // Paginação (contagem)
    $sqlCount = "SELECT COUNT(*) FROM usuarios WHERE perfil = 3";
    if ($pesquisa) {
        $pesquisaEsc = mysqli_real_escape_string($conn, $pesquisa);
        $sqlCount = "
            SELECT COUNT(*) 
            FROM usuarios u
            LEFT JOIN condominios c ON c.id = u.id_condominio
            WHERE u.perfil = 3
              AND (
                  u.nome LIKE '%$pesquisaEsc%'
               OR u.email LIKE '%$pesquisaEsc%'
               OR u.cpf LIKE '%$pesquisaEsc%'
               OR c.nome LIKE '%$pesquisaEsc%'
              )
        ";
    }
    $totalRows = mysqli_fetch_row(mysqli_query($conn, $sqlCount))[0];
    $totalPages = max(1, ceil($totalRows / $limit));
    ?>
    <nav aria-label="Paginação" class="bg-white">
        <ul class="pagination pagination-sm justify-content-center mt-4">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?pesquisa=<?= urlencode($pesquisa) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include '../includes/footer.php'; ?>