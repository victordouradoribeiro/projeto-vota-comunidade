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
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$statusDefault = 'ativo'; // ou pendente, se quiser aprovar manualmente

// Gerar um username único simples (exemplo: nome sem espaços + número rand)
$baseUser = preg_replace('/\s+/', '', strtolower($nome));
$username = $baseUser;
$i = 1;
while (true) {
    $resCheck = mysqli_query($conn, "SELECT 1 FROM usuarios WHERE usuario = '$username' LIMIT 1");
    if (mysqli_num_rows($resCheck) == 0) break;
    $username = $baseUser . $i;
    $i++;
}

// Insert
$sql = "INSERT INTO usuarios 
(usuario, senha, status, perfil, nome, email, telefone, cpf, id_condominio, estado, cidade, bloco, casa)
VALUES
('$username', '$senhaHash', '$statusDefault', 3, '$nomeEsc', '$emailEsc', '$telefoneEsc', '$cpfEsc', $id_condominioEsc, '$estadoEsc', '$cidadeEsc', '$blocoEsc', '$casaEsc')";

if (mysqli_query($conn, $sql)) {
    $_SESSION['sucesso'] = "Morador criado com sucesso!";
    header("Location: ../admin/gerenciar-sindicos.php");
    exit;
} else {
    $_SESSION['erro'] = "Erro ao criar morador: " . mysqli_error($conn);
    $erro = $_SESSION['erro'];
}
?>