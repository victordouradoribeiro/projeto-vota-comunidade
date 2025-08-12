<?php
$nomeEsc = mysqli_real_escape_string($conn, $nome);
$emailEsc = mysqli_real_escape_string($conn, $email);
$telefoneEsc = mysqli_real_escape_string($conn, $telefone);
$cpfEsc = mysqli_real_escape_string($conn, $cpf);
$senhaHash = md5($senha); // Troque para password_hash() se quiser mais segurança
$id_condominioEsc = intval($id_condominio);

$sql = "INSERT INTO usuarios (usuario, senha, status, perfil, nome, email, telefone, cpf, id_condominio) VALUES (
    '$emailEsc', '$senhaHash', 'pendente', 3, '$nomeEsc', '$emailEsc', '$telefoneEsc', '$cpfEsc', $id_condominioEsc
)";
if (mysqli_query($conn, $sql)) {
    $sucesso = "Cadastro realizado com sucesso! Aguarde aprovação do administrador.";
} else {
    $erro = "Erro ao cadastrar: " . mysqli_error($conn);
}