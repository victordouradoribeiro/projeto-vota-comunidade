<?php
session_start();
include '../config/conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = "ID inválido para aprovação.";
    header("Location: ../admin/gerenciar-sindicos.php");
    exit;
}

$id = intval($_GET['id']);

// Atualizar status para ativo
$sql = "UPDATE usuarios SET status = 'ativo' WHERE codigo = $id AND perfil = 2";
if (mysqli_query($conn, $sql)) {
    $_SESSION['sucesso'] = "Síndico aprovado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao aprovar síndico: " . mysqli_error($conn);
}

header("Location: ../admin/gerenciar-sindicos.php");
exit;
?>