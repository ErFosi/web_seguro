<?php
include 'db_connection.php';
$conn = OpenCon();

function generateTableFromResult($result) {
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $html = '<table class="table table-striped">';
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>";
        $html .= "<td><a href='" . htmlspecialchars($actual_link) . "/index.php?flight=" . htmlspecialchars($row['FLIGHT_CODE']) . "'>" . htmlspecialchars($row['FLIGHT_CODE']) . "</a></td>";
        $html .= "<td>" . htmlspecialchars($row['SOURCE']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['DESTINATION']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['ARRIVAL']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['DEPARTURE']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['STATUS']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['DURATION']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['FLIGHTTYPE']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['AIRLINED']) . "</td>";
        $html .= "</tr>\n\r";
    }
    $html .= "</table>";
    return $html;
}

$flightid = null;

if (isset($_GET['flight']) && preg_match("/^[A-Za-z0-9]+$/", $_GET['flight'])) {
    $flightid = $_GET['flight'];
}

if (!$flightid) {
    $query = "SELECT * FROM FLIGHT LIMIT 10";
    $stmt = $conn->prepare($query);
} else {
    $query = "SELECT * FROM FLIGHT WHERE FLIGHT_CODE = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $flightid);
}

$stmt->execute();
$result = $stmt->get_result();

echo generateTableFromResult($result);
?>

</div>
</body>
