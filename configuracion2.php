<?php
// =======================================================
// 1. CONFIGURACIÓN DE LA BASE DE DATOS
// =======================================================
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "usuarios"; 
$tabla = "inscripciones"; // TABLA REQUERIDA PARA LA BÚSQUEDA

// =======================================================
// 2. VERIFICACIÓN Y OBTENCIÓN DE DATOS (usando POST)
// =======================================================
// Verifica si el nombre_buscar fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_buscar']) && !empty($_POST['nombre_buscar'])) {
    
    $nombre_buscado = htmlspecialchars($_POST['nombre_buscar']);

    // =======================================================
    // 3. CONEXIÓN Y CONSULTA
    // =======================================================
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die(" Error de Conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");

    // Prepara la consulta SELECT (Búsqueda por nombre_completo)
    $sql = "SELECT nombre_completo, genero, edad, localidad, sede FROM $tabla WHERE nombre_completo = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die(" ERROR SQL: No se pudo preparar la consulta. Error: " . $conn->error);
    }

    // Vincula el Nombre como String (s)
    $stmt->bind_param("s", $nombre_buscado);
    
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    
    $stmt->close();
    $conn->close();
    
    // =======================================================
    // 4. MOSTRAR RESULTADOS HTML (Importamos tu CSS)
    // =======================================================
    
    echo '<html><head><title>Resultado de Búsqueda</title><link rel="stylesheet" href="style.css"></head><body>';
    echo '<main>'; // Usamos <main> como en tu HTML
    echo '<h2>Resultado de Búsqueda para: ' . htmlspecialchars($nombre_buscado) . '</h2>';

    if ($resultado->num_rows > 0) {
        // Inscripto encontrado
        $inscrito = $resultado->fetch_assoc();
        
        echo '<div class="datos">'; // Puedes darle estilo a esta clase en 'style.css'
        echo '<h3> Datos del Inscripto Encontrado</h3>';
        echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($inscrito['nombre_completo']) . '</p>';
        echo '<p><strong>Género:</strong> ' . htmlspecialchars($inscrito['genero']) . '</p>';
        echo '<p><strong>Edad:</strong> ' . htmlspecialchars($inscrito['edad']) . '</p>';
        echo '<p><strong>Localidad:</strong> ' . htmlspecialchars($inscrito['localidad']) . '</p>';
        echo '<p><strong>Sede:</strong> ' . htmlspecialchars($inscrito['sede']) . '</p>';
        echo '</div>';
        
    } else {
        // Inscrito NO encontrado
        echo '<div class"error">'; // Puedes darle estilo a esta clase en 'style.css'
        echo '<h3>Inscrito NO encontrado en la base de datos.</h3>';
        echo '</div>';
    }

    echo '<br><p><a href="formulario.html">← Realizar otra consulta o inscripción</a></p>';
    echo '</main></body></html>';

} else {
    // Si se accede al script directamente o el campo está vacío
    header("Location: formulario.html"); // Redirige al formulario si no hay datos
    exit;
}
?>

