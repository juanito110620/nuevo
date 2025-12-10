<?php
session_start();
require_once "conexion.php"; // CONEXIÓN PDO REAL

$error = '';

// Si ya está logueado → mandar a su panel
if (isset($_SESSION["rol_id"])) {
    switch ($_SESSION["rol_id"]) {
        case 1: header("Location: Admin/dashboard.php"); exit;
        case 2: header("Location: Director/dashboard.php"); exit;
        case 3: header("Location: Supervisores/dashboard.php"); exit;
        case 4: header("Location: Agentes/dashboard.php"); exit;
        case 5: header("Location: Clientes/dashboard.php"); exit;
        case 6: header("Location: Rh/dashboard.php"); exit;
        case 7: header("Location: Auditores/dashboard.php"); exit;
    }
}

// PROCESAR LOGIN REAL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {

        // Buscar usuario en BD real
        $stmt = $conn->prepare("
            SELECT u.*, r.nombre AS rol_nombre
            FROM usuarios u
            LEFT JOIN roles r ON r.id = u.rol_id
            WHERE u.usuario = ?
            LIMIT 1
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (password_verify($password, $user['password'])) {

                // Guardar sesión
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['usuario'];
                $_SESSION['rol_id']   = $user['rol_id'];
                $_SESSION['rol']      = $user['rol_nombre'];
                $_SESSION['fullname'] = $user['nombre'];

                // Redirección por rol
                switch ($user['rol_id']) {
                    case 1: header("Location: Admin/dashboard.php"); exit;
                    case 2: header("Location: Director/dashboard.php"); exit;
                    case 3: header("Location: Supervisores/dashboard.php"); exit;
                    case 4: header("Location: Agentes/dashboard.php"); exit;
                    case 5: header("Location: Clientes/dashboard.php"); exit;
                    case 6: header("Location: Rh/dashboard.php"); exit;
                    case 7: header("Location: Auditores/dashboard.php"); exit;
                    default:
                        $error = "El rol no está autorizado.";
                }

            } else {
                $error = "Contraseña incorrecta.";
            }

        } else {
            $error = "El usuario no existe.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login | KOYOT</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

body{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    background:radial-gradient(circle at top, #0c2f2f, #000);
}

/* CONTENEDOR */
.login-container{
    background:linear-gradient(180deg, #041d1d, #000);
    padding:45px 55px;
    border-radius:25px;
    box-shadow:
        0 0 30px rgba(191,160,70,0.15),
        inset 0 0 20px rgba(191,160,70,0.05);
    width:360px;
    text-align:center;
    position:relative;
    border:1px solid rgba(191,160,70,0.2);
    animation: fadeIn 1s ease;
}

/* LOGO */
.login-container .logo{
    width:120px;
    margin-bottom:20px;
    border-radius:12px;
    box-shadow:0 0 20px rgba(191,160,70,0.4);
}

/* TÍTULO */
.login-container h1{
    font-size:24px;
    margin-bottom:35px;
    letter-spacing:1px;
    background:linear-gradient(90deg, #BFA046, #A8873C, #9A7730);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    text-shadow:0 0 12px rgba(191,160,70,0.6);
}

/* INPUTS */
.login-container input{
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

.login-container input:focus{
    box-shadow:0 0 12px rgba(191,160,70,0.5);
    border-color:#BFA046;
}

/* BOTÓN */
.login-container button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg, #BFA046, #A8873C, #9A7730);
    font-size:1.1em;
    font-weight:700;
    color:#1a1203;
    cursor:pointer;
    box-shadow:
        0 0 20px rgba(191,160,70,0.6),
        inset 0 0 8px rgba(255,255,255,0.2);
    transition:0.3s ease;
    animation: pulse 2s infinite;
}

.login-container button:hover{
    transform:translateY(-2px);
    box-shadow:
        0 0 30px rgba(191,160,70,0.9),
        inset 0 0 10px rgba(255,255,255,0.3);
}

/* ERROR */
#errorMsg{
    position:absolute;
    top:-55px;
    left:50%;
    transform:translateX(-50%);
    background:linear-gradient(135deg,#c0392b,#922b21);
    color:#fff;
    padding:12px 24px;
    border-radius:14px;
    font-weight:600;
    box-shadow:0 8px 20px rgba(0,0,0,.6);
    opacity:<?= empty($error) ? '0' : '1' ?>;
    transition:.5s;
}

/* TEXTO */
p{
    margin-top:14px;
    font-size:0.9em;
    color:#ccc;
}

a{
    color:#BFA046;
    text-decoration:none;
    font-weight:600;
}
a:hover{ text-decoration:underline; }

/* ANIMACIONES */
@keyframes pulse{
    0%{box-shadow:0 0 18px rgba(191,160,70,.4);}
    50%{box-shadow:0 0 32px rgba(191,160,70,.9);}
    100%{box-shadow:0 0 18px rgba(191,160,70,.4);}
}

@keyframes fadeIn{
    from{opacity:0;transform:scale(.9);}
    to{opacity:1;transform:scale(1);}
}
</style>
</head>

<body>

<div class="login-container">
    <div id="errorMsg"><?= htmlspecialchars($error) ?></div>

    <img src="LOGO CORPORATIVO 2024 png.png" class="logo" alt="Logo">
    <h1>Acceso al Sistema</h1>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>

    <p>¿Olvidaste tu contraseña?  
        <a href="recuperar_password.php">Recuperar</a>
    </p>
</div>

</body>
</html>
