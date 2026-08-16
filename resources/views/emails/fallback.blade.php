<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi AutoRent</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #2563eb;">Pemberitahuan AutoRent</h2>
        
        <p>Berikut adalah pesan notifikasi untuk Anda:</p>
        
        <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #2563eb; margin: 20px 0;">
            {!! nl2br(e($messageText)) !!}
        </div>

        <p style="font-size: 12px; color: #777;">
            Email ini dikirim secara otomatis karena pengiriman pesan melalui WhatsApp sedang mengalami gangguan.<br>
            Harap jangan membalas email ini.
        </p>
    </div>
</body>
</html>
