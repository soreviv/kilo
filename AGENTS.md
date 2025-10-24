# Instrucciones para Agentes en OtorrinoNet

Este documento proporciona directrices para que los agentes (humanos o IA) trabajen de manera efectiva en el proyecto OtorrinoNet.

## 1. Resumen del Proyecto

OtorrinoNet es una aplicación web desarrollada en PHP para la gestión de citas médicas en una clínica de otorrinolaringología. Permite a los pacientes agendar citas y a los administradores gestionarlas a través de un panel. El backend es PHP puro sin un framework principal, y utiliza PostgreSQL como base de datos.

## 2. Entorno de Desarrollo

- **Dependencias**: El proyecto usa Composer para gestionar las dependencias de PHP. Ejecuta el siguiente comando para instalarlas:
  ```bash
  composer install
  ```
- **Configuración**: La configuración del entorno se gestiona a través de un archivo `.env`. Copia el archivo de ejemplo y ajústalo con tus credenciales locales:
  ```bash
  cp otorrinonet/.env.example otorrinonet/.env
  ```

## 3. Estilo de Código

- **Estándar**: Sigue el estándar de codificación **PSR-12** para todo el código PHP.
- **Consistencia**: Mantén la consistencia con el estilo del código existente. Antes de escribir código nuevo, revisa los archivos cercanos para entender las convenciones de nomenclatura y estructura.

## 4. Instrucciones de Testing

- **Estado Actual**: El proyecto actualmente carece de una suite de pruebas automatizadas. Añadir pruebas es una prioridad alta.
- **Herramienta Recomendada**: Utiliza **PHPUnit** para escribir pruebas unitarias y de integración.
- **Comando de Ejecución**: Una vez que PHPUnit esté configurado, las pruebas se podrán ejecutar con:
  ```bash
  ./vendor/bin/phpunit
  ```
- **Qué Probar**:
  - Añade pruebas unitarias para nuevas clases o lógica de negocio.
  - Asegúrate de que cualquier cambio en el código existente esté cubierto por pruebas para evitar regresiones.

## 5. Consideraciones de Seguridad

- **Inyección SQL**: La clase `App\Core\Database` utiliza PDO. **Utiliza siempre consultas preparadas y parametrizadas** para interactuar con la base de datos y prevenir ataques de inyección SQL. No construyas consultas concatenando strings.
- **Cross-Site Scripting (XSS)**: **Escapa siempre toda la salida** que se renderiza en las vistas HTML para prevenir ataques XSS. Utiliza funciones como `htmlspecialchars()` cuando muestres datos que provienen de la base de datos o de la entrada del usuario.
- **Secretos**: Nunca guardes contraseñas, claves de API u otros secretos directamente en el código. Utiliza el archivo `.env` para esta información, el cual no debe ser incluido en el control de versiones.

## 6. Guía para Commits

- **Formato**: Utiliza "Conventional Commits". El formato es `tipo: descripción`.
  - `feat`: Para nuevas funcionalidades.
  - `fix`: Para corrección de errores.
  - `docs`: Para cambios en la documentación.
  - `style`: Para cambios de formato que no afectan la lógica.
  - `refactor`: Para refactorizaciones de código.
  - `test`: Para añadir o corregir pruebas.
  - `chore`: Para tareas de mantenimiento (actualizar dependencias, etc.).

- **Ejemplo**:
  ```
  feat: Añadir paginación a la lista de citas del administrador
  ```
