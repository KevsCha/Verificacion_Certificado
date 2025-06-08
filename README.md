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


## ✉️ Configuración de PHPMailer con Gmail y OAuth2
Este proyecto utiliza PHPMailer junto con la Gmail API y OAuth2 para enviar correos electrónicos de forma segura, sin necesidad de contraseñas SMTP tradicionales.

#### 🔧 Requisitos
``` 
- Cuenta de Google
- Acceso a Google Cloud Console
- PHPMailer instalado (vía Composer o manualmente)
- Librería league/oauth2-client y league/oauth2-google
```
#### 📦 Instalación de dependencias (si usas Composer)

```bash
composer require phpmailer/phpmailer
composer require league/oauth2-client
composer require league/oauth2-google
```

### ⚙️ 1. Configurar Google Cloud Console
1. Ir a Google Cloud Console.
2. Crear un nuevo proyecto.
3. Ir a "APIs y servicios" > "Pantalla de consentimiento OAuth":
    
    3.1 Tipo de usuario: Interno (modo prueba) o Externo si vas a lanzarlo públicamente.
    
    3.2 Agrega el email de prueba (la cuenta que usará PHPMailer para enviar correos).
4. Ir a "Credenciales" > "Crear credenciales" > ID de cliente de OAuth 2.0:
    
    4.1 Tipo de aplicación: Aplicación de escritorio o Web.
    
    4.2 Descarga el archivo credentials.json.

### 🔑 2. Obtener tokens OAuth2
Obtener el accessToken y refreshToken ejecutando el script `oauth_setup.php`, se generara un archivo llamado `token.json` con la informacion necesaria.

---
## Para otro provedor (Hostinger)

## ✉️ Configuración de PHPMailer para Hostinger (SMTP clásico)
Este proyecto puede enviar correos usando PHPMailer y una cuenta de correo configurada en Hostinger mediante autenticación SMTP.
### 📧 Requisitos
Una cuenta de correo configurada en Hostinger (por ejemplo: info@tu-dominio.com)

```
- Acceso al panel de control de Hostinger (hPanel)
- PHPMailer instalado en tu proyecto

```

### 📦 Instalación de PHPMailer
Si usas Composer:

```bash
composer require phpmailer/phpmailer
```

## ⚙️ Datos SMTP típicos de Hostinger
Parámetro	Valor
Host SMTP	smtp.hostinger.com
Puerto	465 (SSL) o 587 (TLS)
Cifrado	ssl o tls
Autenticación SMTP	Sí
Usuario	Tu correo completo (ej: info@...)
Contraseña	Contraseña del correo


| Parameter | Value    | 
| :-------- | :------- |
| `Host SMTP` | smtp.hostinger.com |
| `Puerto` | 465 (SSL) o 587 (TLS) |
| `Cifrado` | ssl o tls |
| `Autenticacion SMTP` | Sí |
| `Usuario ` | Tu correo completo (ej: info@...) |
| `Contraseña` | Contraseña del correo |

### 🧩 Ejemplo de configuración PHPMailer para Hostinger
```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // O el archivo autoload.php de PHPMailer

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@tudominio.com'; // Tu correo en Hostinger
    $mail->Password   = 'tu_contraseña';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usar 'PHPMailer::ENCRYPTION_STARTTLS' si usas puerto 587
    $mail->Port       = 465; // o 587 para TLS

    // Remitente y destinatario
    $mail->setFrom('info@tudominio.com', 'Nombre Remitente');
    $mail->addAddress('destinatario@ejemplo.com', 'Nombre Destinatario');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Asunto de prueba';
    $mail->Body    = '<h1>Hola desde Hostinger</h1><p>Este es un correo de prueba.</p>';
    $mail->AltBody = 'Este es el contenido alternativo en texto plano.';

    $mail->send();
    echo 'El correo ha sido enviado correctamente.';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
```

### 🔐 Diferencia entre SMTP clásico (como Hostinger) y OAuth2 (como Gmail con tokens)


| Característica | SMTP clásico (Hostinger) | SMTP con OAuth2 (Gmail)|
| -------------------------- | ------------------------------------------------- | ------------------------------------------------- |
| 🔑 Método de autenticación | Usuario + contraseña                              | Token OAuth2 (access token y refresh token)       |
| 🔐 Seguridad               | Baja a media (la contraseña queda expuesta)       | Alta (no se usa la contraseña directamente)       |
| 🔄 Revocación              | Debes cambiar la contraseña manualmente           | Puedes revocar o renovar tokens desde Google      |
| 🔧 Configuración           | Fácil: solo email y contraseña                    | Compleja: necesitas registrar app, scopes, tokens |
| 📡 Proveedor               | Casi todos los SMTP tradicionales (ej. Hostinger) | Gmail, Outlook, etc., que exigen OAuth2           |
| 📜 Permisos                | Accede completo con usuario/contraseña            | Permiso limitado a scopes definidos (ej. `send`)  |
| 🔎 Registro de actividad   | No siempre disponible                             | Visible en tu cuenta Google |
