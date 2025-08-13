<?php
session_start(); // Garante que a sessão está ativa
include '../auth.php'; // Inclui a autenticação (deve garantir perfil == 3 para morador)
include '../config/conexao.php';

$erro = '';
$sucesso = '';

// Mensagem vinda do redirecionamento
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}

// Pega os dados do morador logado
$id_morador = $_SESSION['id_usuario'] ?? 0;
if ($id_morador <= 0) {
    die("Erro: usuário não logado.");
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE codigo = ? LIMIT 1");
$stmt->bind_param("i", $id_morador);
$stmt->execute();
$result = $stmt->get_result();
$morador = $result->fetch_assoc();
$stmt->close();

if (!$morador) {
    die("Erro: dados do morador não encontrados.");
}

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $bloco = trim($_POST['bloco']);
    $casa = trim($_POST['casa']);

    // Validação básica dos campos
    if (!$nome || !$email || !$cpf) {
        $erro = "Preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } else {
        // Inclui a ação de atualização específica para o morador
        include '../php_action/update-morador-myaccount.php';
        
        // Atualiza os dados do formulário sem recarregar a página
        $morador = array_merge($morador, $_POST);
    }
}

// Inclui o cabeçalho e a barra de navegação específica para o morador
include '../includes/header.php';
include '../includes/navbar-morador.php';
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
            <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($morador['nome']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($morador['email']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($morador['telefone']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required maxlength="14" value="<?= htmlspecialchars($morador['cpf']) ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="bloco" class="form-label">Bloco</label>
            <input type="text" class="form-control" id="bloco" name="bloco" maxlength="50" value="<?= htmlspecialchars($morador['bloco'] ?? '') ?>">
        </div>
        <div class="mb-3 text-start">
            <label for="casa" class="form-label">Casa</label>
            <input type="text" class="form-control" id="casa" name="casa" maxlength="50" value="<?= htmlspecialchars($morador['casa'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>