<?php
// funciones.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/conexion.php';

function registrar_auditoria($conn, $usuario_id, $accion, $descripcion = null) {
    $sql = "INSERT INTO auditoria (usuario_id, accion, descripcion) VALUES (:usuario_id, :accion, :descripcion)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':usuario_id'=>$usuario_id, ':accion'=>$accion, ':descripcion'=>$descripcion]);
}

function usuario_actual_id() {
    return $_SESSION['user_id'] ?? null;
}

function flash_set($key, $msg) {
    $_SESSION['flash'][$key] = $msg;
}
function flash_get($key) {
    $m = $_SESSION['flash'][$key] ?? null;
    if (isset($_SESSION['flash'][$key])) unset($_SESSION['flash'][$key]);
    return $m;
}

/**
 * Subir archivo (imagen / pdf)
 * @param string $inputName
 * @param string $destFolder
 * @return string|null ruta guardada o null
 */
function subir_archivo($inputName, $destFolder = 'uploads/fotos') {
    if (!isset($_FILES[$inputName]) || empty($_FILES[$inputName]['tmp_name'])) return null;
    if (!file_exists($destFolder)) mkdir($destFolder, 0755, true);
    $tmp = $_FILES[$inputName]['tmp_name'];
    $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $filename = $destFolder . '/' . $basename . '.' . $ext;
    if (move_uploaded_file($tmp, $filename)) return $filename;
    return null;
}
