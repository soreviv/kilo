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