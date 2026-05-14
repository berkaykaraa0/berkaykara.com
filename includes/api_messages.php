<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
header('Content-Type: application/json');

$db     = getDB();
$action = $_POST['action'] ?? '';
$id     = (int)($_POST['id'] ?? 0);

if (!$id) jsonError('Invalid ID.');

switch ($action) {
    case 'mark_read':
        $db->prepare("UPDATE contacts SET is_read=1 WHERE id=?")->execute([$id]);
        jsonSuccess([], 'Marked as read.');
        break;
    case 'delete':
        $db->prepare("DELETE FROM contacts WHERE id=?")->execute([$id]);
        jsonSuccess([], 'Message deleted.');
        break;
    default:
        jsonError('Unknown action.');
}
