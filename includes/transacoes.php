<?php

require_once __DIR__ . '/../db/conexao.php';

function listarTransacoes($filtros = []) {
    $conn = conectar();

    $sql = "SELECT t.*, c.nome AS categoria_nome FROM transacoes t
            LEFT JOIN categorias c ON t.categoria_id = c.id WHERE 1=1";

    $params = [];
    $types = '';

    if (!empty($filtros['mes'])) {
        $sql .= " AND DATE_FORMAT(t.data, '%Y-%m') = ?";
        $params[] = $filtros['mes'];
        $types .= 's';
    }

    if (!empty($filtros['tipo'])) {
        $sql .= " AND t.tipo = ?";
        $params[] = $filtros['tipo'];
        $types .= 's';
    }

    if (!empty($filtros['categoria_id'])) {
        $sql .= " AND t.categoria_id = ?";
        $params[] = $filtros['categoria_id'];
        $types .= 's';
    }

    $sql .= " ORDER BY t.data DESC, t.criado_em DESC";
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $transacoes = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    return $transacoes;
}

function adicionarTransacao($dados) {
    $conn = conectar();

    $descricao    = $dados['descricao'];
    $valor        = floatval(str_replace(',', '.', $dados['valor']));
    $tipo         = $dados['tipo'];
    $categoria_id = !empty($dados['categoria_id']) ? intval($dados['categoria_id']) : null;
    $data         = $dados['data'];

    $stmt = $conn->prepare(
        "INSERT INTO transacoes (descricao, valor, tipo, categoria_id, data) 
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param('sssss', $descricao, $valor, $tipo, $categoria_id, $data);

    $ok = $stmt->execute();

    if (!$ok) {
        error_log('Erro mysqli ao inserir transação: ' . $stmt->error);
    }

    $id = $conn->insert_id;
    $stmt->close();
    $conn->close();

    return $ok ? $id : false;
}

function excluirTransacao($id) {
    $conn = conectar();

    $stmt = $conn->prepare("DELETE FROM transacoes WHERE id = ?");
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();
    return $ok;
}

function listarCategorias($tipo = null) {
    $conn = conectar();

    if ($tipo) {
        $stmt = $conn->prepare("SELECT * FROM categorias WHERE tipo = ? ORDER BY nome");
        $stmt->bind_param('s', $tipo);
    } else {
        $stmt = $conn->prepare("SELECT * FROM categorias ORDER BY tipo, nome");
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $categorias = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    return $categorias;
}