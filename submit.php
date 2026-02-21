<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
$cfg = require __DIR__ . '/../../apps/config.php';

require_once __DIR__ . '/../../apps/build_mail.php';
require_once __DIR__ . '/../../apps/reply_mail_build.php';
require_once __DIR__ . '/../../apps/smtp_mailer.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method Not Allowed');
    }

    $mailData = build_mail_from_request($cfg);
    send_mail_smtp($cfg, $mailData);

    $replyData = build_mail_reply($mailData);
    send_mail_smtp($cfg, $replyData);

    header('Location: /success.html', true, 303);
    exit;
} catch (Throwable $e) {
    $id = bin2hex(random_bytes(6));
    error_log("[upload:$id] " . $e->getMessage());
    error_log("[upload:$id] " . $e->getFile() . ":" . $e->getLine());
    error_log("[upload:$id] FILES=" . json_encode($_FILES, JSON_UNESCAPED_UNICODE));
    error_log("[upload:$id] POST keys=" . implode(',', array_keys($_POST)));

    header('Location: /mistake.html', true, 303);
}
