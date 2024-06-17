<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Electrodomésticos</title>
    <link rel="stylesheet" href="public/css/tailwind.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <!-- Formulario para registrar un electrodoméstico -->
        <form id="electrodomesticoForm" action="index.php" method="POST">
            <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Registrar Electrodoméstico</h2>
            <div class="mb-5">
                <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                <span id="nombreError" class="error hidden">El nombre debe contener solo letras.</span>
            </div>
            <div class="mb-5">
                <label for="color" class="block text-gray-700 font-semibold mb-2">Color:</label>
                <input type="text" id="color" name="color" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-5">
                <label for="consumo" class="block text-gray-700 font-semibold mb-2">Consumo Energético (A, B, C):</label>
                <input type="text" id="consumo" name="consumo" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-5">
                <label for="peso" class="block text-gray-700 font-semibold mb-2">Peso (kg):</label>
                <input type="text" id="peso" name="peso" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                <span id="pesoError" class="error hidden">El peso debe contener solo números.</span>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-transform hover:scale-105">Registrar</button>
            </div>
        </form>
    </div>
    <script src="public/js/main.js"></script>
</body>
</html>

<?php
// Función para limpiar y validar los datos ingresados
function limpiarYValidarDatos($data) {
    // Convertir el consumo a mayúsculas y validar
    $data['consumo'] = strtoupper($data['consumo']);
    if (!in_array($data['consumo'], ['A', 'B', 'C'])) {
        $data['consumo'] = 'C'; // Valor por defecto si no es válido
    }

    // Convertir el peso a float y validar
    $data['peso'] = floatval($data['peso']);
    if ($data['peso'] < 0 || $data['peso'] > 49) {
        $data['peso'] = 1; // Valor por defecto si no es válido
    }

    // Convertir el color a minúsculas y validar
    $colores_permitidos = ['blanco', 'gris', 'negro'];
    $data['color'] = strtolower($data['color']);
    if (!in_array($data['color'], $colores_permitidos)) {
        $data['color'] = 'blanco'; // Valor por defecto si no es válido
    }

    return $data; // Devolver los datos limpiados y validados
}

// Función para calcular el precio base
function calcularPrecioBase($consumo, $peso) {
    $precio_base = 0;
    // Asignar precio base según el consumo energético
    switch ($consumo) {
        case 'A':
            $precio_base = 100;
            break;
        case 'B':
            $precio_base = 80;
            break;
        case 'C':
            $precio_base = 60;
            break;
        default:
            $precio_base = 0;
    }
    // Ajustar el precio base según el peso
    if ($peso >= 0 && $peso <= 19) {
        $precio_base *= 10;
    } elseif ($peso >= 20 && $peso <= 49) {
        $precio_base *= 50;
    }
    return $precio_base; // Devolver el precio base calculado
}

// Función para calcular el descuento según el color
function calcularDescuento($color) {
    $descuentos = [
        'blanco' => 0.05,
        'gris' => 0.07,
        'negro' => 0.10
    ];
    return $descuentos[$color] ?? 0; // Devolver el descuento correspondiente al color
}

// Función para calcular el precio final aplicando el descuento
function calcularPrecioFinal($precio_base, $descuento) {
    return $precio_base * (1 - $descuento); // Devolver el precio final
}

// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $electrodomestico = [
        'nombre' => $_POST['nombre'],
        'color' => $_POST['color'],
        'consumo' => $_POST['consumo'],
        'peso' => $_POST['peso']
    ];

    // Limpiar y validar los datos
    $electrodomestico = limpiarYValidarDatos($electrodomestico);
    // Calcular el precio base
    $precio_base = calcularPrecioBase($electrodomestico['consumo'], $electrodomestico['peso']);
    // Calcular el descuento
    $descuento = calcularDescuento($electrodomestico['color']);
    // Calcular el precio con descuento y el precio final
    $precio_con_descuento = $precio_base * (1 - $descuento);
    $precio_final = calcularPrecioFinal($precio_base, $descuento);

    // Construir el array asociativo con la información del producto
    $producto = array(
        'Nombre' => $electrodomestico['nombre'],
        'Color' => ucfirst($electrodomestico['color']),
        'Consumo' => $electrodomestico['consumo'],
        'Peso' => $electrodomestico['peso'] . ' kg',
        'Precio Base' => '$' . number_format($precio_base, 2),
        'Descuento' => ($descuento * 100) . '%',
        'Precio con Descuento' => '$' . number_format($precio_con_descuento, 2),
        'Precio Final' => '$' . number_format($precio_final, 2)
    );
    
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Electrodoméstico</title>
    <link rel="stylesheet" href="public/css/tailwind.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <!-- Mostrar los detalles del electrodoméstico registrado -->
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Detalles del Electrodoméstico</h2>
        <table class="w-full">
            <?php foreach ($producto as $key => $value): ?>
            <tr>
                <td class="py-2 px-4 font-semibold"><?php echo htmlspecialchars($key); ?>:</td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($value); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

<?php
}
?>
<?php
// Función para almacenar los datos ingresados en el formulario en un array
function almacenarDatosFormulario($nombre, $color, $consumo, $peso) {
    // Construir el array con los datos del electrodoméstico
    $datos_electrodomestico = array(
        'Nombre' => $nombre,
        'Color' => ucfirst($color),
        'Consumo' => $consumo,
        'Peso' => $peso . ' kg'
    );

    return $datos_electrodomestico; // Devolver el array con los datos del electrodoméstico
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $color = $_POST['color'];
    $consumo = $_POST['consumo'];
    $peso = $_POST['peso'];

    // Almacenar los datos en un array
    $datos_electrodomestico = almacenarDatosFormulario($nombre, $color, $consumo, $peso);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electrodomésticos</title>
    <link rel="stylesheet" href="public/css/tailwind.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <!-- Mostrar los detalles del electrodoméstico registrado -->
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Funcion Array</h2>
        <table class="w-full">
            <?php foreach ($datos_electrodomestico as $key => $value): ?>
            <tr>
                <td class="py-2 px-4 font-semibold"><?php echo htmlspecialchars($key); ?>:</td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($value); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

<?php
}
?>

