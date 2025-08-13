<?php
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$sql = "
    SELECT u.codigo, u.nome, u.email, u.cpf, u.bloco, u.casa, u.status, c.nome AS condominio
    FROM usuarios u
    LEFT JOIN condominios c ON c.id = u.id_condominio
    WHERE u.perfil = 3
";
if ($pesquisa) {
    $pesquisaEsc = mysqli_real_escape_string($conn, $pesquisa);
    $sql .= " AND (
                 u.nome LIKE '%$pesquisaEsc%'
              OR u.email LIKE '%$pesquisaEsc%'
              OR u.cpf LIKE '%$pesquisaEsc%'
              OR c.nome LIKE '%$pesquisaEsc%'
             )";
}
$sql .= " ORDER BY u.codigo DESC LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0): ?>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-muted">Nenhum morador encontrado.</td> </tr>
    </tbody>
<?php else: ?>
    <tbody>
        <?php while ($m = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><strong><?= htmlspecialchars($m['nome']) ?></strong></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= htmlspecialchars($m['cpf']) ?></td>
                <td>
                    <?php if ($m['condominio']): ?>
                        <span class="badge bg-info"><?= htmlspecialchars($m['condominio']) ?></span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Não definido</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                        if ($m['status'] == 'pendente') {
                            echo '<span class="badge bg-warning text-dark">Pendente</span>';
                        } elseif ($m['status'] == 'rejeitado') {
                            echo '<span class="badge bg-danger">Rejeitado</span>';
                        } else {
                            echo '<span class="badge bg-success">Ativo</span>';
                        }
                    ?>
                </td> <td>
                    <a href="editar-morador.php?id=<?= $m['codigo'] ?>" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>

                    <?php if ($m['status'] == 'pendente'): ?>
                        <a href="../php_action/reject-morador.php?id=<?= $m['codigo'] ?>" class="btn btn-outline-danger btn-sm me-2"> <i class="fa fa-close"></i> Rejeitar</a>
                        <a href="../php_action/approve-morador.php?id=<?= $m['codigo'] ?>" class="btn btn-outline-success btn-sm"> <i class="fas fa-check-square"></i> Aprovar</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover<?= $m['codigo'] ?>">
                            <i class="fas fa-trash"></i> Apagar
                        </button>

                        <div class="modal fade" id="modalRemover<?= $m['codigo'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $m['codigo'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-body py-4">
                                <h5 class="mb-3 fw-bold text-danger" style="font-size:1.2rem;">Tem certeza que deseja apagar este morador?</h5>
                                <p class="mb-4">Esta ação não pode ser desfeita.</p>
                                <form method="POST" action="../php_action/delete-morador.php">
                                    <input type="hidden" name="id" value="<?= $m['codigo'] ?>">
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