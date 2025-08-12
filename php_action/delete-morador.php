<?php
include '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Segurança: só apaga se for morador (perfil = 3)
    $check = "SELECT perfil FROM usuarios WHERE codigo = $id LIMIT 1";
    $resCheck = mysqli_query($conn, $check);

    if ($resCheck && $user = mysqli_fetch_assoc($resCheck)) {
        if ($user['perfil'] != 3) {
            header("Location: ../admin/gerenciar-moradores.php?erro=nao_autorizado");
            exit;
        }

        $sql = "DELETE FROM usuarios WHERE codigo = $id";
        if (mysqli_query($conn, $sql)) {
            header("Location: ../admin/gerenciar-moradores.php?msg=removido");
            exit;
        } else {
            header("Location: ../admin/gerenciar-moradores.php?erro=1");
            exit;
        }
    } else {
        header("Location: ../admin/gerenciar-moradores.php?erro=nao_encontrado");
        exit;
    }
}
?>
