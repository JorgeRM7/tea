<!doctype html>
<html lang="es" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TEA - No autorizado</title>
    <link rel="icon" type="image/x-icon" href="https://tea.systemsorion.com/Librerias/img/logo" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --tea-green: #38b449;
            --tea-dark-green: #0a4d0e;
            --tea-orange: #f2a71e;
            --bg-color: #0d1a1e;
            --box-bg: rgba(255, 255, 255, 0.08);
            --border-color: rgba(255, 255, 255, 0.15);
            --text-light: #e0e6eb;
            --text-gray: #a9b5bd;
            --gradient-main: linear-gradient(45deg, var(--tea-green), var(--tea-dark-green));
            --gradient-button: linear-gradient(90deg, var(--tea-green), var(--tea-orange));
            --glow-color: rgba(56, 180, 73, 0.4);
        }

        body {
            background: var(--bg-color);
            min-height: 100vh;
            font-family: 'Public Sans', sans-serif;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-box {
            background: var(--box-bg);
            backdrop-filter: blur(25px);
            max-width: 500px;
            width: 100%;
            padding: 3rem;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.8s ease-out;
            position: relative;
            overflow: hidden;
            text-align: center;
            z-index: 1;
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--gradient-main);
            filter: blur(80px);
            opacity: 0.2;
            animation: rotateGlow 20s linear infinite;
            z-index: 0;
        }

        .logo-img {
            max-width: 120px;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 20px var(--tea-green));
        }

        .login-title {
            font-weight: 700;
            font-size: 2.5rem;
            background: var(--gradient-button);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .login-subtitle {
            color: var(--text-gray);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .timer {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--tea-orange);
        }

        @keyframes rotateGlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>
    <div class="login-container">
        <div class="login-box animate__animated animate__fadeIn">
            <img src="https://tea.systemsorion.com/Librerias/img/logo" class="logo-img" alt="Logo TEA" />
            <h4 class="login-title">Acceso denegado</h4>
            <p class="login-subtitle">No tienes permisos para acceder a esta sección.<br>
            Contacta al administrador si crees que es un error.</p>
            <p class="timer">Redirigiendo en <span id="countdown">5</span> segundos...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Partículas
        particlesJS('particles-js', {
            "particles": {
                "number": { "value": 70, "density": { "enable": true, "value_area": 1000 } },
                "color": { "value": "#38b449" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.6, "random": true },
                "size": { "value": 3.5, "random": true },
                "line_linked": {
                    "enable": true,
                    "distance": 180,
                    "color": "#f2a71e",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": { "enable": true, "speed": 2 }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": { "enable": true, "mode": "bubble" },
                    "onclick": { "enable": true, "mode": "push" }
                },
                "modes": {
                    "bubble": { "distance": 150, "size": 6, "duration": 2, "opacity": 0.8, "speed": 3 }
                }
            },
            "retina_detect": true
        });

        // ⏳ Temporizador con redirección
        let seconds = 5;
        const countdownEl = document.getElementById("countdown");

        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "inicio.php";
            }
        }, 1000);
    </script>
</body>
</html>
