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
                <td><strong><?= htmlspecialchars($s['nome']) ?></strong></td>
                <td><span class="badge bg-info"><?= htmlspecialchars($s['condominio'] ?? '-') ?></span></td>
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
                    <a href="editar-sindico.php?id=<?= $s['codigo'] ?>" class="btn btn-outline-primary btn-sm me-2"> <i class="fas fa-edit"></i> Editar</a>
                    <?php if ($s['status'] == 'pendente'): ?>
                        <a href="../php_action/reject-sindico.php?id=<?= $s['codigo'] ?>" class="btn btn-outline-danger btn-sm me-2"> <i class="fa fa-close"></i> Rejeitar</a>
                        <a href="../php_action/approve-sindico.php?id=<?= $s['codigo'] ?>" class="btn btn-outline-success btn-sm"> <i class="fas fa-check-square"></i> Aprovar</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover<?= $s['codigo'] ?>">
                            <i class="fas fa-trash"></i> Apagar
                        </button>
                        
                        <div class="modal fade" id="modalRemover<?= $s['codigo'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $s['codigo'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-body py-4">
                                <h5 class="mb-3 fw-bold text-danger" style="font-size:1.2rem;">Tem certeza que deseja apagar este síndico?</h5>
                                <p class="mb-4">Esta ação não pode ser desfeita.</p>
                                <form method="POST" action="../php_action/delete-sindico.php">
                                    <input type="hidden" name="id" value="<?= $s['codigo'] ?>">
                                    <button type="submit" class="btn btn-danger me-2">Apagar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
<?php endif;