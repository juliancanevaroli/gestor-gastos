<?php
// Carrega variáveis do arquivo .env, se existir (só necessário em ambiente local)
function carregarEnv($caminho) {
    if (!file_exists($caminho)) return;
    foreach (file($caminho) as $linha) {
        $linha = trim($linha);
        if ($linha === '' || $linha[0] === '#') continue;
        [$chave, $valor] = explode('=', $linha, 2);
        putenv(trim($chave) . '=' . trim($valor));
    }
}
carregarEnv(__DIR__ . '/../.env');

function conectar() {
    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $db   = getenv('DB_NAME') ?: 'gestor_gastos';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die('Erro na conexão: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}