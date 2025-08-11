<?php
include 'auth.php';
include '../config/conexao.php';
$currentPage = 'condominios';
include '../includes/navbar-admin.php';
include '../includes/header.php';

$erro = '';
$sucesso = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca dados do condomínio
$sql = "SELECT * FROM condominios WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
$condominio = mysqli_fetch_assoc($result);

if (!$condominio) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Condomínio não encontrado.</div></div>";
    include '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $cep = trim($_POST['cep']);

    if (!$nome || !$endereco) {
        $erro = "Nome e endereço são obrigatórios!";
    } else {
        include '../php_action/update-condominio.php';
        // Atualiza dados para exibir no formulário após edição
        $condominio = array_merge($condominio, [
            'nome' => $nome,
            'endereco' => $endereco,
            'telefone' => $telefone,
            'cep' => $cep
        ]);
    }
}
?>

<div class="container mt-4">
    <h2>Editar Condomínio</h2>
    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="row mb-3 align-items-center">
            <label for="nome" class="col-form-label text-start fw-bold">Nome:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($condominio['nome']) ?>">
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <label for="endereco" class="col-form-label text-start fw-bold">Endereço:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="endereco" name="endereco" required value="<?= htmlspecialchars($condominio['endereco']) ?>">
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <label for="telefone" class="col-form-label text-start fw-bold">Telefone:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($condominio['telefone']) ?>">
            </div>
        </div>
        <div class="row mb-4 align-items-center">
            <label for="cep" class="col-form-label text-start fw-bold">CEP:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($condominio['cep']) ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="gerenciar-condominios.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>