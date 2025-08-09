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
        if (md5($senha, $user['senha'])) {
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
            <a href="#">Esqueceu sua senha?</a>
        </div>
        
        <div class="register-link mt-2">
            Não tem uma conta? <a href="#">Registre-se agora!</a>
        </div>
    </form>

    <hr class="my-4">
</div>
<?php include '../includes/footer.php'; ?>