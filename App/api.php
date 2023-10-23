<?php
// Permite el acceso CORS desde cualquier origen
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'config.php'; // Importa la configuración de la base de datos

$requestMethod = $_SERVER["REQUEST_METHOD"]; // Obtiene el método de la solicitud HTTP (GET, POST, PUT, DELETE)
$tipo = isset($_GET['type']) ? $_GET['type'] : null; // Obtiene el tipo (hotel o habitacion)

switch ($requestMethod) {
    case 'GET':
        // Obtener hoteles o habitaciones
        if ($tipo == 'hotel') {
            $stmt = $pdo->query("SELECT * FROM Hotel"); // Consulta todos los hoteles
            echo json_encode($stmt->fetchAll());
        } elseif ($tipo == 'habitacion') {
            $hotelId = $_GET['hotel_id'];
            $stmt = $pdo->prepare("SELECT * FROM TipoHabitacion WHERE hotel_id = :hotelId"); // Consulta tipos de habitación por hotel
            $stmt->bindParam(':hotelId', $hotelId);
            $stmt->execute();
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        // Crear un nuevo hotel o tipo de habitación
        $inputJSON = file_get_contents('php://input');
        $inputData = json_decode($inputJSON, true);
        if ($tipo == 'hotel') {
            $stmt = $pdo->prepare("INSERT INTO Hotel (nombre, direccion, ciudad, NIT, numeroHabitaciones) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$inputData['nombre'], $inputData['direccion'], $inputData['ciudad'], $inputData['NIT'], $inputData['numeroHabitaciones']]);
            $inputData['id'] = $pdo->lastInsertId(); // Obtiene el ID del último registro insertado
            echo json_encode($inputData);
        } elseif ($tipo == 'habitacion') {
            $stmt = $pdo->prepare("INSERT INTO TipoHabitacion (tipo, acomodacion, cantidad, hotel_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$inputData['tipo'], $inputData['acomodacion'], $inputData['cantidad'], $inputData['hotel_id']]);
            $inputData['id'] = $pdo->lastInsertId(); // Obtiene el ID del último registro insertado
            echo json_encode($inputData);
        }
        break;

    case 'PUT':
        // Actualizar hotel o tipo de habitación
        $inputJSON = file_get_contents('php://input');
        $inputData = json_decode($inputJSON, true);
        if ($tipo == 'hotel') {
            $stmt = $pdo->prepare("UPDATE Hotel SET nombre = ?, direccion = ?, ciudad = ?, NIT = ?, numeroHabitaciones = ? WHERE id = ?");
            $stmt->execute([$inputData['nombre'], $inputData['direccion'], $inputData['ciudad'], $inputData['NIT'], $inputData['numeroHabitaciones'], $inputData['id']]);
            echo json_encode($inputData);
        } elseif ($tipo == 'habitacion') {
            $stmt = $pdo->prepare("UPDATE TipoHabitacion SET tipo = ?, acomodacion = ?, cantidad = ? WHERE id = ?");
            $stmt->execute([$inputData['tipo'], $inputData['acomodacion'], $inputData['cantidad'], $inputData['id']]);
            echo json_encode($inputData);
        }
        break;

    case 'DELETE':
        // Eliminar hotel o tipo de habitación
        if ($tipo == 'hotel') {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("DELETE FROM Hotel WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'Hotel eliminado']);
        } elseif ($tipo == 'habitacion') {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("DELETE FROM TipoHabitacion WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'Tipo habitación eliminado']);
        }
        break;

    default:
        // Manejo de métodos no soportados
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
?>
