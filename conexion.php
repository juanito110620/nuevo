<?php
$conn = new mysqli("localhost", "root", "", "koyot");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>



<?php
$host = "localhost";
$db   = "koyot";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR DB: " . $e->getMessage());
}
?>

<?php
$host = "localhost";
$db   = "koyot";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
