<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../config/conexao.php';

$nomeEsc = mysqli_real_escape_string($conn, $nome);
$emailEsc = mysqli_real_escape_string($conn, $email);
$telefoneEsc = mysqli_real_escape_string($conn, $telefone);
$cpfEsc = mysqli_real_escape_string($conn, $cpf);
$id_condominioEsc = intval($id_condominio);
$estadoEsc = mysqli_real_escape_string($conn, $estado);
$cidadeEsc = mysqli_real_escape_string($conn, $cidade);
$blocoEsc = mysqli_real_escape_string($conn, $bloco);
$casaEsc = mysqli_real_escape_string($conn, $casa);

$sql = "
    UPDATE usuarios SET 
        nome = '$nomeEsc',
        email = '$emailEsc',
        telefone = '$telefoneEsc',
        cpf = '$cpfEsc',
        id_condominio = $id_condominioEsc,
        estado = '$estadoEsc',
        cidade = '$cidadeEsc',
        bloco = '$blocoEsc',
        casa = '$casaEsc'
    WHERE codigo = $id AND perfil = 3
";

if (mysqli_query($conn, $sql)) {
    $_SESSION['sucesso'] = "Morador atualizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar morador: " . mysqli_error($conn);
    $erro = $_SESSION['erro'];
}
?>