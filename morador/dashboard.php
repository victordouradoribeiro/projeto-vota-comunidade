<?php
include 'auth.php';
include '../config/conexao.php';

$idSindico = (int) $_SESSION['id_usuario'];
$currentPage = 'morador';

include '../includes/header.php';
include '../includes/navbar-morador.php';
?>