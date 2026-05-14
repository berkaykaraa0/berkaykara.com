<?php
require_once __DIR__ . '/../includes/functions.php';
startSession();
if (!isLoggedIn()) { jsonResponse(false, 'Unauthorized.'); }
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        jsonResponse(true, '', ['projects' => getProjects(null, false)]);

    case 'get':
        $id = (int)($_GET['id'] ?? 0);
        $p  = getProjectById($id);
        $p ? jsonResponse(true, '', ['project' => $p]) : jsonResponse(false, 'Not found.');

    case 'create':
        $data = [
            'title'            => sanitize($_POST['title'] ?? ''),
            'description'      => sanitize($_POST['description'] ?? ''),
            'long_description' => sanitize($_POST['long_description'] ?? ''),
            'technologies'     => sanitize($_POST['technologies'] ?? ''),
            'category'         => sanitize($_POST['category'] ?? 'web'),
            'github_url'       => sanitize($_POST['github_url'] ?? ''),
            'live_url'         => sanitize($_POST['live_url'] ?? ''),
            'featured'         => isset($_POST['featured']) ? 1 : 0,
            'sort_order'       => (int)($_POST['sort_order'] ?? 0),
            'status'           => sanitize($_POST['status'] ?? 'active'),
        ];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $data['image'] = handleImageUpload($_FILES['image']);
        } else { $data['image'] = 'default.jpg'; }
        if (!$data['title'] || !$data['description']) jsonResponse(false, 'Title and description required.');
        $id = createProject($data);
        jsonResponse(true, 'Project created.', ['id' => $id]);

    case 'update':
        $id   = (int)($_POST['id'] ?? 0);
        $orig = getProjectById($id);
        if (!$orig) jsonResponse(false, 'Project not found.');
        $data = [
            'title'            => sanitize($_POST['title'] ?? ''),
            'description'      => sanitize($_POST['description'] ?? ''),
            'long_description' => sanitize($_POST['long_description'] ?? ''),
            'technologies'     => sanitize($_POST['technologies'] ?? ''),
            'category'         => sanitize($_POST['category'] ?? 'web'),
            'github_url'       => sanitize($_POST['github_url'] ?? ''),
            'live_url'         => sanitize($_POST['live_url'] ?? ''),
            'featured'         => isset($_POST['featured']) ? 1 : 0,
            'sort_order'       => (int)($_POST['sort_order'] ?? 0),
            'status'           => sanitize($_POST['status'] ?? 'active'),
            'image'            => $orig['image'],
        ];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $data['image'] = handleImageUpload($_FILES['image']);
        }
        updateProject($id, $data);
        jsonResponse(true, 'Project updated.');

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        deleteProject($id) ? jsonResponse(true, 'Project deleted.') : jsonResponse(false, 'Delete failed.');

    default:
        jsonResponse(false, 'Unknown action.');
}
