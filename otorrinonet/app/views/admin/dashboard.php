<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Administración') ?></title>
    <link href="/assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <?php include '_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow">
                <div class="container mx-auto px-6 py-4">
                    <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
                </div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                <div class="container mx-auto">
                    <!-- Stat Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-600">Citas Pendientes</h3>
                            <p class="text-3xl font-bold text-blue-500 mt-2"><?= (int)($pendingAppointmentsCount ?? 0) ?></p>
                        </div>
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-600">Mensajes sin Leer</h3>
                            <p class="text-3xl font-bold text-green-500 mt-2"><?= (int)($unreadMessagesCount ?? 0) ?></p>
                        </div>
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-600">Citas para Hoy</h3>
                            <p class="text-3xl font-bold text-indigo-500 mt-2"><?= count($appointmentsToday ?? []) ?></p>
                        </div>
                    </div>

                    <!-- Appointments for Today -->
                    <div class="mt-8">
                        <h2 class="text-2xl font-semibold text-gray-700">Citas para Hoy</h2>
                        <div class="bg-white shadow-md rounded my-6">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hora</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Paciente</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($appointmentsToday)): ?>
                                        <tr>
                                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">No hay citas para hoy.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php
                                        $statusColors = [
                                            'pendiente' => 'bg-yellow-200 text-yellow-900',
                                            'confirmada' => 'bg-green-200 text-green-900',
                                            'cancelada' => 'bg-red-200 text-red-900',
                                            'completada' => 'bg-blue-200 text-blue-900',
                                            'no_asistio' => 'bg-gray-200 text-gray-900',
                                        ];
                                        ?>
                                        <?php foreach ($appointmentsToday as $appointment): ?>
                                            <?php $badgeClasses = $statusColors[$appointment['status']] ?? 'bg-gray-200 text-gray-900'; ?>
                                            <tr>
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars(substr($appointment['hora_cita'], 0, 5)) ?></p>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($appointment['nombre']) ?></p>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars(str_replace('_', ' ', $appointment['tipo_consulta'])) ?></p>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= $badgeClasses ?>">
                                                        <span aria-hidden class="absolute inset-0 opacity-50 rounded-full <?= $badgeClasses ?>"></span>
                                                        <span class="relative"><?= htmlspecialchars(ucfirst($appointment['status'])) ?></span>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>