<?php
if (!isset($conn)) {
    include '../config/conexao.php';
}

$stmt = $conn->prepare("
    UPDATE usuarios 
    SET nome=?, email=?, telefone=?, cpf=?, estado=?, cidade=?, bloco=?, casa=?, id_condominio=?, perfil=?, status=?
    WHERE codigo=?
");

$stmt->bind_param(
    "ssssssssissi",
    $nome,
    $email,
    $telefone,
    $cpf,
    $estado,
    $cidade,
    $bloco,
    $casa,
    $id_condominio,
    $perfil,
    $status,
    $id
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Dados do síndico atualizados com sucesso!";
    header("Location: ../admin/gerenciar-sindicos.php");
    exit;
} else {
    $erro = "Erro ao atualizar síndico: " . $stmt->error;
}
$stmt->close();
?>
