<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 3) {
    // Se o usuário não estiver logado ou não for morador, redireciona para o login
    header("Location: ../public/login.php");
    exit;
}
?>