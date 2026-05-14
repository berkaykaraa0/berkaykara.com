<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.');
}

$name    = sanitize($_POST['name'] ?? '');
$email   = sanitize($_POST['email'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

if (!$name || !$email || !$message) jsonResponse(false, 'Please fill in all required fields.');
if (!validateEmail($email))         jsonResponse(false, 'Invalid email address.');
if (strlen($message) < 10)          jsonResponse(false, 'Message is too short.');

$ok = saveContact(compact('name','email','subject','message'));
$ok ? jsonResponse(true, 'Message sent successfully!')
    : jsonResponse(false, 'Failed to save your message. Please try again.');
