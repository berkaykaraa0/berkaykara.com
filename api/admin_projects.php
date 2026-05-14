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

if ($action === 'delete') {
    $id = (int)($input['id'] ?? 0);
    if ($id > 0) {
        $success = deleteProject($id);
        if ($success) {
            jsonResponse(true, 'Project deleted');
        } else {
            jsonResponse(false, 'Failed to delete project');
        }
    }
}

jsonResponse(false, 'Invalid action');
