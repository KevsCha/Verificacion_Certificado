# 📄 Verificador de Certificados Digitales

Este proyecto permite a los usuarios verificar si un certificado está emitido y registrado en la base de datos. Si la verificación es exitosa, el sistema envía un correo al consultor con los detalles del certificado. Está construido en PHP con arquitectura MVC, y utiliza AJAX para una experiencia de usuario fluida.

----------

## 🚀 Tecnologías utilizadas

- PHP (Programación Orientada a Objetos)
- MySQL
- HTML + CSS + JavaScript (AJAX)
- PHPMailer + Gmail API con OAuth2 (para envío de emails)
- Arquitectura MVC (Modelo - Vista - Controlador)
- Excepciones personalizadas (`ValidationException`, `NotFoundException`, etc.)

-----------

## 📁 Estructura del Proyecto
    📁 VerificacionCertificado
    ├── 📁 SQL
        ├── data.sql(archivo para pruebas)
    ├── 📁 Vendor/ (php MAILER)
    ├── 📁 src/
    |    ├── 📁 config/
    |    |    ├── 🐘connect.php
    |    |    ├── 🐘getCredentials.php
    |    |    ├── 🐘getToken.php
    |    |    ├── credentials.json
    |    |    ├── token.json
    |    ├── 📁 js/
    |    |    ├── formulario.js
    |    ├── 📁 entities/ 
    |    |    ├──🐘Certificado_Emitido.php
    |    |    ├──🐘Consultores.php
    |    |    ├──🐘Historial_consultas.php
    |    |    ├──🐘Persona.php
    |    ├── 📁 repository/
    |    |    ├──🐘Certificado_EmitidoRepository.php
    |    |    ├──🐘ConsultoresRepository.php
    |    |    ├──🐘Historial_consultasRepository.php
    |    ├── 📁 services/
    |    |    ├──🐘Certificado_EmitidoService.php
    |    |    ├──🐘ConsultoresService.php
    |    |    ├──🐘Historial_consultasService.php
    |    ├── 📁 exception/
    |    |    ├──🐘NotFoundException.php
    |    |    ├──🐘ValidationException.php
    |    📄 consulta.php # Punto de entrada del formulario AJAX
    |    📄 SendEmail.php # Clase para enviar correos
    |    📄 database.sql # Script para crear tablas
    |    📄 index.html # Formulario principal
    |    📄 style.css # Estilos (popups de error, layout)
    ├──📄 index.html


#### ✅ Tecnologías
```markdown
- PHP (POO)
- MySQL
- AJAX + HTML
- Gmail API (OAuth2) con PHPMailer
```

## Flujo de la aplicación

1. El usuario completa el formulario HTML.
2. El frontend envía los datos vía AJAX a `consulta.php`.
3. Se instancian los servicios y repositorios.
4. Se validan los datos del certificado.
5. Si todo es válido, se envía el correo y se guarda el historial.
6. Si hay errores, se devuelven y se muestran en pantalla con CSS/JS.


