<?php
include 'auth.php';
include '../config/conexao.php';
$currentPage = 'moradores';
include '../includes/navbar-admin.php';
include '../includes/header.php';

$erro = '';
$sucesso = '';

// Mensagem de sucesso do redirecionamento
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}

// Lista de condomínios
$condominios = [];
$resConds = mysqli_query($conn, "SELECT id, nome FROM condominios ORDER BY nome ASC");
while ($row = mysqli_fetch_assoc($resConds)) {
    $condominios[] = $row;
}

// Perfis (fixo como Morador, mas array para futura expansão)
$perfis = [
    3 => 'Morador'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $estado = trim($_POST['estado']);
    $cidade = trim($_POST['cidade']);
    $bloco = trim($_POST['bloco']);
    $casa = trim($_POST['casa']);
    $senha = trim($_POST['senha']);
    $id_condominio = intval($_POST['id_condominio']);
    $perfil = intval($_POST['perfil']);

    if (!$nome || !$email || !$senha || !$id_condominio) {
        $erro = "Preencha todos os campos obrigatórios!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido!";
    } else {
        include '../php_action/create-morador.php';
    }
}
?>

<div class="container mt-4">
    <h2>Adicionar Morador</h2>
    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="usuario" class="form-label fw-bold">Usuário (login):</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
        </div>
        <div class="mb-3">
            <label for="nome" class="form-label fw-bold">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label fw-bold">Telefone:</label>
            <input type="text" class="form-control" id="telefone" name="telefone">
        </div>

        <div class="mb-3">
            <label for="cpf" class="form-label fw-bold">CPF:</label>
            <input type="text" class="form-control" id="cpf" name="cpf">
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label fw-bold">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label fw-bold">Estado:</label>
            <input type="text" class="form-control" id="estado" name="estado" maxlength="50">
        </div>
        
        <div class="mb-3">
            <label for="cidade" class="form-label fw-bold">Cidade:</label>
            <input type="text" class="form-control" id="cidade" name="cidade" maxlength="100">
        </div>

        <div class="mb-3">
            <label for="bloco" class="form-label fw-bold">Bloco:</label>
            <input type="text" class="form-control" id="bloco" name="bloco" maxlength="50">
        </div>

        <div class="mb-3">
            <label for="casa" class="form-label fw-bold">Casa:</label>
            <input type="text" class="form-control" id="casa" name="casa" maxlength="50">
        </div>

        <div class="mb-3 text-start">
            <label for="id_condominio" class="form-label fw-bold">Condomínio:</label>
            <select class="form-control" id="id_condominio" name="id_condominio" required>
                <option value="">Selecione um condomínio</option>
                <?php foreach ($condominios as $cond): ?>
                    <option value="<?= $cond['id'] ?>"><?= htmlspecialchars($cond['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3 text-start">
            <label for="perfil" class="form-label fw-bold">Perfil:</label>
            <select class="form-control" id="perfil" name="perfil" required>
                <?php foreach ($perfis as $key => $label): ?>
                    <option value="<?= $key ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="gerenciar-moradores.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>