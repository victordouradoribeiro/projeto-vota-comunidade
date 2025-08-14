<?php
// Este script é chamado pelo administrador, portanto o morador é criado como 'ativo'
if (session_status() === PHP_SESSION_NONE) session_start();
include '../config/conexao.php';

// Acesso restrito ao administrador (perfil 1)
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 1) {
    header("Location: ../public/login.php");
    exit;
}

// Verifica se os dados do POST foram enviados
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: ../admin/adicionar-morador.php");
    exit;
}

// Obtém os dados do POST
$usuario = $_POST['usuario'] ?? '';
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$estado = $_POST['estado'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$bloco = $_POST['bloco'] ?? '';
$casa = $_POST['casa'] ?? '';
$senha = $_POST['senha'] ?? '';
$id_condominio = $_POST['id_condominio'] ?? 0;
$perfil = $_POST['perfil'] ?? 3; // Mantém 3 como padrão para segurança

// Validações básicas no servidor
if (empty($usuario) || empty($nome) || empty($email) || empty($senha) || empty($id_condominio)) {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios!";
    header("Location: ../admin/adicionar-morador.php");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido!";
    header("Location: ../admin/adicionar-morador.php");
    exit;
}

// Hash da senha
$senhaHash = md5($senha);

// Status 'ativo' pois é um cadastro feito pelo administrador
$statusDefault = 'pendente'; // Pode ser 'ativo' ou 'pendente' dependendo da lógica do sistema

// Prepared Statement para inserir os dados com segurança
$sql = "INSERT INTO usuarios 
        (usuario, senha, status, perfil, nome, email, telefone, cpf, id_condominio, estado, cidade, bloco, casa)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssisssssssss", 
        $usuario, $senhaHash, $statusDefault, $perfil, $nome, $email, $telefone, $cpf, $id_condominio, $estado, $cidade, $bloco, $casa);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['sucesso'] = "Morador criado com sucesso!";
        header("Location: ../admin/gerenciar-moradores.php");
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao criar morador: " . mysqli_stmt_error($stmt);
        header("Location: ../admin/adicionar-morador.php");
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['erro'] = "Erro ao preparar a consulta: " . mysqli_error($conn);
    header("Location: ../admin/adicionar-morador.php");
    exit;
}
?>