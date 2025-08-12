<?php
$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    if (!$email) {
        $erro = "Informe o e-mail cadastrado.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } else {
        // Simula envio de e-mail
        $sucesso = "Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Vota Comunidade</title>
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
            width: 100%;
            max-width: 500px;
            margin: auto;
        }
        h2 {
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center; 
        }
        .form-label {
            color: #555;
            text-align: left;
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-control {
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #ddd;
            box-shadow: none;
            width: 100%;
        }
        .form-control:focus {
            border-color: #4338CA;
            box-shadow: 0 0 0 0.2rem rgba(67, 56, 202, 0.25);
            outline: none;
        }
        .btn-primary {
            background-color: #4338CA;
            border-color: #4338CA;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 15px;
            color: white;
        }
        .btn-primary:hover {
            background-color: #3c31b5;
            border-color: #3c31b5;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #fff;
            border: 1px solid #4338CA;
            color: #4338CA;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #f3f3f3;
            border-color: #3c31b5;
            color: #3c31b5;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
            padding: 16px 20px;
        }
        .alert-success {
            background-color: #d1fae5;
            border-color: #a7f3d0;
            color: #065f46;
        }
        .alert-danger {
            background-color: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
        }
        .form-group {
            margin-bottom: 20px;
            margin-right: 32px;
        }
        body { position: relative; }
        body::after { content: ''; display: block; height: 10px; }
        footer { position: absolute; bottom: 0; width: 100%; height: 10px; color: #fff; text-align: center; }
        @media (max-width: 768px) {
            body { padding: 10px; }
            .cadastro-container { margin: 10px; padding: 30px 25px; max-width: 100%; }
            h2 { font-size: 24px; margin-bottom: 25px; }
            .form-control { padding: 10px 12px; }
            .btn-primary, .btn-secondary { padding: 10px 20px; font-size: 15px; }
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
        <h2>Recuperar Senha</h2>
        <p class="mb-4" style="margin-bottom: 24px; text-align: left;">
            Para redefinir sua senha, informe o e-mail cadastrado na sua conta.<br>
            Você receberá um link com instruções para criar uma nova senha.
        </p>
        <?php if ($sucesso): ?>
            <div class="alert alert-success text-center" id="success-alert"><?= htmlspecialchars($sucesso) ?></div>
        <?php elseif ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group mb-3 text-start">
                <label for="email" class="form-label">E-mail</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       placeholder="seuemail@exemplo.com"
                       required
                       autofocus
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                       oninput="document.getElementById('success-alert')?.remove();">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Enviar link de redefinição</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='login.php'">Cancelar</button>
            </div>
        </form>
    </div>
    <script>
        // Esconde a mensagem de sucesso após 3 segundos
        setTimeout(function () {
            document.getElementById('success-alert')?.remove();
        }, 3000);
    </script>
</body>
</html>
<?php include '../includes/footer.php'; ?>