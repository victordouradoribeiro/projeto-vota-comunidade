<?php
if (!isset($conn)) {
    include '../config/conexao.php';
}
$idEsc = intval($id);
$nomeEsc = mysqli_real_escape_string($conn, $nome);
$enderecoEsc = mysqli_real_escape_string($conn, $endereco);
$telefoneEsc = mysqli_real_escape_string($conn, $telefone);
$cepEsc = mysqli_real_escape_string($conn, $cep);

$sql = "UPDATE condominios SET nome='$nomeEsc', endereco='$enderecoEsc', telefone='$telefoneEsc', cep='$cepEsc' WHERE id=$idEsc";
if (mysqli_query($conn, $sql)) {
    $sucesso = "Condomínio atualizado com sucesso!";
    echo "<meta http-equiv='refresh' content='2;url=gerenciar-condominios.php'>";
    exit;
} else {
    $erro = "Erro ao atualizar condomínio: " . mysqli_error($conn);
}