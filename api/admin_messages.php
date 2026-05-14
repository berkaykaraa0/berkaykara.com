<?php
require_once __DIR__ . '/../includes/functions.php';
if (!isLoggedIn()) {
    jsonResponse(false, 'Unauthorized');
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    jsonResponse(false, 'Invalid input');
}

$action = $input['action'] ?? '';

if ($action === 'mark_read') {
    $id = (int)($input['id'] ?? 0);
    if ($id > 0) {
        $success = markContactRead($id);
        if ($success) {
            jsonResponse(true, 'Message marked as read');
        } else {
            jsonResponse(false, 'Failed to update message');
        }
    }
}

if ($action === 'delete') {
    $id = (int)($input['id'] ?? 0);
    if ($id > 0) {
        $success = deleteContact($id);
        if ($success) {
            jsonResponse(true, 'Message deleted');
        } else {
            jsonResponse(false, 'Failed to delete message');
        }
    }
}

jsonResponse(false, 'Invalid action');
