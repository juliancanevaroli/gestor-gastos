<?php
function conectar() {
    $conn = new mysqli('localhost', 'SEU_USUARIO', 'SUA_SENHA', 'gestor_gastos');
    if ($conn->connect_error) {
        die('Erro na conexão: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}