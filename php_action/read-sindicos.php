<?php
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Consulta SQL
$sql = "
    SELECT u.codigo, u.nome, u.status, c.nome AS condominio
    FROM usuarios u
    LEFT JOIN condominios c ON u.id_condominio = c.id
    WHERE u.perfil = 2
";
if ($pesquisa) {
    $pesquisaEsc = mysqli_real_escape_string($conn, $pesquisa);
    $sql .= " AND (u.nome LIKE '%$pesquisaEsc%' OR c.nome LIKE '%$pesquisaEsc%')";
}
$sql .= " ORDER BY u.codigo DESC LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0): ?>
    <tbody>
        <tr>
            <td colspan="4" class="text-center text-muted">Nenhum síndico encontrado.</td>
        </tr>
    </tbody>
<?php else: ?>
    <tbody>
        <?php while ($s = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($s['nome']) ?></td>
                <td><?= htmlspecialchars($s['condominio'] ?? '-') ?></td>
                <td>
                    <?php
                        if ($s['status'] == 'pendente') {
                            echo '<span class="badge bg-warning text-dark">Pendente</span>';
                        } elseif ($s['status'] == 'rejeitado') {
                            echo '<span class="badge bg-danger">Rejeitado</span>';
                        } else {
                            echo '<span class="badge bg-success">Ativo</span>';
                        }
                    ?>
                </td>
                <td>
                    <a href="editar-sindico.php?id=<?= $s['codigo'] ?>" class="btn btn-outline-primary btn-sm me-2">Editar</a>
                    <?php if ($s['status'] == 'pendente'): ?>
                        <a href="../php_action/rejeitar_sindico.php?id=<?= $s['codigo'] ?>" class="btn btn-outline-danger btn-sm me-2">Rejeitar</a>
                        <a href="../php_action/aprovar_sindico.php?id=<?= $s['codigo'] ?>" class="btn btn-outline-success btn-sm">Aprovar</a>
                    <?php else: ?>
                        <a href="../php_action/remover_sindico.php?id=<?= $s['codigo'] ?>" class="btn btn-outline-danger btn-sm">Remover</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
<?php endif;