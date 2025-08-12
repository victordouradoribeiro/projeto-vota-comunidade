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
    $_SESSION['sucesso'] = "Dados do morador atualizados com sucesso!";
    header("Location: ../admin/gerenciar-moradores.php");
    exit;
} else {
    $_SESSION['erro'] = "Erro ao atualizar morador: " . $stmt->error;
    header("Location: ../admin/editar-morador.php?id=" . $id);
    exit;
}
$stmt->close();
?>
