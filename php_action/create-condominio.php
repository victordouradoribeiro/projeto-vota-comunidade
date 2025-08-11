<?php
if (!isset($conn)) {
    include '../config/conexao.php';
}
if (isset($nome) && isset($endereco)) {
    $nomeEsc = mysqli_real_escape_string($conn, $nome);
    $enderecoEsc = mysqli_real_escape_string($conn, $endereco);
    $telefoneEsc = mysqli_real_escape_string($conn, $telefone);
    $cepEsc = mysqli_real_escape_string($conn, $cep);

    $sql = "INSERT INTO condominios (nome, endereco, telefone, cep) VALUES ('$nomeEsc', '$enderecoEsc', '$telefoneEsc', '$cepEsc')";
    if (mysqli_query($conn, $sql)) {
        $sucesso = "Condomínio cadastrado com sucesso!";
        echo "<meta http-equiv='refresh' content='2;url=../admin/gerenciar-condominios.php'>";
        exit;
    } else {
        $erro = "Erro ao cadastrar condomínio: " . mysqli_error($conn);
    }
}