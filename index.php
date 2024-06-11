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
// Verificar si se ha enviado un formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Almacenar la información del formulario en un array asociativo
    $electrodomestico = [
        'nombre' => $_POST['nombre'],
        'color' => $_POST['color'],
        'consumo' => $_POST['consumo'],
        'peso' => $_POST['peso']
    ];

    // Consideraciones
    // Consumo energético
    $consumo = strtoupper($electrodomestico['consumo']);
    if (!in_array($consumo, ['A', 'B', 'C'])) {
        $consumo = 'C'; // Por defecto, asignar letra C
    }

    // Peso
    $peso = floatval($electrodomestico['peso']);
    if ($peso < 0 || $peso > 49) {
        $peso = 1; // Por defecto, asignar 1 kg
    }

    // Color
    $colores_permitidos = ['blanco', 'gris', 'negro'];
    $color = strtolower($electrodomestico['color']);
    if (!in_array($color, $colores_permitidos)) {
        $color = 'blanco'; // Por defecto, asignar blanco
    }

    // Actualizar valores en el array
    $electrodomestico['consumo'] = $consumo;
    $electrodomestico['peso'] = $peso;
    $electrodomestico['color'] = $color;

    // Calcular el descuento basado en el color
    $descuentos = [
        'blanco' => 0.05,
        'gris' => 0.07,
        'negro' => 0.10
    ];

    // Verificar si el color tiene un descuento asociado
    $descuento = isset($descuentos[$color]) ? $descuentos[$color] : 0;

    // Calcular el precio base multiplicando el consumo energético por el valor de peso
    $precio_base = 0;
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
    if ($peso >= 0 && $peso <= 19) {
        $precio_base *= 10;
    } elseif ($peso >= 20 && $peso <= 49) {
        $precio_base *= 50;
    }

    // Calcular el precio con descuento aplicado
    $precio_con_descuento = $precio_base * (1 - $descuento);

    // Calcular el precio final sumando el precio base, el descuento y los precios adicionales
    $precio_final = $precio_con_descuento;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Electrodomésticos</title>
    <link rel="stylesheet" href="public/css/tailwind.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md ml-30">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Detalles del Electrodoméstico</h2>
        <table class="w-full">
            <tr>
                <td class="py-2 px-4 font-semibold">Nombre:</td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($electrodomestico['nombre']); ?></td>
            </tr>
            <tr>
                <td class="py-2 px-4 font-semibold">Color:</td>
                <td class="py-2 px-4"><?php echo htmlspecialchars(ucfirst($electrodomestico['color'])); ?></td>
            </tr>
            <tr>
                <td class="py-2 px-4 font-semibold">Consumo Energético:</td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($electrodomestico['consumo']); ?></td>
            </tr>
            <tr>
                <td class="py-2 px-4 font-semibold">Peso:</td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($electrodomestico['peso']); ?> kg</td>
            </tr>
            <tr>
                <td class="py-2 px-4 font-semibold">Precio Base:</td>
                <td class="py-2 px-4">$<?php echo number_format($precio_base, 2); ?></td>
            </tr>
            <tr>
                <td class="py-2 px-4 font-semibold">Descuento aplicado:</td>
                <td class="py-2 px-4"><?php echo ($descuento * 100); ?>%</td>
            </tr>
            <tr>
                <td class="py-2 px-4 font-semibold">Precio con Descuento:</td>
                <td class="py-2 px-4">$<?php echo number_format($precio_con_descuento, 2); ?></td>
            </tr>
            <tr>
                <td class="py-2 px-4 font-semibold">Precio Final:</td>
                <td class="py-2 px-4">$<?php echo number_format($precio_final, 2); ?></td>
            </tr>
        </table>
        <h2 class="text-3xl font-bold text-center my-6 text-gray-800">Información del Producto (Array Asociativo)</h2>
        <pre class="p-4 bg-gray-200 rounded-lg"><?php print_r($electrodomestico); ?></pre>
    </div>
</body>
</html>

<?php
}
?>
