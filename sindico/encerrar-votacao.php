<?php
session_start();
include '../config/conexao.php';

// 1. Verificar se o usuário está logado e tem o perfil de síndico (2)
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 2) {
    $_SESSION['erro'] = "Acesso negado. Apenas síndicos podem encerrar votações.";
    header("Location: ../public/login.php");
    exit;
}

// 2. Verificar se o ID da votação foi recebido via POST
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['erro'] = "ID da votação inválido.";
    header("Location: ../sindico/gerenciar-votacoes.php");
    exit;
}

$idPauta = intval($_POST['id']);
$idSindico = $_SESSION['id_usuario'];

// 3. Verificar se o síndico logado é o criador da votação
$sql_check = "SELECT id FROM pautas WHERE id = ? AND id_sindico = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $idPauta, $idSindico);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) === 0) {
    $_SESSION['erro'] = "Você não tem permissão para encerrar esta votação.";
    header("Location: ../sindico/gerenciar-votacoes.php");
    exit;
}

// 4. Atualizar o status da votação para 'encerrada'
$sql_update = "UPDATE pautas SET status = 'encerrada' WHERE id = ?";
$stmt_update = mysqli_prepare($conn, $sql_update);
mysqli_stmt_bind_param($stmt_update, "i", $idPauta);

if (mysqli_stmt_execute($stmt_update)) {
    $_SESSION['success'] = "Votação encerrada com sucesso!";
} else {
    $_SESSION['error'] = "Erro ao encerrar a votação: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt_check);
mysqli_stmt_close($stmt_update);

// 5. Redirecionar de volta para a página de gerenciamento
header("Location: ../sindico/gerenciar-votacoes.php");
exit;
?>