<?php
session_start();
require_once "conexion.php";
require_once "funciones.php";

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');

    if ($usuario !== '') {
        // Verificamos si el usuario existe
        $stmt = $conn->prepare("SELECT id, correo FROM usuarios WHERE usuario = ? AND estado = 'Activo' LIMIT 1");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Aquí puedes generar un token de recuperación y enviarlo por correo
            // Ejemplo simplificado:
            $token = bin2hex(random_bytes(16));
            $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
            $stmt->execute([$token, $user['id']]);

            // Enviar correo con enlace de recuperación (aquí solo mensaje de ejemplo)
            $mensaje = "Se ha enviado un correo a <strong>{$user['correo']}</strong> con instrucciones para restablecer la contraseña.";
        } else {
            $mensaje = "El usuario no existe o está inactivo.";
        }
    } else {
        $mensaje = "Debe ingresar un usuario válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar Contraseña | KOYOT</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

*{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif;}

body{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    background:radial-gradient(circle at top, #0c2f2f, #000);
}

.container{
    background:linear-gradient(180deg, #041d1d, #000);
    padding:40px 50px;
    border-radius:25px;
    box-shadow:0 0 30px rgba(191,160,70,0.15);
    width:360px;
    text-align:center;
    border:1px solid rgba(191,160,70,0.2);
}

.container h1{
    font-size:24px;
    margin-bottom:30px;
    background:linear-gradient(90deg, #BFA046, #A8873C, #9A7730);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    text-shadow:0 0 12px rgba(191,160,70,0.6);
}

.container input{
    width:100%;
    padding:14px 16px;
    margin-bottom:18px;
    border-radius:12px;
    border:1px solid rgba(191,160,70,0.25);
    background:#0b1f1f;
    color:#fff;
    font-size:14px;
    outline:none;
    transition:0.3s;
}

.container input:focus{
    box-shadow:0 0 12px rgba(191,160,70,0.5);
    border-color:#BFA046;
}

.container button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg, #BFA046, #A8873C, #9A7730);
    font-size:1.1em;
    font-weight:700;
    color:#1a1203;
    cursor:pointer;
    box-shadow:0 0 20px rgba(191,160,70,0.6);
    transition:0.3s ease;
}

.container button:hover{
    transform:translateY(-2px);
    box-shadow:0 0 30px rgba(191,160,70,0.9);
}

#mensaje{
    margin-bottom:15px;
    padding:10px 15px;
    border-radius:12px;
    background:rgba(191,160,70,0.2);
    color:#fff;
    font-weight:600;
    font-size:0.9em;
}
</style>
</head>
<body>

<div class="container">
    <h1>Recuperar Contraseña</h1>

    <?php if($mensaje): ?>
        <div id="mensaje"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Ingrese su usuario" required>
        <button type="submit">Enviar instrucciones</button>
    </form>

    <p style="margin-top:12px; color:#ccc;">Recordaste tu contraseña? <a href="login.php" style="color:#BFA046;">Ingresar</a></p>
</div>

</body>
</html>
