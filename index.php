<?php
include_once 'bd.php';
session_start();

if (isset($_COOKIE['correo'])) {
    $correo = $_COOKIE['correo'];
} else {
    $correo = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    if (!empty($correo) && !empty($contraseña)) {
        $consulta = mysqli_query($bd, "SELECT * FROM usuario WHERE correo = '$correo'");
        
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $usuario = mysqli_fetch_assoc($consulta);
            if (password_verify($contraseña, $usuario['contraseña'])) {
                $_SESSION["id_usuario"] = $usuario['id_usuario'];

                if (isset($_POST['recordar'])) {
                    setcookie('correo', $correo, time() + (7 * 24 * 60 * 60), "/");
                } else {
                    setcookie('correo', '', time() - 3600, "/");
                }

                header("Location: principal.php");
                exit();
            } else {
                echo "<script>alert('El correo y la contraseña no coinciden.');</script>";
            }
        } else {
            echo "<script>alert('El correo y la contraseña no coinciden.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="form">
    <div class="envoltorio">
        <form method="post" action="">
            <h2>Acceso</h2>
            <div class="entrada">
                <input type="text" name="correo" placeholder="Correo electrónico" value="<?php echo $correo; ?>" required>
            </div>

            <div class="entrada">
                <input type="password" name="contraseña" placeholder="Contraseña" required>
            </div>

            <div class="recordar">
                <label>
                    <input type="checkbox" name="recordar" <?php if (isset($_COOKIE['correo'])) echo 'checked'; ?>> Recordarme
                </label>
            </div>

            <button type="submit" class="boton" name="boton">Acceder</button>

            <div class="enlace">
                <p>¿No tienes una cuenta? <a href="registro.php">Registrarse</a></p>
            </div>
        </form>
    </div>

    <?php include_once 'footer.html'; ?>
</body>
</html>
