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
        <div class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="px-8 py-4 bg-gray-900">
                <h2 class="text-xl font-bold">Admin Panel</h2>
            </div>
            <nav class="flex-1 px-4 py-4 space-y-2">
                <a href="/admin/dashboard" class="flex items-center px-4 py-2 text-gray-100 bg-gray-700 rounded-md">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <!-- Aquí irán más enlaces (ej. Citas, Mensajes) -->
            </nav>
            <div class="px-4 py-4 border-t border-gray-700">
                <a href="/admin/logout" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-md">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H3"></path></svg>
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow">
                <div class="container mx-auto px-6 py-4">
                    <h1 class="text-xl font-bold text-gray-800">Bienvenido al Dashboard</h1>
                </div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                <div class="container mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-700">Resumen</h2>
                    <!-- Aquí irá el contenido del dashboard -->
                    <div class="bg-white shadow-md rounded my-6 p-6">
                        <p>Contenido del panel de administración.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>