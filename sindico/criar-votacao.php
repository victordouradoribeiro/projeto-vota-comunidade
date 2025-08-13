<?php
include 'auth.php'; // proteger acesso
include '../config/conexao.php';

// Buscar condomínios para popular select
$result = mysqli_query($conn, "SELECT id, nome FROM condominios ORDER BY nome");
$condominios = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Variáveis para feedback e dados pré-preenchidos (útil após erro)
$errors = [];
$old = [
    'titulo' => '',
    'id_condominio' => '',
    'descricao' => '',
    'data_inicio' => '',
    'data_fim' => '',
    'opcoes' => ['', ''] // sempre pelo menos 2
];

if (isset($_SESSION['old_data'])) {
    $old = $_SESSION['old_data'];
    unset($_SESSION['old_data']);
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

include '../includes/header.php';
include '../includes/navbar-sindico.php'; // Crie um navbar-sindico.php para o menu do síndico
?>

<div class="container mt-4">
    <h1 class="mb-4">Criar Nova Votação</h1>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="../php_action/create-poll.php" method="POST">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="titulo" class="form-label">Título da Votação *</label>
                <input type="text" name="titulo" id="titulo" class="form-control" maxlength="150" required
                    value="<?= htmlspecialchars($old['titulo']) ?>">
            </div>
            <div class="col-md-6">
                <label for="id_condominio" class="form-label">Condomínio *</label>
                <select name="id_condominio" id="id_condominio" class="form-select" required>
                    <option value="">Selecione um condomínio</option>
                    <?php foreach ($condominios as $cond): ?>
                        <option value="<?= $cond['id'] ?>" <?= ($old['id_condominio'] == $cond['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cond['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="3" placeholder="Descreva os detalhes da votação..."><?= htmlspecialchars($old['descricao']) ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="data_inicio" class="form-label">Data e Hora de Início *</label>
                <input type="datetime-local" name="data_inicio" id="data_inicio" class="form-control" required
                    value="<?= htmlspecialchars($old['data_inicio']) ?>">
            </div>
            <div class="col-md-6">
                <label for="data_fim" class="form-label">Data e Hora de Encerramento *</label>
                <input type="datetime-local" name="data_fim" id="data_fim" class="form-control" required
                    value="<?= htmlspecialchars($old['data_fim']) ?>">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Opções de Voto *</label>
            <div id="opcoes-container">
                <?php
                $opcoesCount = count($old['opcoes']);
                for ($i=0; $i < max(2, $opcoesCount); $i++): ?>
                    <div class="input-group mb-2">
                        <input type="text" name="opcoes[]" maxlength="100" class="form-control" placeholder="Digite a opção <?= $i+1 ?>" value="<?= isset($old['opcoes'][$i]) ? htmlspecialchars($old['opcoes'][$i]) : '' ?>" required>
                        <?php if($i >= 2): ?>
                            <button type="button" class="btn btn-outline-danger remove-opcao">
                                <i class="fas fa-times"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="add-opcao">
                <i class="fas fa-plus"></i> Adicionar Opção
            </button>
            <small class="text-muted d-block mt-1">Mínimo 2 opções</small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="dashboard.php" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Criar Votação</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let opcaoCount = document.querySelectorAll('#opcoes-container input').length;

    document.getElementById('add-opcao').addEventListener('click', function() {
        if (opcaoCount < 10) {
            const container = document.getElementById('opcoes-container');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <input type="text" name="opcoes[]" maxlength="100" class="form-control" placeholder="Digite a opção ${opcaoCount + 1}" required>
                <button type="button" class="btn btn-outline-danger remove-opcao">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
            opcaoCount++;

            div.querySelector('.remove-opcao').addEventListener('click', function() {
                div.remove();
                opcaoCount--;
            });
        }
    });

    // Remover opções
    document.querySelectorAll('.remove-opcao').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.parentElement.remove();
            opcaoCount--;
        });
    });

    // Data mínima para inputs datetime-local
    const now = new Date();
    const nowString = now.toISOString().slice(0,16);
    document.getElementById('data_inicio').min = nowString;
    document.getElementById('data_fim').min = nowString;

    document.getElementById('data_inicio').addEventListener('change', function() {
        document.getElementById('data_fim').min = this.value;
    });
});
</script>

<?php include '../includes/footer.php'; ?>