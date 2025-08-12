<?php
session_start();
include '../config/conexao.php'; // Sua conexão com mysqli

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Escapa a entrada do usuário para evitar SQL Injection
    $login = $_POST['login'];
    $senha = $_POST['password'];

    // Prepara a consulta usando prepared statements
    $sql = "SELECT codigo, perfil, senha FROM usuarios 
            WHERE (usuario = ? OR email = ?) 
              AND status = 'ativo' 
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    // Liga os parâmetros (s = string)
    mysqli_stmt_bind_param($stmt, "ss", $login, $login);

    // Executa a consulta
    mysqli_stmt_execute($stmt);

    // Obtém o resultado
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verifica a senha fornecida com o hash armazenado
        if (md5($senha) === $user['senha']) {
            // Login bem-sucedido, cria as variáveis de sessão
            $_SESSION['id_usuario'] = $user['codigo'];
            $_SESSION['perfil'] = $user['perfil'];

            // Redireciona conforme perfil
            if ($user['perfil'] == 1) {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['perfil'] == 2) {
                header("Location: ../sindico/dashboard.php"); 
            } else {
                header("Location: ../morador/dashboard.php"); 
            }
            exit;
        }
    }
    // Se o login falhou (usuário não encontrado ou senha incorreta)
    $erro = "Login ou senha inválidos!";
}

include '../includes/header.php';
?>
<style>
    body {
    /* Cor de fundo roxa escura: #4338CA */
    background-color: #4338CA;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Garante que o corpo ocupe 100% da altura da viewport */
    margin: 0;
    font-family: 'Inter', sans-serif; /* Fonte Inter */
}

.login-container {
    background-color: #fff; /* Fundo branco para o formulário de login */
    padding: 40px;
    border-radius: 12px; /* Cantos arredondados */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Sombra */
    width: 100%;
    max-width: 450px; /* Largura máxima para o container */
    text-align: center;
}

h2 {
    color: #333; /* Cor do título */
    margin-bottom: 30px;
    font-weight: 600; /* Negrito para o título */
}

.form-label {
    color: #555; /* Cor para os rótulos dos campos */
    text-align: left;
    display: block; /* Garante que o rótulo ocupe sua própria linha */
    margin-bottom: 8px;
    font-weight: 500;
}

.form-control {
    border-radius: 8px; /* Cantos arredondados para os inputs */
    padding: 12px 15px;
    border: 1px solid #ddd; /* Borda sutil */
    box-shadow: none; /* Remove a sombra padrão */
}

.form-control:focus {
    border-color: #4338CA; /* Cor da borda ao focar */
    box-shadow: none; /* Remove o contorno/sombra ao focar */
}

.btn-primary {
    background-color: #4338CA; /* Roxo principal para o botão */
    border-color: #4338CA;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
    font-size: 16px;
    width: 100%;
}

.btn-primary:hover {
    background-color: #3c31b5; /* Tom um pouco mais escuro no hover */
    border-color: #3c31b5;
}

.btn-primary:focus {
    background-color: #3c31b5;
    border-color: #3c31b5;
    box-shadow: 0 0 0 0.2rem rgba(67, 56, 202, 0.25); /* Sombra de foco com a cor roxa */
}

.form-check-label {
    color: #666; /* Cor para o texto do checkbox */
    font-size: 14px;
}

.form-check-input:checked {
    background-color: #4338CA; /* Cor de fundo quando marcado */
    border-color: #4338CA; /* Cor da borda quando marcado */
}

.form-check-input:focus {
    border-color: #4338CA;
    box-shadow: 0 0 0 0.2rem rgba(67, 56, 202, 0.25);
}

.forgot-password {
    margin-top: 20px;
    text-align: center;
}

.forgot-password a {
    color: #4338CA; /* Cor roxa para o link */
    text-decoration: none;
    font-size: 14px;
}

.forgot-password a:hover {
    text-decoration: underline;
}

.register-link {
    margin-top: 15px;
    text-align: center;
    font-size: 14px;
    color: #666;
}

.register-link a {
    color: #4338CA; /* Cor roxa para o link de registro */
    text-decoration: none;
    font-weight: 500;
}

.register-link a:hover {
    text-decoration: underline;
}

/* Estilização para as credenciais de teste */
.text-muted {
    text-align: center;
    font-size: 12px;
    margin-top: 20px;
}

.text-muted small {
    display: block;
    margin-bottom: 2px;
}

/* Responsividade */
@media (max-width: 768px) {
    .login-container {
        margin: 20px;
        padding: 30px 25px;
    }
    
    h2 {
        font-size: 24px;
        margin-bottom: 25px;
    }
}
</style>

<div class="login-container container py-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Faça seu login</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="mb-3 text-start">
            <label for="login" class="form-label">E-mail ou Usuário</label>
            <input type="text" 
                   class="form-control" 
                   id="login" 
                   name="login" 
                   placeholder="seuemail@exemplo.com ou usuário" 
                   value="<?= isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '' ?>" 
                   required>
        </div>
        
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Senha</label>
            <input type="password" 
                   class="form-control" 
                   id="password" 
                   name="password" 
                   placeholder="********" 
                   required>
        </div>
        
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">
                Manter-se conectado
            </label>
        </div>
        
        <div class="d-grid gap-3">
            <button type="submit" class="btn btn-primary">Entrar</button>
        </div>
        
        <div class="forgot-password mt-3">
            <a href="forgot-password.php">Esqueceu sua senha?</a>
        </div>
        
        <div class="register-link mt-2">
            Não tem uma conta? <a href="register.php">Registre-se agora!</a>
        </div>
    </form>

    <hr class="my-4">
</div>
<?php include '../includes/footer.php'; ?>