<?php
// Incluir el header
include '../app/views/_header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Nuestros Servicios</h1>
        <p class="text-xl">Atención médica especializada en Otorrinolaringología y Cirugía de Cabeza y Cuello</p>
    </div>
</section>

<!-- Servicios Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- Consulta General -->
        <div class="mb-16">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-4 rounded-full mr-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Consulta Otorrinolaringológica</h2>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-8">
                <p class="text-gray-600 mb-6">Evaluación completa y diagnóstico de padecimientos relacionados con:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-bold text-lg text-blue-800 mb-3">Oído</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Pérdida auditiva y sordera</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Infecciones del oído (otitis)</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Tinnitus (zumbido en los oídos)</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Vértigo y mareos</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-blue-800 mb-3">Nariz</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Sinusitis y rinitis</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Obstrucción nasal</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Desviación del tabique</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Pólipos nasales</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-blue-800 mb-3">Garganta</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Faringitis y amigdalitis</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Problemas de deglución</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Ronquera y disfonía</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Apnea del sueño</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-blue-800 mb-3">Cabeza y Cuello</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Nódulos y tumores</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Glándulas salivales</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Problemas de tiroides</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span>Traumatismos faciales</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cirugías Especializadas -->
        <div class="mb-16">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 p-4 rounded-full mr-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Cirugías Especializadas</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h3 class="font-bold text-xl text-indigo-800 mb-3">Amigdalectomía</h3>
                    <p class="text-gray-600 mb-4">Extirpación quirúrgica de las amígdalas palatinas, indicada en casos de infecciones recurrentes o hipertrofia amigdalar.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Técnica mínimamente invasiva</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Recuperación rápida</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Anestesia general</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h3 class="font-bold text-xl text-indigo-800 mb-3">Septoplastia</h3>
                    <p class="text-gray-600 mb-4">Corrección quirúrgica de la desviación del tabique nasal para mejorar la respiración y función nasal.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Mejora la respiración</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Procedimiento ambulatorio</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Sin cicatrices visibles</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h3 class="font-bold text-xl text-indigo-800 mb-3">Adenoidectomía</h3>
                    <p class="text-gray-600 mb-4">Extirpación de las adenoides, frecuentemente realizada en niños con obstrucción nasal o infecciones recurrentes.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Cirugía pediátrica especializada</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Mejora la calidad de vida</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Alta el mismo día</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h3 class="font-bold text-xl text-indigo-800 mb-3">Cirugía Endoscópica de Senos</h3>
                    <p class="text-gray-600 mb-4">Tratamiento quirúrgico de sinusitis crónica y pólipos nasales mediante técnicas endoscópicas avanzadas.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Técnica de última generación</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Menor trauma quirúrgico</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Resultados duraderos</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h3 class="font-bold text-xl text-indigo-800 mb-3">Microcirugía Laríngea</h3>
                    <p class="text-gray-600 mb-4">Cirugía precisa de cuerdas vocales y laringe para tratar pólipos, nódulos y otras lesiones vocales.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Preservación de la voz</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Microscopio quirúrgico</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Recuperación vocal óptima</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h3 class="font-bold text-xl text-indigo-800 mb-3">Timpanotomía</h3>
                    <p class="text-gray-600 mb-4">Colocación de tubos de ventilación en el tímpano para tratar otitis media recurrente y líquido en el oído.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Procedimiento breve</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Mejora inmediata</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Común en pediatría</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Cirugía Estética Funcional -->
        <div class="mb-16">
            <div class="flex items-center mb-6">
                <div class="bg-purple-100 p-4 rounded-full mr-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Cirugía Estética Funcional</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-xl text-purple-800 mb-4">Rinoplastia</h3>
                    <p class="text-gray-600 mb-4">Cirugía reconstructiva y estética de la nariz que combina mejora funcional respiratoria con armonización facial.</p>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Indicaciones:</h4>
                        <ul class="space-y-1 text-sm text-gray-600">
                            <li>• Corrección de forma y tamaño nasal</li>
                            <li>• Mejora de la respiración</li>
                            <li>• Corrección de trauma nasal</li>
                            <li>• Armonización facial</li>
                        </ul>
                    </div>
                    <div class="bg-purple-50 p-4 rounded">
                        <p class="text-sm text-purple-800"><strong>Nota:</strong> Se realiza evaluación completa funcional y estética previa.</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-xl text-purple-800 mb-4">Otoplastia</h3>
                    <p class="text-gray-600 mb-4">Cirugía correctiva de las orejas prominentes o asimétricas, mejorando la apariencia y autoestima del paciente.</p>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Indicaciones:</h4>
                        <ul class="space-y-1 text-sm text-gray-600">
                            <li>• Orejas prominentes (despegadas)</li>
                            <li>• Asimetría auricular</li>
                            <li>• Deformidades congénitas</li>
                            <li>• Reconstrucción post-trauma</li>
                        </ul>
                    </div>
                    <div class="bg-purple-50 p-4 rounded">
                        <p class="text-sm text-purple-800"><strong>Nota:</strong> Puede realizarse desde los 6 años de edad.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tratamientos Especializados -->
        <div class="mb-16">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-4 rounded-full mr-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Tratamientos Especializados</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-lg text-green-800 mb-3">Tratamiento de Vértigo</h3>
                    <p class="text-gray-600 mb-3">Diagnóstico y manejo especializado de trastornos del equilibrio y mareos.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Maniobras de reposicionamiento</li>
                        <li>• Rehabilitación vestibular</li>
                        <li>• Tratamiento farmacológico</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-lg text-green-800 mb-3">Manejo de Tinnitus</h3>
                    <p class="text-gray-600 mb-3">Evaluación integral y tratamiento del zumbido en los oídos.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Evaluación audiológica completa</li>
                        <li>• Terapia de sonido</li>
                        <li>• Counseling especializado</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-lg text-green-800 mb-3">Terapia de Voz</h3>
                    <p class="text-gray-600 mb-3">Rehabilitación vocal para problemas de la voz y cuerdas vocales.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Evaluación fonoaudiológica</li>
                        <li>• Ejercicios de rehabilitación vocal</li>
                        <li>• Seguimiento especializado</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Servicios Adicionales -->
        <div class="mb-16">
            <div class="flex items-center mb-6">
                <div class="bg-orange-100 p-4 rounded-full mr-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Servicios Adicionales</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-xl text-orange-800 mb-4">Adaptación Auditiva</h3>
                    <p class="text-gray-600 mb-4">Selección, adaptación y seguimiento de auxiliares auditivos de última generación.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Audiometría y pruebas especializadas</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Selección personalizada del dispositivo</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Ajuste y programación</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Seguimiento y mantenimiento</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-xl text-orange-800 mb-4">Aplicación de Vacunas</h3>
                    <p class="text-gray-600 mb-4">Inmunización especializada para prevención de enfermedades otorrinolaringológicas.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Vacuna contra influenza</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Vacuna antineumocócica</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Esquemas de vacunación personalizada</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-orange-600 mr-2">✓</span>
                            <span>Orientación y seguimiento</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-xl p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">¿Necesitas una Consulta?</h2>
            <p class="text-xl mb-6">Agenda tu cita y recibe atención médica especializada</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/agendar-cita.php" class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition duration-300">
                    Agendar Cita
                </a>
                <a href="/contacto.php" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-blue-600 transition duration-300">
                    Contactar
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Incluir el footer
include '../app/views/_footer.php';
?>
