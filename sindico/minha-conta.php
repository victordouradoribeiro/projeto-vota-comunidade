<?php
session_start(); // garante que a sessão está ativa
include '../auth.php';
include '../config/conexao.php';

$erro = '';
$sucesso = '';

// Mensagem vinda do redirecionamento
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}

// Pega os dados do síndico logado
$id = $_SESSION['id_usuario'] ?? 0;
if ($id <= 0) {
    die("Erro: usuário não logado.");
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE codigo = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$sindico = $result->fetch_assoc();
$stmt->close();

if (!$sindico) {
    die("Erro: dados do síndico não encontrados.");
}

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $estado = trim($_POST['estado']);
    $cidade = trim($_POST['cidade']);
    $bloco = trim($_POST['bloco']);
    $casa = trim($_POST['casa']);

    if (!$nome || !$email || !$cpf) {
        $erro = "Preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } else {
        include '../php_action/update-sindico-myaccount.php';
        // Atualiza os dados do formulário sem recarregar
        $sindico = array_merge($sindico, $_POST);
    }
}

include '../includes/header.php';
include '../includes/navbar-sindico.php';
?>
<div class="container mt-4">
    <h2>Minha Conta</h2>
    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3 text-start">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($sindico['nome']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($sindico['email']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($sindico['telefone']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required maxlength="14" value="<?= htmlspecialchars($sindico['cpf']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" maxlength="50" value="<?= htmlspecialchars($sindico['estado'] ?? '') ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" class="form-control" id="cidade" name="cidade" maxlength="100" value="<?= htmlspecialchars($sindico['cidade'] ?? '') ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="bloco" class="form-label">Bloco</label>
            <input type="text" class="form-control" id="bloco" name="bloco" maxlength="50" value="<?= htmlspecialchars($sindico['bloco'] ?? '') ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="casa" class="form-label">Casa</label>
            <input type="text" class="form-control" id="casa" name="casa" maxlength="50" value="<?= htmlspecialchars($sindico['casa'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
