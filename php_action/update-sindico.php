<?php
if (!isset($conn)) {
    include '../config/conexao.php';
}
$idEsc = intval($id);
$nomeEsc = mysqli_real_escape_string($conn, $nome);
$emailEsc = mysqli_real_escape_string($conn, $email);
$telefoneEsc = mysqli_real_escape_string($conn, $telefone);
$cpfEsc = mysqli_real_escape_string($conn, $cpf);
$id_condominioEsc = intval($id_condominio);
$perfilEsc = intval($perfil);
$statusEsc = mysqli_real_escape_string($conn, $status);

$sql = "UPDATE usuarios SET 
    nome='$nomeEsc',
    email='$emailEsc',
    telefone='$telefoneEsc',
    cpf='$cpfEsc',
    id_condominio=$id_condominioEsc,
    perfil=$perfilEsc,
    status='$statusEsc'
    WHERE codigo=$idEsc
";
if (mysqli_query($conn, $sql)) {
    $sucesso = "Dados do síndico atualizados com sucesso!";
    echo "<meta http-equiv='refresh' content='2;url=gerenciar-sindicos.php'>";
    exit;
} else {
    $erro = "Erro ao atualizar síndico: " . mysqli_error($conn);
}