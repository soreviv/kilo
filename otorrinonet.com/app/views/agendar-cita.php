<?php
// Incluir el header. Las variables como $pageTitle, $status, $errors y $old_data
// son pasadas desde AppointmentController->create().
require '_header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Agendar Cita') ?></h1>
        <p class="text-xl">Selecciona la fecha y hora que mejor te convenga</p>
    </div>
</section>

<!-- Sección para mostrar mensajes de estado (éxito o error) -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <?php if (isset($status) && $status): ?>
            <?php if ($status['type'] === 'success'): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 rounded-lg shadow-lg" role="alert">
                    <p class="font-bold text-lg"><?= htmlspecialchars($status['message']) ?></p>
                </div>
            <?php else: ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg shadow-lg" role="alert">
                    <p class="font-bold text-lg"><?= htmlspecialchars($status['message']) ?></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Agendamiento de Citas -->
<section class="pb-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">1. Selecciona Fecha y Hora</h2>
                    <div id="calendar" class="mb-6"></div>
                    <div id="available-times" class="hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Horarios Disponibles</h3>
                        <p class="text-gray-600 mb-4">Fecha seleccionada: <span id="selected-date" class="font-bold"></span></p>
                        <div id="time-slots" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Cita -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">2. Datos del Paciente</h2>

                    <form method="POST" action="/agendar-cita" id="appointment-form" class="space-y-4">
                        <input type="hidden" name="fecha_cita" id="fecha_cita" value="<?= htmlspecialchars($old_data['fecha_cita'] ?? '') ?>">
                        <input type="hidden" name="hora_cita" id="hora_cita" value="<?= htmlspecialchars($old_data['hora_cita'] ?? '') ?>">
                        
                        <!-- (Form fields remain the same) -->

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mantenemos el mismo JavaScript para el calendario -->
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

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth' },
        buttonText: { today: 'Hoy' },
        validRange: { start: new Date().toISOString().split('T')[0] },
        dateClick: function(info) {
            const dayOfWeek = new Date(info.dateStr + 'T00:00:00').getDay();
            if (dayOfWeek === 6 || dayOfWeek === 0) { return; }
            fetchAvailableTimes(info.dateStr);
        },
        dayCellClassNames: function(arg) {
            const dayOfWeek = arg.date.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) { return ['bg-gray-100', 'cursor-not-allowed']; }
            return [];
        }
    });
    calendar.render();

    async function fetchAvailableTimes(dateStr) {
        selectedDateSpan.textContent = formatDate(dateStr);
        fechaCitaInput.value = dateStr;
        horaCitaInput.value = '';
        updateSubmitButton();

        availableTimesDiv.classList.remove('hidden');
        timeSlotsDiv.innerHTML = '<p class="text-gray-500">Cargando horarios...</p>';

        try {
            const response = await fetch(`/api/available-times?date=${dateStr}`);
            if (!response.ok) {
                throw new Error('Error al cargar los horarios.');
            }
            const timeSlots = await response.json();

            timeSlotsDiv.innerHTML = '';
            if (timeSlots.length === 0) {
                timeSlotsDiv.innerHTML = '<p class="text-red-500">No hay horarios disponibles para este día.</p>';
                return;
            }

            timeSlots.forEach(time => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'px-4 py-3 border-2 border-blue-300 text-blue-700 rounded-lg hover:bg-blue-600 hover:text-white transition duration-200';
                btn.textContent = time;
                btn.onclick = () => selectTime(time, dateStr);
                timeSlotsDiv.appendChild(btn);
            });
        } catch (error) {
            console.error(error);
            timeSlotsDiv.innerHTML = '<p class="text-red-500">No se pudieron cargar los horarios. Inténtalo de nuevo.</p>';
        }
    }

    function selectTime(time, date) {
        document.querySelectorAll('#time-slots button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
        });
        event.target.classList.add('bg-blue-600', 'text-white');

        horaCitaInput.value = time;
        summaryDate.textContent = formatDate(date);
        summaryTime.textContent = time;
        appointmentSummary.classList.remove('hidden');
        updateSubmitButton();
    }

    function updateSubmitButton() {
        if (fechaCitaInput.value && horaCitaInput.value) {
            submitBtn.disabled = false;
            submitBtn.className = 'w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-bold cursor-pointer transition';
            submitBtn.textContent = 'Confirmar Cita';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed transition';
            submitBtn.textContent = 'Selecciona Fecha y Hora';
        }
    }

    function formatDate(dateStr) {
        return new Date(dateStr + 'T00:00:00').toLocaleDateString('es-MX', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    }

    // Si hay una fecha/hora antigua, pre-selecciónala
    if (fechaCitaInput.value) {
        fetchAvailableTimes(fechaCitaInput.value);

        if (horaCitaInput.value) {
            setTimeout(() => {
                const buttons = document.querySelectorAll('#time-slots button');
                buttons.forEach(btn => {
                    if (btn.textContent === horaCitaInput.value) {
                        btn.click();
                    }
                });
            }, 500); // Pequeño retraso para dar tiempo a la API a responder
        }
    }
});
</script>

<?php require '_footer.php'; ?>
