<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a1a2e; color: white; padding: 20px; text-align: center; }
        .content { background: #f4f4f4; padding: 30px; }
        .password-box { background: white; padding: 20px; border-left: 4px solid #0f3460; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎬 DarkMovies</h1>
        </div>
        <div class="content">
            <h2>Hola {{ $customerName }},</h2>
            <p>Recibimos una solicitud para recuperar tu contraseña.</p>
            
            <div class="password-box">
                <p><strong>Tu información de acceso:</strong></p>
                <p>Correo: <strong>{{ $email }}</strong></p>
                <p>Contraseña (ID de dirección): <strong>{{ $password }}</strong></p>
            </div>
            
            <p>Si no solicitaste esta recuperación, ignora este correo.</p>
            <p>Por seguridad, te recomendamos cambiar tu contraseña después de iniciar sesión.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} DarkMovies. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>