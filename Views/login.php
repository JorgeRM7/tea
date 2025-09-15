<!doctype html>
<html lang="es" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TEA - Login</title>
    <link rel="icon" type="image/x-icon" href="https://tea.systemsorion.com/Librerias/img/logo" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            
            --tea-green: #38b449;
            --tea-dark-green: #0a4d0e;
            --tea-orange: #f2a71e;

            /* Colores generales del diseño */
            --bg-color: #0d1a1e; /* Fondo oscuro que combina con el logo */
            --box-bg: rgba(255, 255, 255, 0.08); /* Fondo de la caja de login semitransparente */
            --border-color: rgba(255, 255, 255, 0.15); /* Borde claro para los elementos */
            --text-light: #e0e6eb; /* Texto principal claro */
            --text-gray: #a9b5bd; /* Texto secundario (placeholders, subtítulos) */
            --input-bg: rgba(255, 255, 255, 0.05); /* Fondo de los campos de input */
            
            /* Degradados para el diseño, usando los colores de TEA */
            --gradient-main: linear-gradient(45deg, var(--tea-green), var(--tea-dark-green));
            --gradient-button: linear-gradient(90deg, var(--tea-green), var(--tea-orange));
            --glow-color: rgba(56, 180, 73, 0.4); /* Resplandor basado en el verde */
        }

        body {
            cursor: url('https://tea.systemsorion.com/Librerias/img/logo') 16 16, auto;
            background: var(--bg-color);
            min-height: 100vh;
            font-family: 'Public Sans', sans-serif;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
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
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-box {
            background: var(--box-bg);
            backdrop-filter: blur(25px); /* Un blur más intenso */
            max-width: 450px; /* Un poco más ancho */
            width: 100%;
            padding: 3.5rem; /* Más padding */
            border-radius: 20px; /* Bordes ligeramente más suaves */
            border: 1px solid var(--border-color);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada */
            animation: fadeIn 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        /* Efecto de brillo detrás de la caja */
        .login-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--gradient-main); /* Usamos el degradado de TEA */
            filter: blur(80px); /* Un blur más grande para un efecto de luz suave */
            opacity: 0.2;
            animation: rotateGlow 20s linear infinite;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem; /* Más espacio para el logo */
        }

        .logo-img {
            max-width: 150px; /* Logo un poco más grande */
            filter: drop-shadow(0 0 20px var(--tea-green)); /* Resplandor verde */
            transition: all 0.4s ease;
        }

        .logo-img:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 0 30px var(--tea-orange)); /* Resplandor naranja al pasar el mouse */
        }

        .login-title {
            font-weight: 700;
            font-size: 2.8rem;
            background: var(--gradient-button);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 0.8rem;
            animation: fadeInUp 1s ease-out;
        }

        .login-subtitle {
            color: var(--text-gray);
            text-align: center;
            margin-bottom: 3rem; /* Más espacio */
            font-size: 1.05rem;
            animation: fadeInUp 1.2s ease-out;
        }

        .form-label {
            color: var(--text-light);
            font-weight: 600;
            margin-bottom: 0.6rem; /* Más espacio entre label y input */
            display: flex;
            align-items: center;
            gap: 0.7rem; /* Espacio entre icono y texto */
        }
        
        .form-label i {
            color: var(--tea-green); /* Iconos en verde TEA */
        }

        .form-control {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px; /* Bordes de input un poco más rectos */
            padding: 0.95rem 1.4rem; /* Más padding en los inputs */
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--tea-green); /* Borde verde al enfocar */
            box-shadow: 0 0 0 0.25rem var(--glow-color); /* Sombra de resplandor verde */
        }

        .form-control::placeholder {
            color: var(--text-gray);
        }

        .btn-primary {
            background: var(--gradient-button); /* Botón con degradado verde a naranja */
            border: none;
            border-radius: 12px;
            padding: 1.1rem 2.5rem; /* Botón más grande */
            font-weight: 700;
            font-size: 1.2rem; /* Texto del botón más grande */
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px; /* Ligeramente más espaciado */
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease-in-out;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-5px); /* Efecto de "levantar" más pronunciado */
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.4);
            filter: brightness(1.1); /* Ligeramente más brillante */
        }

        .input-group-text.toggle-password {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-left: none;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            color: var(--text-gray);
            transition: all 0.3s ease;
        }

        .input-group-text.toggle-password:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--tea-orange); /* Icono naranja al pasar el mouse */
        }
        
        /* Icono de ojo en el input de contraseña */
        #eye-icon {
            color: var(--text-gray); /* Color gris por defecto */
        }
        
        .input-group .form-control {
            border-right: none;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes rotateGlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Partículas JS con colores TEA */
        /* Ajustadas directamente en el JS */
    </style>
</head>

<body>
    <div id="particles-js"></div>

    <div class="login-container">
        <div class="login-box animate__animated animate__fadeIn">
            <div id="camara-wrapper" class="d-none" style="display: none;">
                <video id="video" width="280" height="210" autoplay muted style="display: none;"></video>
                <canvas id="canvas" width="280" height="210" style="display: none;"></canvas>
            </div>

            <div class="logo-container animate__animated animate__zoomIn">
                <img src="https://tea.systemsorion.com/Librerias/img/logo" class="logo-img" alt="Logo TEA" />
                </div>

            <!--<h4 class="login-title animate__animated animate__fadeInUp">Bienvenido a TEA</h4>-->
            <p class="login-subtitle animate__animated animate__fadeInUp animate__delay-1s">Ingresa tus credenciales para acceder</p>

            <form name="formulario" id="formulario" method="POST">
                <div class="mb-3 animate__animated animate__fadeInLeft animate__delay-2s">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        Usuario
                    </label>
                    <input type="text" class="form-control" id="login_usuario" name="login_usuario" placeholder="Correo o usuario" />
                </div>

                <div class="mb-4 animate__animated animate__fadeInRight animate__delay-3s position-relative">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Contraseña
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="login_clave" name="login_clave" placeholder="Ingresa tu contraseña" />
                        <span class="input-group-text toggle-password" onclick="togglePassword()" style="cursor: pointer;">
                            <i id="eye-icon" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="button" class="btn btn-primary w-100 animate__animated animate__fadeInUp animate__delay-4s" onclick="login()">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Iniciar sesión
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        let modeloCargado = false;

        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 70, /* Más partículas */
                        "density": {
                            "enable": true,
                            "value_area": 1000 /* Área de densidad */
                        }
                    },
                    "color": {
                        "value": "#38b449" /* Partículas en verde TEA */
                    },
                    "shape": {
                        "type": "circle"
                    },
                    "opacity": {
                        "value": 0.6, /* Más opacas */
                        "random": true
                    },
                    "size": {
                        "value": 3.5, /* Un poco más grandes */
                        "random": true
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 180, /* Líneas más largas */
                        "color": "#f2a71e", /* Líneas en naranja TEA */
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": true,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "bubble" /* Efecto bubble al pasar el mouse */
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "bubble": {
                            "distance": 150, /* Distancia del bubble */
                            "size": 6, /* Tamaño del bubble */
                            "duration": 2,
                            "opacity": 0.8,
                            "speed": 3
                        },
                        "push": {
                            "particles_nb": 4
                        }
                    }
                },
                "retina_detect": true
            });
        });


        const login = () => {
            const login_usuario = document.getElementById("login_usuario").value.trim();
            const login_clave = document.getElementById("login_clave").value.trim();

            if (!login_usuario || !login_clave) {
                Swal.fire({
                    title: "Campos requeridos",
                    text: "Por favor ingresa tu usuario y contraseña",
                    icon: "warning",
                    confirmButtonColor: "#38b449"
                });
                return;
            }
            const formData = new FormData(document.getElementById("formulario"));
            $.ajax({
                url: "../Controllers/loginController.php?op=verificar",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response)

                    if( response == null ){
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Éxito',
                            text: 'Usuario y/o contraseña incorrectos.',
                        });
                        
                    }else{
                        window.location.href = "admin-employees.php";
                    }
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo guardar el registro.",
                        icon: "error"
                    });
                }
            });
        };
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                login();
            }
        });

        function togglePassword() {
            const input = document.getElementById("login_clave");
            const icon = document.getElementById("eye-icon");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>