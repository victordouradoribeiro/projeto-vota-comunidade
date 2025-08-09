<?php
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $database = "clientes_db";
    
    $conn = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_error()) { 
        die("Erro na Conexão: " . mysqli_connect_error());
    }   
?>