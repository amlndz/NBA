<?php
require "autenticarUsuario.php";

include "connection.php";
$conn = connect();

$pageSize = 24;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$sql = "SELECT p.id, p.first_name, p.last_name, p.number, p.position, p.draft, p.country, t.full_name as team_name 
        FROM final_players p
        INNER JOIN final_teams t ON p.team_id = t.id";

$conditions = [];
$params = [];

if (isset($_GET['name']) || isset($_GET['team']) || isset($_GET['position']) || isset($_GET['draft']) || isset($_GET['country'])) {
    if (!empty($_GET['name'])) {
        $nameParts = explode(' ', $_GET['name'], 2);
        if (count($nameParts) == 2) {
            $conditions[] = "(p.first_name LIKE ? AND p.last_name LIKE ?)";
            $params[] = "%" . $nameParts[0] . "%";
            $params[] = "%" . $nameParts[1] . "%";
        } else {
            $conditions[] = "(p.first_name LIKE ? OR p.last_name LIKE ?)";
            $params[] = "%" . $_GET['name'] . "%";
            $params[] = "%" . $_GET['name'] . "%";
        }
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

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $paramTypes = str_repeat('s', count($params));
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$totalRecords = $result->num_rows;

$offset = ($page - 1) * $pageSize;

$sql .= " LIMIT ?, ?";
$params[] = $offset;
$params[] = $pageSize;

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();

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

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo "<div id='players-container' class='container-fluid pt-5'><div class='container'><div class='row'>";
    while ($row = $result->fetch_assoc()) {
        $playerId = $row['id'];
        $url = "playerInfo.php?id=".urlencode($playerId);
        echo "
        <div class='col-lg-4 mb-4'>
            <div class='row align-items-center'>
                <div class='col-sm-5'>
                    <a href='$url'><img class='img-fluid mb-3 mb-sm-0' src='./assets/img/players/".$playerId.".avif' alt='img' onerror=\"this.onerror=null;this.src='./assets/img/players/default.avif'\"></a>
                </div>
                <div class='col-sm-7'>
                    <h4><a hrefa class='player-name' href='$url'>".$row['first_name']." ".$row['last_name']."</a></h4>
                    <p class='m-0'>
                        Dorsal: ".$row['number']."<br/>Equipo: ". $row['team_name']."<br/>Posición: ".$row['position']."<br/>Draft: ".($row['draft'] ? $row['draft'] : "N/A")."<br/>Nacionalidad: ".$row['country']."
                    </p>
                </div>
            </div>
        </div>";
    }
    echo "</div></div></div>";

    // Calcular el número total de páginas
    $totalPages = ceil($totalRecords / $pageSize);

    // Mostrar controles de navegación
    echo "<div id='pagination-container' class='pagination-container'><div class='pagination'>";
    if ($page > 1) {
        echo "<a href='?page=".($page - 1).buildFilterQueryString()."'>&laquo; Anterior</a>";
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=".$i.buildFilterQueryString()."'".($page == $i ? " class='active'" : "").">$i</a>";
    }
    if ($page < $totalPages) {
        echo "<a href='?page=".($page + 1).buildFilterQueryString()."'>Siguiente &raquo;</a>";
    }
    echo "</div></div>";

    exit;
}
