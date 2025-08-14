<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../config/conexao.php';

// Verifique se as variáveis existem antes de usá-las para evitar erros de undefined
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$senha = $_POST['senha'] ?? '';
$id_condominio = $_POST['id_condominio'] ?? 0;

// O campo 'usuario' não está no formulário, então vamos preenchê-lo com o e-mail
// O e-mail já é único e serve como um bom nome de usuário.
$usuario = $email;

// Outros campos não presentes no formulário, defina valores padrão
$estado = '';
$cidade = '';
$bloco = '';
$casa = '';
$perfil = 3;

// Insere usuário com status 'pendente'
$statusDefault = 'pendente';
$senhaHash = md5($senha); // Use um hash mais seguro

// Prepara a consulta SQL com placeholders (?)
$sql = "INSERT INTO usuarios 
        (usuario, senha, status, perfil, nome, email, telefone, cpf, id_condominio, estado, cidade, bloco, casa)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Vincula os parâmetros aos placeholders
    mysqli_stmt_bind_param($stmt, "sssisssssssss", $usuario, $senhaHash, $statusDefault, $perfil, $nome, $email, $telefone, $cpf, $id_condominio, $estado, $cidade, $bloco, $casa);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['sucesso'] = "Seu cadastro foi enviado com sucesso e está pendente de aprovação.";
        header("Location: ../public/login.php");
        exit;
    } else {
        $erro = "Erro ao criar morador: " . mysqli_stmt_error($stmt);
        $_SESSION['erro'] = $erro;
    }
    mysqli_stmt_close($stmt);
} else {
    $erro = "Erro ao preparar a consulta: " . mysqli_error($conn);
    $_SESSION['erro'] = $erro;
}

// Em caso de erro, redireciona de volta para a página de registro
if (!empty($erro)) {
    header("Location: ../public/register.php");
    exit;
}
?>