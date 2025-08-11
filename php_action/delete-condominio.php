<?php
include '../config/conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM condominios WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../admin/gerenciar-condominios.php?msg=removido");
        exit;
    } else {
        header("Location: ../admin/gerenciar-condominios.php?erro=1");
        exit;
    }
}