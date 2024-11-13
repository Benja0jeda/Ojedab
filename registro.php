<?php
include_once 'bd.php';
session_start();

$nombre = $correo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $confirmar = $_POST['confirmar'];
    $clave = $_POST['clave'];
    
        if (empty($nombre)) {
            echo "<script>alert('El nombre es requerido');</script>";
        } 
        elseif (empty($correo)) {
            echo "<script>alert('El correo electrónico es requerido');</script>";
        }
        elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Formato de correo electrónico inválido');</script>";
        }
        elseif (empty($contraseña)) {
            echo "<script>alert('La contraseña es requerida');</script>";
        }
        elseif (strlen($contraseña) < 6) {
            echo "<script>alert('La contraseña debe tener al menos 6 caracteres');</script>";
        }
        elseif ($contraseña !== $confirmar) {
            echo "<script>alert('Las contraseñas no coinciden');</script>";
        }
        else {
            $consulta = mysqli_query($bd, "SELECT * FROM usuario WHERE correo = '$correo'");
            if (mysqli_num_rows($consulta) > 0) {
                echo "<script>alert('Este correo electrónico ya está registrado');</script>";
            } else {
                $consulta = mysqli_query($bd, "SELECT clave FROM configuracion");
                $fila = mysqli_fetch_assoc($consulta);
                $clave_maestra = $fila['clave'];
                if ($clave !== $clave_maestra) {
                    echo "<script>alert('La clave maestra es incorrecta');</script>";
            } else {
                $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
                
                $consulta = mysqli_query($bd, "INSERT INTO usuario (nombre, correo, contraseña) VALUES ('$nombre', '$correo', '$contraseña_hash')");
                if ($consulta) {
                    echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.');</script>";
                    echo "<script>window.location.href = 'index.php';</script>";
                } else {
                    echo "<script>alert('Error al registrar el usuario. Por favor, inténtalo de nuevo.');</script>";
                }
            }            
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="form">
    <div class="envoltorio">
        <form method="POST">
            <h2>Registro</h2>
            <div class="entrada">
                <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" required>
            </div>
            <div class="entrada">
                <input type="email" name="correo" placeholder="Correo electrónico" value="<?php echo $correo; ?>" required>
            </div>
            <div class="entrada">
                <input type="password" name="contraseña" placeholder="Contraseña" required>
            </div>
            <div class="entrada">
                <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
            </div>
            <div class="entrada">
                <input type="password" name="clave" placeholder="Clave maestra" required>
            </div>
            <button type="submit" class="boton" name="boton">Registrarse</button>
            <div class="enlace">
                <p>¿Ya tienes una cuenta? <a href="index.php">Acceder</a></p>
            </div>
        </form>
    </div>

    <?php include_once 'footer.html'; ?>
</body>

</html>