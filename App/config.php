<?php


$host = 'decameron.ccqtchjlqpmx.us-east-1.rds.amazonaws.com';
$db   = 'postgres';
$user = 'decameron';
$pass = 'Gtr1anadelgado';

$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();  // Termina el script aquí para evitar ejecutar el resto del código
}
?>
