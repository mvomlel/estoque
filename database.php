<?php
// Adiciona a porta à string de conexão DSN
$servername = "127.0.0.1"; #localhost
$username = "root"; 
$password = "";
$dbname = "inventario_quimico";
$port = 3306; // Defina a porta do seu banco de dados aqui, 3306 é a padrão do MySQL

try {
    // Adiciona a porta à string de conexão DSN
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit();
}
?>