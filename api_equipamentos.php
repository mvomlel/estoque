<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

            // Busca todos os equipamentos com nome do laboratório
            $sql = "SELECT e.id, e.nome, e.estado, e.id_laboratorio, l.nome as nome_laboratorio 
                    FROM t_equip e 
                    LEFT JOIN t_laboratorio l ON e.id_laboratorio = l.id";
            
            $params = [];
            if (!empty($searchTerm)) {
                $sql .= " WHERE e.nome LIKE ?"; // Adiciona filtro por nome
                $params[] = '%' . $searchTerm . '%';
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($equipamentos);
            break;

        case 'POST':
            // Insere um novo equipamento
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['nome']) || empty($data['estado']) || !isset($data['id_laboratorio'])) {
                 http_response_code(400); // Bad Request
                 echo json_encode(['success' => false, 'message' => 'Dados incompletos para inserir equipamento.']);
                 exit;
            }
            // Buscar o maior ID existente e adicionar 1
            $stmt_max_id = $conn->query("SELECT MAX(id) as max_id FROM t_equip");
            $max_id_row = $stmt_max_id->fetch(PDO::FETCH_ASSOC);
            $next_id = ($max_id_row && $max_id_row['max_id'] !== null) ? (int)$max_id_row['max_id'] + 1 : 1;


            $sql = "INSERT INTO t_equip (id, nome, estado, id_laboratorio) VALUES (:id, :nome, :estado, :id_laboratorio)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $next_id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':estado', $data['estado']);
            $stmt->bindParam(':id_laboratorio', $data['id_laboratorio'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $lastId = $conn->lastInsertId();
                echo json_encode(['id' => $lastId, 'success' => true, 'message' => 'Equipamento inserido com sucesso.']);
            } else {
                 http_response_code(500); // Internal Server Error
                 echo json_encode(['success' => false, 'message' => 'Erro ao inserir equipamento.']);
            }
            break;

        case 'PUT':
            // Atualiza um equipamento existente
             $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['id']) || empty($data['nome']) || empty($data['estado']) || !isset($data['id_laboratorio'])) {
                 http_response_code(400); // Bad Request
                 echo json_encode(['success' => false, 'message' => 'Dados incompletos para atualizar equipamento.']);
                 exit;
            }

            $sql = "UPDATE t_equip SET nome = :nome, estado = :estado, id_laboratorio = :id_laboratorio WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':estado', $data['estado']);
            $stmt->bindParam(':id_laboratorio', $data['id_laboratorio'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                 if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true, 'message' => 'Equipamento atualizado com sucesso.']);
                 } else {
                    // Nenhum registro foi alterado (talvez o ID não exista ou os dados são os mesmos)
                    echo json_encode(['success' => false, 'message' => 'Nenhum equipamento encontrado com o ID fornecido ou dados inalterados.']);
                 }
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar equipamento.']);
            }
            break;

        case 'DELETE':
            // Exclui um equipamento
            if (!isset($_GET['id'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'message' => 'ID do equipamento não fornecido para exclusão.']);
                exit;
            }
            
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

            if ($id === false || $id <= 0) {
                 http_response_code(400); // Bad Request
                 echo json_encode(['success' => false, 'message' => 'ID do equipamento inválido.']);
                 exit;
            }

            $sql = "DELETE FROM t_equip WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

             if ($stmt->execute()) {
                 if ($stmt->rowCount() > 0) {
                     echo json_encode(['success' => true, 'message' => 'Equipamento excluído com sucesso.']);
                 } else {
                    // Nenhum registro foi deletado (provavelmente ID não existe)
                     echo json_encode(['success' => false, 'message' => 'Nenhum equipamento encontrado com o ID fornecido.']);
                 }
             } else {
                 http_response_code(500); // Internal Server Error
                 echo json_encode(['success' => false, 'message' => 'Erro ao excluir equipamento.']);
             }
            break;

        default:
            // Método não permitido
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
     http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
}

// Fechar conexão (opcional, pois o PHP geralmente faz isso no final do script)
// $conn = null;

?> 