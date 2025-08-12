<?php
session_start();
include '../config/conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = "ID inválido para exclusão.";
    header("Location: ../admin/gerenciar-sindicos.php");
    exit;
}

$id = intval($_GET['id']);

// Evitar deletar administradores, por segurança (opcional)
$sqlCheck = "SELECT perfil FROM usuarios WHERE codigo = $id LIMIT 1";
$resCheck = mysqli_query($conn, $sqlCheck);
$user = mysqli_fetch_assoc($resCheck);
if (!$user) {
    $_SESSION['erro'] = "Síndico não encontrado.";
    header("Location: ../admin/gerenciar-sindicos.php");
    exit;
}
if ($user['perfil'] == 1) {
    $_SESSION['erro'] = "Não é permitido apagar um administrador.";
    header("Location: ../admin/gerenciar-sindicos.php");
    exit;
}

// Deletar síndico
$sql = "DELETE FROM usuarios WHERE codigo = $id";
if (mysqli_query($conn, $sql)) {
    $_SESSION['sucesso'] = "Síndico apagado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao apagar síndico: " . mysqli_error($conn);
}

header("Location: ../admin/gerenciar-sindicos.php");
exit;
?>