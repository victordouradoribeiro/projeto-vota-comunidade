<?php
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

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
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover<?= $c['id'] ?>">
                        <i class="fas fa-trash"></i> Apagar
                    </button>
                    <!-- Modal de confirmação -->
                    <div class="modal fade" id="modalRemover<?= $c['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $c['id'] ?>" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-body py-4">
                            <h5 class="mb-3 fw-bold text-danger" style="font-size:1.2rem;">Tem certeza que deseja apagar este condomínio?</h5>
                            <p class="mb-4">Esta ação não pode ser desfeita.</p>
                            <form method="POST" action="../php_action/delete-condominio.php">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn btn-danger me-2">Apagar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
<?php endif; ?>