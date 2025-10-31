<?php
// Asegúrate de que el script solo responda a peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// 1. Recibir los datos del formulario (CampoA, CampoB, CampoC)
$campoA = isset($_POST['campoA']) ? $_POST['campoA'] : null;
$campoB = isset($_POST['campoB']) ? $_POST['campoB'] : null;
$campoC = isset($_POST['campoC']) ? $_POST['campoC'] : null;

// Validación básica (se recomienda validar más a fondo)
if (is_null($campoA) || is_null($campoB) || is_null($campoC)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Faltan datos en la petición.']);
    exit;
}

// Crear el nuevo registro de datos
$newEntry = [
    'id' => uniqid(), // Generar un ID único para el registro
    'campoA' => (int)$campoA,
    'campoB' => (int)$campoB,
    'campoC' => (int)$campoC,
    'timestamp' => date('Y-m-d H:i:s')
];

$filePath = 'data.json';

// 2. Leer el contenido actual del archivo data.json
if (file_exists($filePath)) {
    $currentData = file_get_contents($filePath);
    $dataArray = json_decode($currentData, true);
    // Si la decodificación falla o el archivo está vacío, inicializar como un array vacío
    if ($dataArray === null || !is_array($dataArray)) {
        $dataArray = [];
    }
} else {
    // Si el archivo no existe, inicializar con un array vacío
    $dataArray = [];
}

// 3. Agregar el nuevo registro al array
$dataArray[] = $newEntry;

// 4. Codificar el array completo a JSON (con formato legible)
$jsonToSave = json_encode($dataArray, JSON_PRETTY_PRINT);

// 5. Guardar el contenido JSON de vuelta al archivo
$result = file_put_contents($filePath, $jsonToSave);

// 6. Enviar una respuesta al frontend
header('Content-Type: application/json');
if ($result !== false) {
    echo json_encode(['success' => true, 'message' => 'Datos guardados exitosamente en ' . $filePath]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al escribir en el archivo ' . $filePath]);
}
?>
