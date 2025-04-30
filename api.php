<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'database.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'GET':
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

        if (isset($_GET['id'])) {
            // Get single item (ignora search por enquanto, foca na lista)
            $stmt = $conn->prepare("SELECT t_produto.*, t_laboratorio.nome as nome_laboratorio FROM t_produto JOIN t_laboratorio ON t_produto.id_laboratorio = t_laboratorio.id WHERE t_produto.id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Get all items or filtered items
            $sql = "SELECT t_produto.*, t_laboratorio.nome as nome_laboratorio FROM t_produto JOIN t_laboratorio ON t_produto.id_laboratorio = t_laboratorio.id";
            $params = [];
            if (!empty($searchTerm)) {
                $sql .= " WHERE t_produto.nome LIKE ?";
                $params[] = '%' . $searchTerm . '%';
            }
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($result);
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        // Buscar o maior ID existente e adicionar 1
        $stmt_max_id = $conn->query("SELECT MAX(id) as max_id FROM t_produto");
        $max_id_row = $stmt_max_id->fetch(PDO::FETCH_ASSOC);
        $next_id = ($max_id_row && $max_id_row['max_id'] !== null) ? (int)$max_id_row['max_id'] + 1 : 1;

        // Preparar a inserção incluindo o ID calculado
        $stmt = $conn->prepare("INSERT INTO t_produto (id, id_laboratorio, nome, formula, volume, massa, quantidade) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $next_id,
            $data['id_laboratorio'],
            $data['nome'],
            $data['formula'],
            $data['volume'],
            $data['massa'],
            $data['quantidade']
        ]);

        // Retornar o ID que foi inserido
        echo json_encode(["id" => $next_id]);
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE t_produto SET id_laboratorio = ?, nome = ?, formula = ?, volume = ?, massa = ?, quantidade = ? WHERE id = ?");
        $stmt->execute([$data['id_laboratorio'], $data['nome'], $data['formula'], $data['volume'], $data['massa'], $data['quantidade'], $data['id']]);
        echo json_encode(["success" => true]);
        break;
        
    case 'DELETE':
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM t_produto WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true]);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}

$conn = null;
?>