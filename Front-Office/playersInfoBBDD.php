<?php
require "autenticarUsuario.php";
$usuario_autenticado = autenticar();

require "connection.php";
$conn = connect();


// Parámetros de paginación
$pageSize = 24; // Jugadores por página
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Página actual

// Construir la consulta SQL base
$sql = "SELECT p.id, p.first_name, p.last_name, p.number, p.position, p.draft, p.country, t.full_name as team_name 
        FROM final_players p
        INNER JOIN final_teams t ON p.team_id = t.id";

// Inicializar condiciones y parámetros
$conditions = [];
$params = [];

// Verificar si se ha enviado el formulario de búsqueda
if (isset($_GET['name']) || isset($_GET['team']) || isset($_GET['position']) || isset($_GET['draft']) || isset($_GET['country'])) {
    // Agregar condiciones de búsqueda según los campos llenados en el formulario
    if (!empty($_GET['name'])) {
        $conditions[] = "(p.first_name LIKE ? OR p.last_name LIKE ?)";
        $params[] = "%" . $_GET['name'] . "%";
        $params[] = "%" . $_GET['name'] . "%";
    }
    if (!empty($_GET['team'])) {
        $conditions[] = "t.full_name LIKE ?";
        $params[] = "%" . $_GET['team'] . "%";
    }
    if (!empty($_GET['position'])) {
        $conditions[] = "p.position LIKE ?";
        $params[] = $_GET['position'];
    }
    if (!empty($_GET['draft'])) {
        $conditions[] = "p.draft = ?";
        $params[] = $_GET['draft'];
    }
    if (!empty($_GET['country'])) {
        $conditions[] = "p.country LIKE ?";
        $params[] = "%" . $_GET['country'] . "%";
    }

    // Combinar condiciones de búsqueda
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
}

// Preparar la consulta SQL
$stmt = $conn->prepare($sql);

// Verificar si hay parámetros para enlazar antes de llamar a bind_param
if (!empty($params)) {
    // Construir la cadena de tipos de parámetros
    $paramTypes = str_repeat('s', count($params));

    // Pasar los parámetros a bind_param
    $stmt->bind_param($paramTypes, ...$params);
}

// Ejecutar la consulta SQL
$stmt->execute();

// Ejecutar la consulta SQL
$stmt->execute();
$result = $stmt->get_result();

// Calcular el número total de registros después de la consulta
$totalRecords = $result->num_rows;

// Calcular el índice de inicio para la consulta
$offset = ($page - 1) * $pageSize;

// Agregar paginación a la consulta SQL
$sql .= " LIMIT ?, ?";
$params[] = $offset;
$params[] = $pageSize;

// Preparar y ejecutar la consulta SQL nuevamente con los límites de paginación
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Cerrar la declaración
$stmt->close();

// Cerrar la conexión
$conn->close();

// ! mirar filtro
function buildFilterQueryString() {
    $queryString = '';
    if (!empty($_GET['name'])) {
        $queryString .= "&name=".$_GET['name'];
    }
    if (!empty($_GET['team'])) {
        $queryString .= "&team=".$_GET['team'];
    }
    if (!empty($_GET['position'])) {
        $queryString .= "&position=".$_GET['position'];
    }
    if (!empty($_GET['draft'])) {
        $queryString .= "&draft=".$_GET['draft'];
    }
    if (!empty($_GET['country'])) {
        $queryString .= "&country=".$_GET['country'];
    }
    return $queryString;
}