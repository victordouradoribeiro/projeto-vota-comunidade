<?php
include '../config/conexao.php';

$id_pauta = intval($_POST['id_pauta']);
$titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
$descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
$data_inicio = mysqli_real_escape_string($conn, $_POST['data_inicio']);
$data_fim = mysqli_real_escape_string($conn, $_POST['data_fim']);
$opcoes = $_POST['opcoes'] ?? [];

if (count($opcoes) < 2) {
    die("Erro: A votação deve ter pelo menos 2 opções.");
}

// Atualiza a pauta
$sql = "UPDATE pautas 
        SET titulo='$titulo', descricao='$descricao', data_inicio='$data_inicio', data_fim='$data_fim' 
        WHERE id=$id_pauta";
if (!mysqli_query($conn, $sql)) {
    die("Erro ao atualizar votação: " . mysqli_error($conn));
}

// Remove opções antigas
mysqli_query($conn, "DELETE FROM opcoes_voto WHERE id_pauta = $id_pauta");

// Insere novas opções
foreach ($opcoes as $opcao) {
    $opcao = mysqli_real_escape_string($conn, trim($opcao));
    if ($opcao !== '') {
        mysqli_query($conn, "INSERT INTO opcoes_voto (id_pauta, descricao) VALUES ($id_pauta, '$opcao')");
    }
}

header("Location: ../gerenciar-votacoes.php?msg=Votação atualizada com sucesso");
exit;