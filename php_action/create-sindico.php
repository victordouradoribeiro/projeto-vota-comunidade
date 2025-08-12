<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($conn)) {
    include '../config/conexao.php';
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO usuarios (usuario, nome, email, telefone, cpf, estado, cidade, bloco, casa, senha, id_condominio, perfil, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ativo')
");
$stmt->bind_param(
    "ssssssssssis",
    $usuario,
    $nome,
    $email,
    $telefone,
    $cpf,
    $estado,
    $cidade,
    $bloco,
    $casa,
    $senhaHash,
    $id_condominio,
    $perfil
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Síndico adicionado com sucesso!";
    header("Location: ../admin/gerenciar-sindicos.php");
    exit;
} else {
    $erro = "Erro ao adicionar síndico: " . $stmt->error;
}
$stmt->close();
?>
