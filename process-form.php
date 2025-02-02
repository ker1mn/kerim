<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini al
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));

    // PHPMailer kullanarak e-posta gönder
    $mail = new PHPMailer(true);

    try {
        // Mail sunucusu ayarları
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP sunucusu
        $mail->SMTPAuth = true;
        $mail->Username = 'kerimkarakaya2019@gmail.com'; // Gmail adresiniz
        $mail->Password = 'xxxx xxxx xxxx xxxx'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SSL;
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Alıcı ve gönderici ayarları
        $mail->setFrom('your-email@gmail.com', 'Website Contact Form');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('your-email@gmail.com'); // Alıcı Gmail adresi

        // E-posta içeriği
        $mail->isHTML(true);
        $mail->Subject = "Yeni İletişim Formu Mesajı: $subject";
        $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #8B6B61;'>Yeni İletişim Formu Mesajı</h2>
                <div style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>
                    <p><strong>İsim:</strong> {$name}</p>
                    <p><strong>E-posta:</strong> {$email}</p>
                    <p><strong>Konu:</strong> {$subject}</p>
                    <p><strong>Mesaj:</strong><br>{$message}</p>
                </div>
                <p style='font-size: 12px; color: #666;'>Bu e-posta website iletişim formundan gönderilmiştir.</p>
            </body>
            </html>
        ";
        $mail->AltBody = "İsim: {$name}\nE-posta: {$email}\nKonu: {$subject}\nMesaj: {$message}";

        $mail->send();
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Mesajınız başarıyla gönderildi."]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "E-posta gönderilemedi: {$mail->ErrorInfo}"]);
    }
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Form gönderiminde bir sorun oluştu."]);
}
?> 