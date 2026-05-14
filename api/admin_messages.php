<?php
require_once __DIR__ . '/../includes/functions.php';
startSession();
if (!isLoggedIn()) { jsonResponse(false, 'Unauthorized.'); }
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        jsonResponse(true, '', ['messages' => getContacts()]);
    case 'read':
        $id = (int)($_POST['id'] ?? 0);
        markContactRead($id);
        jsonResponse(true, 'Marked as read.');
    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        deleteContact($id) ? jsonResponse(true, 'Deleted.') : jsonResponse(false, 'Failed.');
    default:
        jsonResponse(false, 'Unknown action.');
}
