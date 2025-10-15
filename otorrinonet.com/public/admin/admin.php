<?php
require_once __DIR__ . '/_header.php'; // Incluye el control de sesión y la cabecera
require_once __DIR__ . '/../../vendor/autoload.php';

// Cargar variables de entorno
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    die('No se pudo encontrar el archivo .env');
}

$appointments = [];
$error_db = '';

try {
    $db = \App\Core\Database::getInstance()->getConnection();
    
    // Consulta para obtener las citas, ordenadas por las más próximas primero
    $stmt = $db->query("
        SELECT id, nombre, telefono, fecha_cita, hora_cita, tipo_consulta, status
        FROM appointments
        WHERE fecha_cita >= CURRENT_DATE
        ORDER BY fecha_cita ASC, hora_cita ASC
    ");
    
    $appointments = $stmt->fetchAll();

} catch (PDOException $e) {
    $error_db = "Error al conectar con la base de datos: " . $e->getMessage();
}

?>

<h2 class="text-2xl font-semibold text-gray-800 mb-6">Próximas Citas</h2>

<?php if ($error_db): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
        <p class="font-bold">Error de Base de Datos</p>
        <p><?php echo htmlspecialchars($error_db); ?></p>
    </div>
<?php elseif (empty($appointments)): ?>
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
        <p>No hay citas programadas para los próximos días.</p>
    </div>
<?php else: ?>
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($appointments as $cita): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($cita['nombre']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($cita['telefono']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo date("d/m/Y", strtotime($cita['fecha_cita'])); ?></div>
                            <div class="text-sm text-gray-500"><?php echo date("g:i A", strtotime($cita['hora_cita'])); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $cita['tipo_consulta']))); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                    switch ($cita['status']) {
                                        case 'pendiente': echo 'bg-yellow-100 text-yellow-800'; break;
                                        case 'confirmada': echo 'bg-green-100 text-green-800'; break;
                                        case 'cancelada': echo 'bg-red-100 text-red-800'; break;
                                        default: echo 'bg-gray-100 text-gray-800';
                                    }
                                ?>">
                                <?php echo htmlspecialchars(ucfirst($cita['status'])); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                            </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/_footer.php'; ?>
