<?php
include 'auth.php';
include '../config/conexao.php';
$currentPage = 'condominios';
include '../includes/navbar-admin.php';

// Parâmetros de pesquisa e paginação
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Condomínios</h2>
        <a href="adicionar-condominio.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Adicionar Condomínio
        </a>
    </div>

    <form method="GET" action="gerenciar-condominios.php" class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" name="pesquisa" placeholder="Pesquisar por nome, endereço ou síndico..." value="<?= htmlspecialchars($pesquisa) ?>">
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
            <?php if (!empty($pesquisa)): ?>
                <a href="gerenciar-condominios.php" class="btn btn-outline-danger">Limpar</a>
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
                            <th scope="col">ENDEREÇO</th>
                            <th scope="col">SÍNDICO</th>
                            <th scope="col">AÇÕES</th>
                        </tr>
                    </thead>
                    <?php
                        include '../php_action/read-condominios.php';
                    ?>
                </table>
            </div>
        </div>
    </div>

    <?php
    // Paginação
    $sqlCount = "SELECT COUNT(*) FROM condominios";
    if ($pesquisa) {
        $pesquisaEsc = mysqli_real_escape_string($conn, $pesquisa);
        $sqlCount = "
            SELECT COUNT(*) FROM condominios c
            LEFT JOIN usuarios u ON u.id_condominio = c.id AND u.perfil = 2
            WHERE c.nome LIKE '%$pesquisaEsc%'
               OR c.endereco LIKE '%$pesquisaEsc%'
               OR u.nome LIKE '%$pesquisaEsc%'
        ";
    }
    $totalRows = mysqli_fetch_row(mysqli_query($conn, $sqlCount))[0];
    $totalPages = ceil($totalRows / $limit);
    ?>
    <nav>
        <ul class="pagination justify-content-center mt-4">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?pesquisa=<?= urlencode($pesquisa) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php include '../includes/footer.php'; ?>