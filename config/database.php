<?php
// config/database.php
// Configuração de conexão com o banco de dados

// Usar variáveis de ambiente do Docker ou valores padrão
define('DB_HOST', getenv('MYSQL_HOST') ?: 'localhost');
define('DB_USER', getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: 'root');
define('DB_NAME', getenv('MYSQL_DB') ?: 'volx_db');

try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verifica conexão
    if ($mysqli->connect_error) {
        die('Erro na conexão: ' . $mysqli->connect_error);
    }
    
    // Define charset
    $mysqli->set_charset('utf8mb4');
    
} catch (Exception $e) {
    die('Erro ao conectar ao banco: ' . $e->getMessage());
}
?>
