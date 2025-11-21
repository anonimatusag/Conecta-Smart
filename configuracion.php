<?php
// =======================================================
// 1. CONFIGURACIÓN DE LA BASE DE DATOS (Basado en tu ejemplo)
// =======================================================
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "usuarios"; 
$tabla = "inscripciones"; // La tabla que creamos en el Paso 1

// =======================================================
// 2. VERIFICACIÓN Y OBTENCIÓN DE DATOS (usando POST)
// =======================================================
// Verificamos que los datos se envíen por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtiene y sanea los datos de tu formulario HTML
    // Los nombres ('Nombre', 'genero', etc.) deben coincidir con el atributo 'name' de tu HTML
    $nombre = htmlspecialchars($_POST['Nombre']);
    $genero = htmlspecialchars($_POST['genero']);
    $edad = (int)$_POST['Edad']; // Convertimos la edad a número entero
    $localidad = htmlspecialchars($_POST['ciudad']);
    $sede = htmlspecialchars($_POST['Selecciona']);

    // =======================================================
    // 3. CONEXIÓN A LA BASE DE DATOS
    // =======================================================
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(" La conexión falló: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");

    // =======================================================
    // 4. INSERCIÓN SEGURA DE DATOS (SENTENCIAS PREPARADAS)
    // =======================================================
    // El SQL debe coincidir con las columnas de tu tabla
    $sql = "INSERT INTO $tabla (nombre_completo, genero, edad, localidad, sede) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die(" Error al preparar la consulta. Revisa si la tabla '$tabla' existe. Error: " . $conn->error);
    }

    // Vincula los parámetros: 5 variables
    // "ssiss" significa: String, String, Integer, String, String
    $stmt->bind_param("ssiss", $nombre, $genero, $edad, $localidad, $sede);
    
    // =======================================================
    // 5. EJECUTAR Y MOSTRAR RESULTADO
    // =======================================================
    if ($stmt->execute()) {
        echo '<html><head><title>Éxito</title><link rel="stylesheet" href="style.css"></head><body>';
        echo '<h2> Inscripción Exitosa</h2>';
        echo "<p>Datos de <strong>" . htmlspecialchars($nombre) . "</strong> guardados correctamente.</p>";
        echo '<a href="formulario.html">← Volver al formulario</a>';
        echo '</body></html>';
    } else {
        echo '<html><head><title>Error</title><link rel="stylesheet" href="style.css"></head><body>';
        echo '<h2> Error al guardar los datos</h2>';
        echo "<p>Error: " . $stmt->error . "</p>";
        echo '<a href="formulario.html">← Volver a intentarlo</a>';
        echo '</body></html>';
    }
    
    // Cierra conexiones
    $stmt->close();
    $conn->close();

} else {
    // Si alguien intenta acceder a registrar.php directamente
    echo "Acceso no permitido. Debes enviar el formulario.";
    header("Location: formulario.html");
    exit;
}
?>