<?php
// Recebe parâmetros da página principal
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Consulta SQL
$sql = "
    SELECT c.id, c.nome, c.endereco,
           u.nome AS sindico_nome
    FROM condominios c
    LEFT JOIN usuarios u ON u.id_condominio = c.id AND u.perfil = 2
";
if ($pesquisa) {
    $pesquisaEsc = mysqli_real_escape_string($conn, $pesquisa);
    $sql .= " WHERE c.nome LIKE '%$pesquisaEsc%'
               OR c.endereco LIKE '%$pesquisaEsc%'
               OR u.nome LIKE '%$pesquisaEsc%'";
}
$sql .= " ORDER BY c.id DESC LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0): ?>
    <tbody>
        <tr>
            <td colspan="4" class="text-center text-muted">Nenhum condomínio encontrado.</td>
        </tr>
    </tbody>
<?php else: ?>
    <tbody>
        <?php while ($c = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><strong><?= htmlspecialchars($c['nome']) ?></strong></td>
                <td><?= htmlspecialchars($c['endereco']) ?></td>
                <td>
                    <?php if ($c['sindico_nome']): ?>
                        <span class="badge bg-success"><?= htmlspecialchars($c['sindico_nome']) ?></span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Não definido</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="editar-condominio.php?id=<?= $c['id'] ?>" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="php_action/remover-condominio.php?id=<?= $c['id'] ?>" class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Tem certeza que deseja remover este condomínio? Esta ação não pode ser desfeita.')">
                        <i class="fas fa-trash"></i> Remover
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
<?php endif; ?>