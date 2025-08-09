<?php include 'includes/header.php'; ?>
    <div class="login-container container py-5" style="max-width: 400px;">
        <h2 class="mb-4 text-center">Faça seu login</h2>

        <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors) && is_array($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3 text-start">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       placeholder="seuemail@exemplo.com" 
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" 
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
<?php include 'includes/footer.php'; ?>
