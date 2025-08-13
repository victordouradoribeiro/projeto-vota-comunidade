<?php
include 'auth.php'; // já faz a verificação de login/perfil
include '../config/conexao.php';

// Verifica se recebeu o ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da votação não informado.");
}

$id_pauta = intval($_GET['id']);

// Busca a votação
$sql = "SELECT p.*, c.nome AS nome_condominio 
        FROM pautas p
        LEFT JOIN usuarios u ON p.id_sindico = u.codigo
        LEFT JOIN condominios c ON u.id_condominio = c.id
        WHERE p.id = $id_pauta";
$result = mysqli_query($conn, $sql);
$votacao = mysqli_fetch_assoc($result);

if (!$votacao) {
    die("Votação não encontrada.");
}

// Busca opções
$sqlOpcoes = "SELECT * FROM opcoes_voto WHERE id_pauta = $id_pauta";
$resultOpcoes = mysqli_query($conn, $sqlOpcoes);
$opcoes = mysqli_fetch_all($resultOpcoes, MYSQLI_ASSOC);
include '../includes/navbar-sindico.php';
include '../includes/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-edit"></i> Editar Votação: <?= htmlspecialchars($votacao['titulo']) ?>
                </h5>
            </div>
            <div class="card-body">
                <form action="php_action/update-votacao.php" method="POST">
                    <input type="hidden" name="id_pauta" value="<?= $votacao['id'] ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titulo" class="form-label">Título da Votação *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo"
                                   value="<?= htmlspecialchars($votacao['titulo']) ?>" maxlength="150" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Condomínio</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($votacao['nome_condominio']) ?>" readonly>
                            <small class="text-muted">Não pode ser alterado</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($votacao['descricao']) ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">Data e Hora de Início *</label>
                            <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio"
                                   value="<?= date('Y-m-d\TH:i', strtotime($votacao['data_inicio'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="data_fim" class="form-label">Data e Hora de Encerramento *</label>
                            <input type="datetime-local" class="form-control" id="data_fim" name="data_fim"
                                   value="<?= date('Y-m-d\TH:i', strtotime($votacao['data_fim'])) ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Opções de Voto *</label>
                        <div id="opcoes-container">
                            <?php foreach ($opcoes as $index => $opcao): ?>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="opcoes[]"
                                           value="<?= htmlspecialchars($opcao['descricao']) ?>" maxlength="100" required>
                                    <?php if ($index >= 2): ?>
                                        <button type="button" class="btn btn-outline-danger remove-opcao">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="add-opcao">
                            <i class="fas fa-plus"></i> Adicionar Opção
                        </button>
                        <small class="text-muted d-block mt-1">Mínimo 2 opções</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Alterar opções pode afetar votos já registrados.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="gerenciar-votacoes.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Atualizar Votação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let opcaoCount = document.querySelectorAll('#opcoes-container input').length;
    document.getElementById('add-opcao').addEventListener('click', function() {
        if (opcaoCount < 10) {
            const container = document.getElementById('opcoes-container');
            const newOpcao = document.createElement('div');
            newOpcao.className = 'input-group mb-2';
            newOpcao.innerHTML = `
                <input type="text" class="form-control" name="opcoes[]" placeholder="Digite a opção ${opcaoCount + 1}" maxlength="100" required>
                <button type="button" class="btn btn-outline-danger remove-opcao">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newOpcao);
            opcaoCount++;
            newOpcao.querySelector('.remove-opcao').addEventListener('click', function() {
                newOpcao.remove();
                opcaoCount--;
            });
        }
    });
    document.querySelectorAll('.remove-opcao').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.remove();
            opcaoCount--;
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>