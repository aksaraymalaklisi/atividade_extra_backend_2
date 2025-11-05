<?php
$host = 'db'; // Nome do serviço MySQL no docker-compose.yml
$db   = 'cadastro_db';
$user = 'app_user';
$pass = 'app_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Em um ambiente de produção, você não exibiria a mensagem de erro completa
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// --- CÓDIGO PARA CRIAÇÃO DA TABELA ---
$sql_create_table = "
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);";

try {
    $pdo->exec($sql_create_table);
} catch (PDOException $e) {
    echo "Erro ao criar tabela: " . $e->getMessage();
}

?>