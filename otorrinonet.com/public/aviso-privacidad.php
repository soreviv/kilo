<?php
// Incluir el header
include '../app/views/_header.php';
?>

<!-- Privacy Notice Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Aviso de Privacidad</h1>
        <p class="text-xl">Información sobre el tratamiento de sus datos personales</p>
    </div>
</section>

<!-- Privacy Notice Content -->
<section class="py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Responsable del Tratamiento de Datos Personales</h2>
                <p class="text-gray-700 leading-relaxed">Dr. Alejandro Viveros Domínguez, con cédula profesional 6277305 y especialidad 10148701, con domicilio en Buenavista 20, Col. Lindavista, Gustavo A. Madero, Ciudad de México, CDMX 07750, es el responsable del tratamiento de sus datos personales.</p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Datos Personales que Recopilamos</h2>
                <p class="text-gray-700 leading-relaxed mb-4">Para prestar nuestros servicios médicos, recopilamos los siguientes datos personales:</p>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        <span><strong>Datos de identificación:</strong> Nombre completo, fecha de nacimiento, sexo, nacionalidad</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        <span><strong>Datos de contacto:</strong> Domicilio, teléfono, correo electrónico</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        <span><strong>Datos médicos:</strong> Historia clínica, diagnósticos, tratamientos, medicamentos</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        <span><strong>Datos de facturación:</strong> Información fiscal para emisión de comprobantes</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        <span><strong>Datos de citas:</strong> Fechas y horarios de consultas, tipos de servicio</span>
                    </li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">3. Finalidades del Tratamiento</h2>
                <p class="text-gray-700 leading-relaxed mb-4">Utilizamos sus datos personales para las siguientes finalidades:</p>
                
                <h3 class="text-xl font-bold text-gray-800 mb-3">Finalidades primarias:</h3>
                <ul class="space-y-2 text-gray-700 mb-4">
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Prestar servicios médicos especializados en otorrinolaringología</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Agendar y gestionar citas médicas</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Elaborar y mantener expedientes clínicos</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Realizar diagnósticos y tratamientos médicos</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Emitir comprobantes fiscales</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Dar seguimiento a tratamientos y evolución del paciente</span>
                    </li>
                </ul>
                
                <h3 class="text-xl font-bold text-gray-800 mb-3">Finalidades secundarias:</h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Enviar recordatorios de citas médicas</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Compartir información relevante sobre salud y prevención</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Realizar encuestas de satisfacción</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">✓</span>
                        <span>Mejorar nuestros servicios médicos</span>
                    </li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">4. Derechos ARCO</h2>
                <p class="text-gray-700 leading-relaxed mb-4">Usted tiene derecho a ejercer los derechos de Acceso, Rectificación, Cancelación y Oposición:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-bold text-blue-800 mb-2">Acceso</h4>
                        <p class="text-sm text-gray-700">Conocer qué datos personales tenemos de usted</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-bold text-blue-800 mb-2">Rectificación</h4>
                        <p class="text-sm text-gray-700">Corregir datos personales inexactos o incompletos</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-bold text-blue-800 mb-2">Cancelación</h4>
                        <p class="text-sm text-gray-700">Eliminar sus datos personales de nuestros registros</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-bold text-blue-800 mb-2">Oposición</h4>
                        <p class="text-sm text-gray-700">Oponerse al tratamiento de sus datos para fines específicos</p>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">5. Medidas de Seguridad</h2>
                <p class="text-gray-700 leading-relaxed mb-4">Implementamos las siguientes medidas de seguridad:</p>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">🔒</span>
                        <span>Encriptación de datos sensibles</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">🔒</span>
                        <span>Control de acceso restringido al personal autorizado</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">🔒</span>
                        <span>Sistemas de respaldo y recuperación de información</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">🔒</span>
                        <span>Actualizaciones regulares de software de seguridad</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">🔒</span>
                        <span>Capacitación continua del personal en protección de datos</span>
                    </li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">6. Contacto</h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="font-bold text-gray-800 mb-2">Departamento de Datos Personales</p>
                    <p class="text-gray-700 mb-1">Dr. Alejandro Viveros Domínguez</p>
                    <p class="text-gray-700 mb-1">Dirección: Buenavista 20, Col. Lindavista, CDMX 07750</p>
                    <p class="text-gray-700 mb-1">Email: privacidad@otorrinonet.com</p>
                    <p class="text-gray-700">Teléfono: +52 55 1234-5678</p>
                </div>
            </div>

            <div class="bg-blue-100 border-l-4 border-blue-600 p-6 rounded">
                <p class="text-sm text-blue-800"><strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?></p>
                <p class="text-sm text-blue-800 mt-2">Este aviso cumple con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares y su Reglamento.</p>
            </div>
        </div>
    </div>
</section>

<?php
// Incluir el footer
include '../app/views/_footer.php';
?>
