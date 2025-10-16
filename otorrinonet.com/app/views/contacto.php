<?php
namespace App\Core;
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Incluir el header
include '_header.php';

$messageSent = false;
$errors = [];

// Procesar formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar hCaptcha
    $hcaptchaSecret = $_ENV['HCAPTCHA_SECRET_KEY'] ?? '';
    $hcaptchaResponse = $_POST['h-captcha-response'] ?? '';
    
    if (!empty($hcaptchaResponse)) {
        $verifyUrl = 'https://hcaptcha.com/siteverify';
        $data = [
            'secret' => $hcaptchaSecret,
            'response' => $hcaptchaResponse
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $verify = file_get_contents($verifyUrl, false, $context);
        $captchaSuccess = json_decode($verify);
        
        if (!$captchaSuccess->success) {
            $errors[] = "Por favor, complete la verificación de seguridad.";
        }
    } else {
        $errors[] = "Por favor, complete la verificación de seguridad.";
    }
    
    // Validar campos
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
    $asunto = filter_input(INPUT_POST, 'asunto', FILTER_SANITIZE_STRING);
    $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);
    
    if (empty($nombre)) {
        $errors[] = "El nombre es requerido.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email es inválido.";
    }
    
    if (empty($telefono)) {
        $errors[] = "El teléfono es requerido.";
    }
    
    if (empty($asunto)) {
        $errors[] = "El asunto es requerido.";
    }
    
    if (empty($mensaje)) {
        $errors[] = "El mensaje es requerido.";
    }
    
    // Si no hay errores, guardar en base de datos
    if (empty($errors)) {
        try {
            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("
                INSERT INTO contact_messages (nombre, email, telefono, asunto, mensaje, fecha_envio)
                VALUES (:nombre, :email, :telefono, :asunto, :mensaje, NOW())
            ");
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':telefono' => $telefono,
                ':asunto' => $asunto,
                ':mensaje' => $mensaje
            ]);
            
            $messageSent = true;
        } catch (PDOException $e) {
            $errors[] = "Error al enviar el mensaje. Por favor, intente más tarde.";
            // En producción, registrar el error en un log
            error_log("Error al guardar mensaje de contacto: " . $e->getMessage());
        }
    }
}
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Contacto</h1>
        <p class="text-xl">Estamos aquí para atenderte. Envíanos un mensaje</p>
    </div>
</section>

<!-- Mensajes de éxito/error -->
<?php if ($messageSent): ?>
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
            <p class="font-bold">¡Mensaje enviado con éxito!</p>
            <p>Nos pondremos en contacto contigo pronto.</p>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            <p class="font-bold">Errores en el formulario:</p>
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contacto Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Información de Contacto -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Información de Contacto</h2>
                
                <!-- Dirección -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 mb-2">Dirección</h3>
                            <p class="text-gray-600">Buenavista 20, Col. Lindavista</p>
                            <p class="text-gray-600">Gustavo A. Madero, CDMX 07750</p>
                        </div>
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 mb-2">Teléfono</h3>
                            <p class="text-gray-600">+52 55 1234-5678</p>
                            <a href="https://wa.me/<?php echo $_ENV['WHATSAPP_PHONE_NUMBER'] ?? '525512345678'; ?>" 
                               class="text-green-600 hover:text-green-700 font-medium">
                                WhatsApp: Da clic aquí
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 mb-2">Email</h3>
                            <p class="text-gray-600">contacto@otorrinonet.com</p>
                        </div>
                    </div>
                </div>

                <!-- Horarios -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-orange-100 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-bold text-lg text-gray-800 mb-3">Horarios de Atención</h3>
                            <div class="space-y-2 text-gray-600">
                                <div class="flex justify-between">
                                    <span class="font-medium">Lunes a Miércoles:</span>
                                    <span>16:00 - 20:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Jueves y Viernes:</span>
                                    <span>10:00 - 13:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Sábado y Domingo:</span>
                                    <span class="text-red-600">Cerrado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa -->
                <div class="mt-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Ubicación</h3>
                    <div id="map" class="rounded-xl w-full h-96 border-2 border-gray-200 shadow-inner"></div>
                </div>
            </div>

            <!-- Formulario de Contacto -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Envíanos un Mensaje</h2>
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <form method="POST" action="/contacto" class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-gray-700 font-medium mb-2">
                                Nombre Completo <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   required
                                   value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: Juan Pérez">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">
                                Correo Electrónico <span class="text-red-600">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="correo@ejemplo.com">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-gray-700 font-medium mb-2">
                                Teléfono <span class="text-red-600">*</span>
                            </label>
                            <input type="tel" 
                                   id="telefono" 
                                   name="telefono" 
                                   required
                                   value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="55 1234-5678">
                        </div>

                        <!-- Asunto -->
                        <div>
                            <label for="asunto" class="block text-gray-700 font-medium mb-2">
                                Asunto <span class="text-red-600">*</span>
                            </label>
                            <select id="asunto" 
                                    name="asunto" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione un asunto</option>
                                <option value="consulta" <?php echo (isset($_POST['asunto']) && $_POST['asunto'] === 'consulta') ? 'selected' : ''; ?>>Consulta General</option>
                                <option value="cita" <?php echo (isset($_POST['asunto']) && $_POST['asunto'] === 'cita') ? 'selected' : ''; ?>>Información sobre Citas</option>
                                <option value="cirugia" <?php echo (isset($_POST['asunto']) && $_POST['asunto'] === 'cirugia') ? 'selected' : ''; ?>>Información sobre Cirugías</option>
                                <option value="cotizacion" <?php echo (isset($_POST['asunto']) && $_POST['asunto'] === 'cotizacion') ? 'selected' : ''; ?>>Solicitud de Cotización</option>
                                <option value="seguimiento" <?php echo (isset($_POST['asunto']) && $_POST['asunto'] === 'seguimiento') ? 'selected' : ''; ?>>Seguimiento de Tratamiento</option>
                                <option value="otro" <?php echo (isset($_POST['asunto']) && $_POST['asunto'] === 'otro') ? 'selected' : ''; ?>>Otro</option>
                            </select>
                        </div>

                        <!-- Mensaje -->
                        <div>
                            <label for="mensaje" class="block text-gray-700 font-medium mb-2">
                                Mensaje <span class="text-red-600">*</span>
                            </label>
                            <textarea id="mensaje" 
                                      name="mensaje" 
                                      required
                                      rows="5"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Escribe tu mensaje aquí..."><?php echo htmlspecialchars($_POST['mensaje'] ?? ''); ?></textarea>
                        </div>

                        <!-- hCaptcha -->
                        <div class="h-captcha" data-sitekey="<?php echo $_ENV['HCAPTCHA_SITE_KEY'] ?? ''; ?>"></div>

                        <!-- Botón Enviar -->
                        <div>
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition duration-300 shadow-lg">
                                Enviar Mensaje
                            </button>
                        </div>

                        <p class="text-sm text-gray-600 text-center">
                            <span class="text-red-600">*</span> Campos obligatorios
                        </p>
                    </form>
                </div>

                <!-- Información Adicional -->
                <div class="mt-8 bg-blue-50 border-l-4 border-blue-600 p-6 rounded">
                    <h3 class="font-bold text-blue-800 mb-2">¿Prefieres agendar una cita directamente?</h3>
                    <p class="text-gray-700 mb-4">Utiliza nuestro sistema de citas en línea para seleccionar el horario que mejor te convenga.</p> 
                    <a href="/agendar-cita" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-300">
                        Agendar Cita Ahora
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Incluir el footer
include '_footer.php';
?>

<!-- Google Maps API -->
<script>
function initMap() {
    // Coordenadas del consultorio
    const location = { lat: 19.4925, lng: -99.1355 }; // Coordenadas para Buenavista 20, Lindavista

    // Crear el mapa
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: location,
        mapTypeControl: false,
        streetViewControl: false,
    });

    // Crear el marcador
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        title: "Dr. Alejandro Viveros Domínguez",
        animation: google.maps.Animation.DROP,
    });

    // Opcional: Añadir un InfoWindow al hacer clic en el marcador
    const infowindow = new google.maps.InfoWindow({
        content: '<strong>Dr. Alejandro Viveros Domínguez</strong><br>Buenavista 20, Col. Lindavista<br>CDMX',
    });

    marker.addListener("click", () => {
        infowindow.open(map, marker);
    });
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $_ENV['GOOGLE_MAPS_API_KEY'] ?? ''; ?>&callback=initMap"></script>
