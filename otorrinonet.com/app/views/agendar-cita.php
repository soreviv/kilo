<?php
namespace App\Core;
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Incluir el header
include '_header.php';

$appointmentBooked = false;
$errors = [];
$email = ''; // Inicializar email para evitar errores si no hay POST
// Procesar formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book_appointment') {
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
    $fecha_cita = filter_input(INPUT_POST, 'fecha_cita', FILTER_SANITIZE_STRING);
    $hora_cita = filter_input(INPUT_POST, 'hora_cita', FILTER_SANITIZE_STRING);
    $tipo_consulta = filter_input(INPUT_POST, 'tipo_consulta', FILTER_SANITIZE_STRING);
    $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING);
    
    if (empty($nombre)) {
        $errors[] = "El nombre es requerido.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email es inválido.";
    }
    
    if (empty($telefono)) {
        $errors[] = "El teléfono es requerido.";
    }
    
    if (empty($fecha_cita)) {
        $errors[] = "La fecha de cita es requerida.";
    }
    
    if (empty($hora_cita)) {
        $errors[] = "La hora de cita es requerida.";
    }
    
    if (empty($tipo_consulta)) {
        $errors[] = "El tipo de consulta es requerido.";
    }
    
    if (empty($motivo)) {
        $errors[] = "El motivo de consulta es requerido.";
    }
    
    // Si no hay errores, guardar en base de datos
    if (empty($errors)) {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Verificar disponibilidad
            $stmt = $db->prepare("
                SELECT COUNT(*) FROM appointments 
                WHERE fecha_cita = :fecha_cita 
                AND hora_cita = :hora_cita 
                AND status != 'cancelada'
            ");
            
            $stmt->execute([
                ':fecha_cita' => $fecha_cita,
                ':hora_cita' => $hora_cita
            ]);
            
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Este horario ya no está disponible. Por favor, seleccione otro.";
            } else {
                // Insertar cita
                $stmt = $db->prepare("
                    INSERT INTO appointments (nombre, email, telefono, fecha_cita, hora_cita, tipo_consulta, motivo, status, fecha_creacion)
                    VALUES (:nombre, :email, :telefono, :fecha_cita, :hora_cita, :tipo_consulta, :motivo, 'pendiente', NOW())
                ");
                
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':fecha_cita' => $fecha_cita,
                    ':hora_cita' => $hora_cita,
                    ':tipo_consulta' => $tipo_consulta,
                    ':motivo' => $motivo
                ]);
                
                $appointmentBooked = true;
            }
        } catch (PDOException $e) {
            $errors[] = "Error al agendar la cita. Por favor, intente más tarde.";
            error_log("Error al agendar cita: " . $e->getMessage());
        }
    }
}
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Agendar Cita</h1>
        <p class="text-xl">Selecciona la fecha y hora que mejor te convenga</p>
    </div>
</section>

<!-- Mensajes de éxito/error -->
<?php if ($appointmentBooked): ?>
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 rounded-lg shadow-lg" role="alert">
            <div class="flex items-center mb-2">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="font-bold text-lg">¡Cita agendada con éxito!</p>
            </div>
            <p class="ml-8">Te hemos enviado un correo de confirmación a <strong><?php echo htmlspecialchars($email); ?></strong></p>
            <p class="ml-8 mt-2">Nos pondremos en contacto contigo pronto para confirmar tu cita.</p>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            <p class="font-bold">Errores en el formulario:</p>
            <ul class="list-disc list-inside mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Agendamiento de Citas -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Selecciona Fecha y Hora</h2>
                    
                    <!-- Calendario FullCalendar -->
                    <div id="calendar" class="mb-6"></div>
                    
                    <!-- Horarios Disponibles -->
                    <div id="available-times" class="hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Horarios Disponibles</h3>
                        <p class="text-gray-600 mb-4">Fecha seleccionada: <span id="selected-date" class="font-bold"></span></p>
                        <div id="time-slots" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <!-- Los horarios se generarán dinámicamente con JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Información Importante -->
                <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded">
                    <h3 class="font-bold text-yellow-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Información Importante
                    </h3>
                    <ul class="text-sm text-yellow-800 space-y-1">
                        <li>• Llegar 10 minutos antes de la hora programada</li>
                        <li>• Traer identificación oficial</li>
                        <li>• Traer estudios médicos previos (si los tiene)</li>
                        <li>• Cancelaciones con menos de 24 horas pueden generar cargos</li>
                        <li>• La confirmación se enviará por email y WhatsApp</li>
                    </ul>
                </div>
            </div>

            <!-- Formulario de Cita -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Datos del Paciente</h2>

                    <form method="POST" action="/agendar-cita" id="appointment-form" class="space-y-4">
                        <input type="hidden" name="action" value="book_appointment">
                        <input type="hidden" name="fecha_cita" id="fecha_cita">
                        <input type="hidden" name="hora_cita" id="hora_cita">
                        
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-gray-700 font-medium mb-2">
                                Nombre Completo <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   required
                                   value="<?php echo htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                   value="<?php echo htmlspecialchars($_POST['telefono'] ?? '', ENT_QUOTES); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="55 1234-5678">
                        </div>

                        <!-- Tipo de Consulta -->
                        <div>
                            <label for="tipo_consulta" class="block text-gray-700 font-medium mb-2">
                                Tipo de Consulta <span class="text-red-600">*</span>
                            </label>
                            <select id="tipo_consulta" 
                                    name="tipo_consulta" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                <option value="primera_vez">Primera Vez</option>
                                <option value="seguimiento">Seguimiento</option>
                                <option value="urgencia">Urgencia</option>
                                <option value="valoracion_cirugia">Valoración para Cirugía</option>
                            </select>
                        </div>

                        <!-- Motivo -->
                        <div>
                            <label for="motivo" class="block text-gray-700 font-medium mb-2">
                                Motivo de Consulta <span class="text-red-600">*</span>
                            </label>
                            <textarea id="motivo" 
                                      name="motivo" 
                                      required
                                      rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe brevemente tu motivo de consulta..."><?php echo htmlspecialchars($_POST['motivo'] ?? '', ENT_QUOTES); ?></textarea>
                        </div>

                        <!-- Resumen de Cita -->
                        <div id="appointment-summary" class="hidden bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-bold text-blue-800 mb-2">Resumen de tu Cita</h3>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><strong>Fecha:</strong> <span id="summary-date"></span></p>
                                <p><strong>Hora:</strong> <span id="summary-time"></span></p>
                            </div>
                        </div>

                        <!-- hCaptcha -->
                        <div class="h-captcha" data-sitekey="<?php echo $_ENV['HCAPTCHA_SITE_KEY'] ?? ''; ?>"></div>

                        <!-- Botón Enviar -->
                        <div>
                            <button type="submit" 
                                    id="submit-btn"
                                    disabled
                                    class="w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed transition duration-300">
                                Selecciona Fecha y Hora
                            </button>
                        </div>

                        <p class="text-xs text-gray-600 text-center">
                            <span class="text-red-600">*</span> Campos obligatorios
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Horarios de Atención -->
        <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Horarios de Atención</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                    <div class="bg-blue-600 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">Lunes a Miércoles</p>
                        <p class="text-gray-600">16:00 - 20:00 hrs</p>
                    </div>
                </div>
                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="bg-green-600 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">Jueves y Viernes</p>
                        <p class="text-gray-600">10:00 - 13:00 hrs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script de FullCalendar y gestión de citas -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const availableTimesDiv = document.getElementById('available-times');
    const timeSlotsDiv = document.getElementById('time-slots');
    const selectedDateSpan = document.getElementById('selected-date');
    const fechaCitaInput = document.getElementById('fecha_cita');
    const horaCitaInput = document.getElementById('hora_cita');
    const submitBtn = document.getElementById('submit-btn');
    const appointmentSummary = document.getElementById('appointment-summary');
    const summaryDate = document.getElementById('summary-date');
    const summaryTime = document.getElementById('summary-time');

    // Inicializar FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        buttonText: {
            today: 'Hoy'
        },
        validRange: {
            start: new Date().toISOString().split('T')[0]
        },
        selectConstraint: {
            start: new Date()
        },
        dateClick: function(info) {
            const clickedDate = new Date(info.dateStr + 'T12:00:00');
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (clickedDate < today) {
                alert('No puedes seleccionar una fecha pasada');
                return;
            }

            const dayOfWeek = clickedDate.getDay();
            
            // Verificar si es fin de semana
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                alert('No hay atención los fines de semana');
                return;
            }

            // Mostrar horarios disponibles
            showAvailableTimes(info.dateStr, dayOfWeek);
        },
        dayCellClassNames: function(arg) {
            const dayOfWeek = arg.date.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                return ['bg-gray-200'];
            }
            return [];
        }
    });

    calendar.render();

    function showAvailableTimes(dateStr, dayOfWeek) {
        selectedDateSpan.textContent = formatDate(dateStr);
        fechaCitaInput.value = dateStr;
        
        // Definir horarios según el día
        let timeSlots = [];
        
        // Lunes (1), Martes (2), Miércoles (3): 16:00 - 20:00
        if (dayOfWeek >= 1 && dayOfWeek <= 3) {
            timeSlots = [
                '16:00', '16:30', '17:00', '17:30',
                '18:00', '18:30', '19:00', '19:30'
            ];
        }
        // Jueves (4), Viernes (5): 10:00 - 13:00
        else if (dayOfWeek === 4 || dayOfWeek === 5) {
            timeSlots = [
                '10:00', '10:30', '11:00', '11:30',
                '12:00', '12:30'
            ];
        }

        // Generar botones de horarios
        timeSlotsDiv.innerHTML = '';
        timeSlots.forEach(time => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'px-4 py-3 border-2 border-blue-300 text-blue-700 rounded-lg hover:bg-blue-600 hover:text-white hover:border-blue-600 transition duration-200 font-medium';
            btn.textContent = time;
            btn.onclick = function() {
                selectTime(time, dateStr);
            };
            timeSlotsDiv.appendChild(btn);
        });

        availableTimesDiv.classList.remove('hidden');
    }

    function selectTime(time, date) {
        // Remover selección previa
        document.querySelectorAll('#time-slots button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.add('border-blue-300', 'text-blue-700');
        });

        // Marcar botón seleccionado
        event.target.classList.remove('border-blue-300', 'text-blue-700');
        event.target.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

        // Actualizar valores
        horaCitaInput.value = time;
        summaryDate.textContent = formatDate(date);
        summaryTime.textContent = time;

        // Mostrar resumen y habilitar botón
        appointmentSummary.classList.remove('hidden');
        submitBtn.disabled = false;
        submitBtn.className = 'w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition duration-300 shadow-lg cursor-pointer';
        submitBtn.textContent = 'Confirmar Cita';
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr + 'T12:00:00');
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('es-MX', options);
    }
});
</script>

<?php
// Incluir el footer
include '_footer.php';
?>ring-2 focus:ring-blue-500"
                                   placeholder="Juan Pérez">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">
                                Email <span class="text-red-600">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:
