<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Method not allowed.', 405);

$name    = sanitize($_POST['name']    ?? '');
$email   = sanitizeEmail($_POST['email'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');
$ip      = $_SERVER['REMOTE_ADDR'] ?? '';

if (!$name)    jsonError('Name is required.');
if (!$email)   jsonError('A valid email address is required.');
if (!$message) jsonError('Message cannot be empty.');
if (strlen($name) < 2)    jsonError('Name must be at least 2 characters.');
if (strlen($message) < 10) jsonError('Message must be at least 10 characters.');

// Simple rate limiting: max 3 messages per hour per IP
$db   = getDB();
$stmt = $db->prepare(
    "SELECT COUNT(*) FROM contacts WHERE ip_address = ? AND created_at > NOW() - INTERVAL 1 HOUR"
);
$stmt->execute([$ip]);
if ((int)$stmt->fetchColumn() >= 3) {
    jsonError('Too many messages. Please try again later.');
}

$stmt = $db->prepare(
    "INSERT INTO contacts (name, email, subject, message, ip_address) VALUES (?,?,?,?,?)"
);
$stmt->execute([$name, $email, $subject, $message, $ip]);

jsonSuccess([], 'Your message has been sent! I\'ll get back to you soon.');
