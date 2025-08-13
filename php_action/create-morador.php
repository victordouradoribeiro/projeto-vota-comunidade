<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../config/conexao.php';

$usuarioEsc = mysqli_real_escape_string($conn, $usuario);
$nomeEsc = mysqli_real_escape_string($conn, $nome);
$emailEsc = mysqli_real_escape_string($conn, $email);
$telefoneEsc = mysqli_real_escape_string($conn, $telefone);
$cpfEsc = mysqli_real_escape_string($conn, $cpf);
$id_condominioEsc = intval($id_condominio);
$estadoEsc = mysqli_real_escape_string($conn, $estado);
$cidadeEsc = mysqli_real_escape_string($conn, $cidade);
$blocoEsc = mysqli_real_escape_string($conn, $bloco);
$casaEsc = mysqli_real_escape_string($conn, $casa);
$senhaHash = md5($senha);
$statusDefault = 'ativo'; // ou pendente, se quiser aprovar manualmente

// Insert
$sql = "INSERT INTO usuarios 
(usuario, senha, status, perfil, nome, email, telefone, cpf, id_condominio, estado, cidade, bloco, casa)
VALUES
('$usuarioEsc', '$senhaHash', '$statusDefault', 3, '$nomeEsc', '$emailEsc', '$telefoneEsc', '$cpfEsc', $id_condominioEsc, '$estadoEsc', '$cidadeEsc', '$blocoEsc', '$casaEsc')";

if (mysqli_query($conn, $sql)) {
    $_SESSION['sucesso'] = "Morador criado com sucesso!";
    header("Location: ../admin/gerenciar-moradores.php");
    exit;
} else {
    $_SESSION['erro'] = "Erro ao criar morador: " . mysqli_error($conn);
    $erro = $_SESSION['erro'];
}
?>