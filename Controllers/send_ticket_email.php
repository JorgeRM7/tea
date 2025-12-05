<?php
require_once dirname(__DIR__) . "/Database/conexion.php";
require_once __DIR__ . "/../vendor/autoload.php";
$config = include dirname(__DIR__) . "/Config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Obtener datos desde JS
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? null;
$tick_id = $data['ticket_id'] ?? null;

if (!$email || !$tick_id) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

// Generar PDF del ticket
$pdf_url = "https://transportestea.com//Pdf/ticket_online.php?ticket_id=" . $tick_id;

// Descargar PDF generado
$pdf_content = file_get_contents($pdf_url);
$temp_pdf = tempnam(sys_get_temp_dir(), 'ticket') . ".pdf";
file_put_contents($temp_pdf, $pdf_content);

// Configurar PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = $config['mail'];
    $mail->Password = $config['mail_password'];
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->setFrom("tea.contacto@digitalenigma.mx", "TEA Transportes");
    $mail->addAddress($email);
    $mail->Subject = "Tu boleto de viaje - TEA";

    // PLANTILLA HTML
    $html = "
    <div style='font-family: Poppins, Arial, sans-serif; background:#f4f4f7; padding:40px;'>
        <div style='max-width:600px; margin:0 auto; background:#ffffff; border-radius:15px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.15);'>
            <div style='background:linear-gradient(135deg, #0a4d0e, #0d1a1e); padding:30px; text-align:center; color:white;'>
                <h2 style='margin:0; font-weight:700;'>¡Tu Boleto Está Listo! 🎉</h2>
                <p style='margin-top:5px; font-size:14px; opacity:.9;'>Gracias por viajar con Transportes Ejidales de Ario</p>
            </div>

            <div style='padding:30px; line-height:1.6; color:#333;'>
                <p style='font-size:16px;'>Hola,</p>
                <p style='font-size:16px;'>Adjunto encontrarás tu boleto en formato PDF.</p>

                <div style='margin:25px 0; padding:20px; background:#f7f7f7; border-left:5px solid #0a4d0e; border-radius:8px;'>
                    <p style='margin:0; font-size:15px;'>
                        <strong>🚌 Transporte:</strong> TEA – Transportes Ejidales de Ario<br>
                        <strong>🎟 Número de boleto:</strong> #$tick_id<br>
                        <strong>📄 Archivo adjunto:</strong> boleto_$tick_id.pdf
                    </p>
                </div>

                <a href='https://transportestea.com' 
                    style='display:inline-block; margin-top:20px; padding:12px 25px; background:#0a4d0e; color:white; text-decoration:none; font-size:15px; font-weight:600; border-radius:8px;'>
                    Ir al portal de boletos
                </a>
            </div>

            <div style='background:#0d1a1e; padding:20px; text-align:center; color:white; font-size:13px;'>
                <p style='margin:0;'>Transportes Ejidales de Ario © " . date('Y') . "</p>
            </div>
        </div>
    </div>
    ";

    $mail->Body = $html;
    $mail->isHTML(true);

    // Adjuntar boleto
    $mail->addAttachment($temp_pdf, "boleto_" . $tick_id . ".pdf");

    $mail->send();
    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

unlink($temp_pdf);
