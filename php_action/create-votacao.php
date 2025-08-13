<?php
include '../config/conexao.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 2) {
    header("Location: ../public/login.php");
    exit;
}

$idSindico = $_SESSION['id_usuario'];

// Receber dados do POST
$titulo = trim($_POST['titulo'] ?? '');
$id_condominio = $_POST['id_condominio'] ?? '';
$descricao = trim($_POST['descricao'] ?? '');
$data_inicio = $_POST['data_inicio'] ?? '';
$data_fim = $_POST['data_fim'] ?? '';
$opcoes = $_POST['opcoes'] ?? [];

$errors = [];
$old_data = [
    'titulo' => $titulo,
    'id_condominio' => $id_condominio,
    'descricao' => $descricao,
    'data_inicio' => $data_inicio,
    'data_fim' => $data_fim,
    'opcoes' => $opcoes,
];

// Validações básicas
if (empty($titulo)) {
    $errors[] = "O título da votação é obrigatório.";
}

if (empty($id_condominio) || !is_numeric($id_condominio)) {
    $errors[] = "Selecione um condomínio válido.";
}

if (empty($data_inicio) || empty($data_fim)) {
    $errors[] = "Informe data e hora de início e encerramento.";
} elseif ($data_inicio >= $data_fim) {
    $errors[] = "A data de início deve ser anterior à data de encerramento.";
}

if (!is_array($opcoes) || count($opcoes) < 2) {
    $errors[] = "Informe ao menos duas opções de voto.";
} else {
    foreach ($opcoes as $opcao) {
        if (trim($opcao) === '') {
            $errors[] = "Todas as opções devem ser preenchidas.";
            break;
        }
    }
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = $old_data;
    header("Location: ../sindico/criar-votacao.php");
    exit;
}

// Escapar dados para query
$tituloEsc = mysqli_real_escape_string($conn, $titulo);
$id_condominioEsc = (int)$id_condominio;
$descricaoEsc = mysqli_real_escape_string($conn, $descricao);
$data_inicioEsc = mysqli_real_escape_string($conn, $data_inicio);
$data_fimEsc = mysqli_real_escape_string($conn, $data_fim);

// Inserir na tabela pautas
$sql = "INSERT INTO pautas (titulo, descricao, data_inicio, data_fim, status, id_sindico) 
        VALUES ('$tituloEsc', '$descricaoEsc', '$data_inicioEsc', '$data_fimEsc', 'ativa', $idSindico)";
if (!mysqli_query($conn, $sql)) {
    $_SESSION['errors'] = ["Erro ao criar votação: " . mysqli_error($conn)];
    $_SESSION['old_data'] = $old_data;
    header("Location: ../sindico/criar-votacao.php");
    exit;
}

$idPauta = mysqli_insert_id($conn);

// Inserir opções de voto
$stmt = mysqli_prepare($conn, "INSERT INTO opcoes_voto (id_pauta, descricao) VALUES (?, ?)");
if (!$stmt) {
    $_SESSION['errors'] = ["Erro ao preparar inserção das opções: " . mysqli_error($conn)];
    $_SESSION['old_data'] = $old_data;
    header("Location: ../sindico/criar-votacao.php");
    exit;
}

foreach ($opcoes as $opcao) {
    $opcaoEsc = trim($opcao);
    if ($opcaoEsc === '') continue;
    mysqli_stmt_bind_param($stmt, "is", $idPauta, $opcaoEsc);
    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['errors'] = ["Erro ao inserir opção: " . mysqli_stmt_error($stmt)];
        $_SESSION['old_data'] = $old_data;
        header("Location: ../sindico/criar-votacao.php");
        exit;
    }
}
mysqli_stmt_close($stmt);

$_SESSION['success'] = "Votação criada com sucesso!";
header("Location: ../sindico/dashboard.php");
exit;
