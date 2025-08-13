<?php
include 'auth.php';
include '../config/conexao.php';
$currentPage = 'sindicos';
include '../includes/navbar-admin.php';
include '../includes/header.php';

$erro = '';
$sucesso = '';

// Mensagem vinda do redirecionamento
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ID inválido.</div></div>";
    include '../includes/footer.php';
    exit;
}

// Busca dados do síndico
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE codigo = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$sindico = $result->fetch_assoc();
$stmt->close();

if (!$sindico) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Síndico não encontrado.</div></div>";
    include '../includes/footer.php';
    exit;
}

// Busca condomínios
$condominios = [];
$resConds = mysqli_query($conn, "SELECT id, nome FROM condominios ORDER BY nome ASC");
while ($row = mysqli_fetch_assoc($resConds)) {
    $condominios[] = $row;
}

// Perfis disponíveis
$perfis = [
    1 => 'Administrador',
    2 => 'Síndico',
    3 => 'Morador'
];

// Status disponíveis
$statusList = [
    'ativo' => 'Ativo',
    'pendente' => 'Pendente',
    'rejeitado' => 'Rejeitado'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $estado = trim($_POST['estado']);
    $cidade = trim($_POST['cidade']);
    $bloco = trim($_POST['bloco']);
    $casa = trim($_POST['casa']);
    $id_condominio = intval($_POST['id_condominio']);
    $perfil = intval($_POST['perfil']);
    $status = $_POST['status'];

    if (!$nome || !$email || !$cpf || !$id_condominio || !$perfil || !$status) {
        $erro = "Preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } else {
        include '../php_action/update-sindico.php';
        // Atualiza dados para exibir no formulário sem precisar recarregar
        $sindico = array_merge($sindico, $_POST);
    }
}
?>
<div class="container mt-4">
    <h2>Editar Síndico</h2>
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
        <div class="mb-3 text-start">
            <label for="id_condominio" class="form-label">Condomínio</label>
            <select class="form-control" id="id_condominio" name="id_condominio" required>
                <option value="">Selecione...</option>
                <?php foreach ($condominios as $cond): ?>
                    <option value="<?= $cond['id'] ?>" <?= ($sindico['id_condominio'] == $cond['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cond['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 text-start">
            <label for="perfil" class="form-label">Perfil</label>
            <select class="form-control" id="perfil" name="perfil" required>
                <?php foreach ($perfis as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($sindico['perfil'] == $key) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 text-start">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <?php foreach ($statusList as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($sindico['status'] == $key) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="gerenciar-sindicos.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>