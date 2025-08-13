<?php
session_start(); // garante que a sessão está ativa
if (!isset($conn)) {
    include '../config/conexao.php';
}

// O ID do síndico é obtido da sessão
$id = $_SESSION['id_usuario'] ?? 0;

if ($id <= 0) {
    die("Erro: usuário não logado.");
}

$stmt = $conn->prepare("
    UPDATE usuarios 
    SET nome=?, email=?, telefone=?, cpf=?, estado=?, cidade=?, bloco=?, casa=?
    WHERE codigo=?
");

$stmt->bind_param(
    "ssssssssi",
    $nome,
    $email,
    $telefone,
    $cpf,
    $estado,
    $cidade,
    $bloco,
    $casa,
    $id
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Seus dados foram atualizados com sucesso!";
    header("Location: ../sindico/dashboard.php");
    exit;
} else {
    $erro = "Erro ao atualizar dados: " . $stmt->error;
}

$stmt->close();
?>
