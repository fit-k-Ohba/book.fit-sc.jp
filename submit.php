<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
$cfg = require __DIR__ . '/../../app/config.php';

require_once __DIR__ . '/../../app/build_mail.php';
require_once __DIR__ . '/../../app/smtp_mailer.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method Not Allowed');
    }

    $mailData = build_mail_from_request($cfg);
    send_mail_smtp($cfg, $mailData);

    header('Location: /success.html', true, 303);
    exit;
} catch (Throwable $e) {
    header('Location: /mistake.html', true, 303);
}
