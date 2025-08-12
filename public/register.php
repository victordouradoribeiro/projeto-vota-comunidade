<?php
include '../config/conexao.php';

$erro = '';
$sucesso = '';

// Busca condomínios para o select
$condominios = [];
$resConds = mysqli_query($conn, "SELECT id, nome FROM condominios ORDER BY nome ASC");
while ($row = mysqli_fetch_assoc($resConds)) {
    $condominios[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $senha = $_POST['senha'];
    $senha_confirmation = $_POST['senha_confirmation'];
    $id_condominio = intval($_POST['id_condominio']);
    $terms = isset($_POST['terms']);

    // Validação básica
    if (!$nome || !$email || !$cpf || !$senha || !$senha_confirmation || !$id_condominio || !$terms) {
        $erro = "Preencha todos os campos obrigatórios e aceite os termos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } elseif ($senha !== $senha_confirmation) {
        $erro = "As senhas não conferem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        // Verifica se já existe email ou cpf
        $emailEsc = mysqli_real_escape_string($conn, $email);
        $cpfEsc = mysqli_real_escape_string($conn, $cpf);
        $check = mysqli_query($conn, "SELECT 1 FROM usuarios WHERE email='$emailEsc' OR cpf='$cpfEsc' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $erro = "Já existe um usuário com este e-mail ou CPF.";
        } else {
            // Insere usuário como morador pendente
            include '../php_action/create-morador.php';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Vota Comunidade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #4338CA;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            padding: 20px 0;
        }
        .cadastro-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin: auto;
        }
        h2 { color: #333; margin-bottom: 30px; font-weight: 600; }
        .form-label { color: #555; text-align: left; display: block; margin-bottom: 8px; font-weight: 500; }
        .form-control { border-radius: 8px; padding: 15px; border: 1px solid #ddd; box-shadow: none; width: 100%; }
        .form-control:focus { border-color: #4338CA; box-shadow: 0 0 0 0.2rem rgba(67, 56, 202, 0.25); outline: none; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e"); background-position: right 12px center; background-repeat: no-repeat; background-size: 16px 12px; padding-right: 40px; }
        .btn-primary { background-color: #4338CA; border-color: #4338CA; border-radius: 8px; padding: 12px 24px; font-weight: 500; font-size: 16px; width: 100%; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #3c31b5; border-color: #3c31b5; transform: translateY(-1px); }
        .btn-primary:focus { background-color: #3c31b5; border-color: #3c31b5; box-shadow: 0 0 0 0.2rem rgba(67, 56, 202, 0.25); }
        .btn-primary:active { transform: translateY(0); }
        .form-check-label { color: #666; font-size: 14px; text-align: left; }
        .form-check-input:checked { background-color: #4338CA; border-color: #4338CA; }
        .form-check-input:focus { border-color: #4338CA; box-shadow: 0 0 0 0.2rem rgba(67, 56, 202, 0.25); }
        .form-check { text-align: left; }
        .login-link { margin-top: 20px; text-align: center; font-size: 14px; color: #666; }
        .login-link a { color: #4338CA; text-decoration: none; font-weight: 500; }
        .login-link a:hover { text-decoration: underline; }
        .alert { border-radius: 8px; margin-bottom: 20px; text-align: left; }
        .alert-success { background-color: #d1fae5; border-color: #a7f3d0; color: #065f46; }
        .alert-danger { background-color: #fee2e2; border-color: #fecaca; color: #991b1b; }
        .form-group { margin-bottom: 20px; margin-right: 32px; }
        .form-control { margin-bottom: 0.5rem; }
        body {
            position: relative;
        }
        body::after {
            content: '';
            display: block;
            height: 10px;
            /* Set same as footer's height */
        }
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 10px;
            color: #fff;
            text-align: center;
        }
        @media (max-width: 768px) {
            body { padding: 10px; }
            .cadastro-container { margin: 10px; padding: 30px 25px; max-width: 100%; }
            h2 { font-size: 24px; margin-bottom: 25px; }
            .form-control { padding: 10px 12px; }
            .btn-primary { padding: 10px 20px; font-size: 15px; }
        }
        @media (max-width: 480px) {
            .cadastro-container { padding: 25px 20px; }
            h2 { font-size: 22px; }
            .form-label { font-size: 14px; }
            .form-control { font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <h2>Criar conta</h2>
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php elseif ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group mb-5 text-start">
                <label for="nome" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Seu nome completo" required value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
            </div>
            <div class="form-group mb-3 text-start">
                <label for="email" class="mt-2 form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="seuemail@exemplo.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            <div class="form-group mb-3 text-start">
                <label for="telefone" class="form-label">Telefone (opcional)</label>
                <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(11) 99999-9999" value="<?= isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : '' ?>">
            </div>
            <div class="form-group mb-3 text-start">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" required value="<?= isset($_POST['cpf']) ? htmlspecialchars($_POST['cpf']) : '' ?>">
            </div>
            <div class="form-group mb-3 text-start">
                <label for="id_condominio" class="form-label">Condomínio</label>
                <select class="form-control" id="id_condominio" name="id_condominio" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($condominios as $cond): ?>
                        <option value="<?= $cond['id'] ?>" <?= (isset($_POST['id_condominio']) && $_POST['id_condominio'] == $cond['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cond['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mb-3 text-start">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
            </div>
            <div class="form-group mb-3 text-start">
                <label for="senha_confirmation" class="form-label">Confirmar senha</label>
                <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation" placeholder="Digite a senha novamente" required>
            </div>
            <div class="form-group form-check mb-3">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    Aceito os termos de uso e política de privacidade
                </label>
            </div>
            <div class="d-grid gap-3">
                <button type="submit" class="btn btn-primary">Criar conta</button>
            </div>
            <div class="login-link">
                Já tem uma conta? <a href="login.php">Faça login!</a>
            </div>
        </form>
    </div>
    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    </script>
</body>
</html>
<?php include '../includes/footer.php'; ?>