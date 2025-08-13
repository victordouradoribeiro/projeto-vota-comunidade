<?php
session_start();
include '../config/conexao.php';

// Verifica se o ID foi passado e se é um número válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = "ID inválido para aprovação.";
    header("Location: ../admin/gerenciar-moradores.php");
    exit;
}

$id = intval($_GET['id']);

// Prepara a consulta SQL para aprovar o morador
// O status é atualizado para 'ativo' somente se o perfil for 3 (morador)
$sql = "UPDATE usuarios SET status = 'ativo' WHERE codigo = ? AND perfil = 3";

// Usa prepared statement para prevenir SQL Injection
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    // Verifica se alguma linha foi afetada para garantir que o usuário existia
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['sucesso'] = "Morador aprovado com sucesso!";
    } else {
        $_SESSION['erro'] = "Morador não encontrado ou já está ativo.";
    }
} else {
    $_SESSION['erro'] = "Erro ao aprovar morador: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);

// Redireciona de volta para a página de gerenciamento de moradores
header("Location: ../admin/gerenciar-moradores.php");
exit;
?>