<?php
session_start();
include '../config/conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = "ID inválido para exclusão.";
    header("Location: ../admin/gerenciar-moradores.php");
    exit;
}

$id = intval($_GET['id']);

// Segurança: não deletar admins nem síndicos
$sqlCheck = "SELECT perfil FROM usuarios WHERE codigo = $id LIMIT 1";
$resCheck = mysqli_query($conn, $sqlCheck);
$user = mysqli_fetch_assoc($resCheck);
if (!$user) {
    $_SESSION['erro'] = "Morador não encontrado.";
    header("Location: ../admin/gerenciar-moradores.php");
    exit;
}
if ($user['perfil'] != 3) {
    $_SESSION['erro'] = "Não é permitido apagar usuário que não seja morador.";
    header("Location: ../admin/gerenciar-moradores.php");
    exit;
}

$sql = "DELETE FROM usuarios WHERE codigo = $id";

if (mysqli_query($conn, $sql)) {
    $_SESSION['sucesso'] = "Morador apagado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao apagar morador: " . mysqli_error($conn);
}

header("Location: ../admin/gerenciar-moradores.php");
exit;
?>