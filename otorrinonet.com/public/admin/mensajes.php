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

$messages = [];
$error_db = '';

try {
    $db = \App\Core\Database::getInstance()->getConnection();
    
    // Consulta para obtener los mensajes, los más nuevos primero
    $stmt = $db->query("
        SELECT id, nombre, email, telefono, asunto, mensaje, status, fecha_envio
        FROM contact_messages
        ORDER BY fecha_envio DESC
    ");
    
    $messages = $stmt->fetchAll();

} catch (PDOException $e) {
    $error_db = "Error al conectar con la base de datos: " . $e->getMessage();
}

?>

<h2 class="text-2xl font-semibold text-gray-800 mb-6">Bandeja de Entrada de Contacto</h2>

<?php if ($error_db): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
        <p class="font-bold">Error de Base de Datos</p>
        <p><?php echo htmlspecialchars($error_db); ?></p>
    </div>
<?php elseif (empty($messages)): ?>
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
        <p>No hay mensajes en la bandeja de entrada.</p>
    </div>
<?php else: ?>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <?php foreach ($messages as $message): ?>
            <div class="border-b border-gray-200">
                <div class="p-4 cursor-pointer flex justify-between items-center hover:bg-gray-50" onclick="toggleMessage(<?php echo $message['id']; ?>)">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                    switch ($message['status']) {
                                        case 'nuevo': echo 'bg-blue-100 text-blue-800'; break;
                                        case 'leido': echo 'bg-gray-100 text-gray-800'; break;
                                        case 'respondido': echo 'bg-green-100 text-green-800'; break;
                                        case 'archivado': echo 'bg-yellow-100 text-yellow-800'; break;
                                    }
                                ?>">
                                <?php echo htmlspecialchars(ucfirst($message['status'])); ?>
                            </span>
                            <p class="text-sm font-medium text-gray-900 ml-3"><?php echo htmlspecialchars($message['nombre']); ?></p>
                            <p class="text-sm text-gray-600 ml-3 truncate hidden md:block"><?php echo htmlspecialchars($message['asunto']); ?></p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 text-right">
                        <?php echo date("d/m/Y H:i", strtotime($message['fecha_envio'])); ?>
                    </div>
                </div>
                <div id="message-body-<?php echo $message['id']; ?>" class="hidden p-6 bg-gray-50 border-t border-gray-200">
                    <p class="text-sm text-gray-800 mb-4"><strong>De:</strong> <?php echo htmlspecialchars($message['nombre']); ?> (<?php echo htmlspecialchars($message['email']); ?>)</p>
                    <p class="text-sm text-gray-800 mb-4"><strong>Teléfono:</strong> <?php echo htmlspecialchars($message['telefono']); ?></p>
                    <p class="text-sm text-gray-800 mb-4"><strong>Asunto:</strong> <?php echo htmlspecialchars($message['asunto']); ?></p>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <?php echo nl2br(htmlspecialchars($message['mensaje'])); ?>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-300 flex space-x-2">
                        <button class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Responder</button>
                        <button class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Marcar como leído</button>
                        <button class="px-3 py-1 text-sm font-medium text-gray-700 bg-yellow-200 rounded-md hover:bg-yellow-300">Archivar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function toggleMessage(id) {
    const body = document.getElementById('message-body-' + id);
    body.classList.toggle('hidden');

    // Opcional: Marcar como leído al abrir
    // Aquí podrías hacer una llamada AJAX para actualizar el estado en la BD
    // fetch('mensajes_action.php', { method: 'POST', body: JSON.stringify({ action: 'mark_as_read', id: id }) });
}
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>