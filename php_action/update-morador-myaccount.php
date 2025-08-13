<?php
session_start(); // garante que a sessão está ativa
if (!isset($conn)) {
    include '../config/conexao.php';
}

// O ID do morador é obtido da sessão
$id = $_SESSION['id_usuario'] ?? 0;

if ($id <= 0) {
    die("Erro: usuário não logado.");
}

// Pega os dados do POST
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$telefone = trim($_POST['telefone']);
$cpf = trim($_POST['cpf']);
$bloco = trim($_POST['bloco']);
$casa = trim($_POST['casa']);

$stmt = $conn->prepare("
    UPDATE usuarios 
    SET nome=?, email=?, telefone=?, cpf=?, bloco=?, casa=?
    WHERE codigo=?
");

$stmt->bind_param(
    "ssssssi",
    $nome,
    $email,
    $telefone,
    $cpf,
    $bloco,
    $casa,
    $id
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Seus dados foram atualizados com sucesso!";
    // Redireciona para a página de minha conta do morador
    header("Location: ../morador/minha-conta.php");
    exit;
} else {
    $erro = "Erro ao atualizar dados: " . $stmt->error;
}

$stmt->close();
?>