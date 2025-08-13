<?php
session_start();
include 'auth.php'; // deve garantir session_start() e perfil == 3
include '../config/conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 3) {
    echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_pauta = $_POST['id_pauta'] ?? null;
$id_opcao = $_POST['id_opcao'] ?? null;

if (!$id_pauta || !$id_opcao) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
    exit;
}

// Verifica se já votou
$stmt = $conn->prepare("SELECT 1 FROM votos WHERE id_usuario = ? AND id_pauta = ?");
$stmt->bind_param("ii", $id_usuario, $id_pauta);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Você já votou nesta pauta.']);
    exit;
}

// Insere voto
$stmt = $conn->prepare("INSERT INTO votos (id_usuario, id_opcao, id_pauta) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $id_usuario, $id_opcao, $id_pauta);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao registrar voto.']);
}
