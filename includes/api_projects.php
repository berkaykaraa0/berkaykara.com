<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

$db       = getDB();
$action   = $_GET['action'] ?? 'list';
$category = $_GET['category'] ?? 'all';

switch ($action) {
    // ── Public: list projects ─────────────────────────────────
    case 'list':
        $sql    = "SELECT * FROM projects WHERE is_active = 1";
        $params = [];
        if ($category !== 'all') {
            $sql   .= " AND category = ?";
            $params[] = $category;
        }
        $sql .= " ORDER BY is_featured DESC, display_order ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $projects = $stmt->fetchAll();
        jsonSuccess($projects);
        break;

    // ── Admin: get single project ─────────────────────────────
    case 'get':
        requireLogin();
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        if (!$project) jsonError('Project not found', 404);
        jsonSuccess($project);
        break;

    // ── Admin: create project ─────────────────────────────────
    case 'create':
        requireLogin();
        $title       = sanitize($_POST['title']       ?? '');
        $desc        = sanitize($_POST['description']  ?? '');
        $short       = sanitize($_POST['short_description'] ?? '');
        $tech        = sanitize($_POST['technologies'] ?? '');
        $cat         = sanitize($_POST['category']     ?? 'web');
        $github      = sanitize($_POST['github_url']   ?? '');
        $live        = sanitize($_POST['live_url']     ?? '');
        $featured    = isset($_POST['is_featured']) ? 1 : 0;
        $order       = (int)($_POST['display_order']  ?? 0);

        if (!$title || !$desc) jsonError('Title and description are required.');

        $imagePath = '';
        if (!empty($_FILES['image']['name'])) {
            $up = uploadImage($_FILES['image'], 'project');
            if (!$up) jsonError('Invalid image file.');
            $imagePath = $up;
        }

        $stmt = $db->prepare("INSERT INTO projects
            (title, description, short_description, technologies, category, image_path, github_url, live_url, is_featured, display_order)
            VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title,$desc,$short,$tech,$cat,$imagePath,$github,$live,$featured,$order]);
        jsonSuccess(['id' => $db->lastInsertId()], 'Project created successfully.');
        break;

    // ── Admin: update project ─────────────────────────────────
    case 'update':
        requireLogin();
        $id       = (int)($_POST['id'] ?? 0);
        $title    = sanitize($_POST['title']       ?? '');
        $desc     = sanitize($_POST['description']  ?? '');
        $short    = sanitize($_POST['short_description'] ?? '');
        $tech     = sanitize($_POST['technologies'] ?? '');
        $cat      = sanitize($_POST['category']     ?? 'web');
        $github   = sanitize($_POST['github_url']   ?? '');
        $live     = sanitize($_POST['live_url']     ?? '');
        $featured = isset($_POST['is_featured']) ? 1 : 0;
        $order    = (int)($_POST['display_order']  ?? 0);

        if (!$id || !$title || !$desc) jsonError('Missing required fields.');

        // Check existing image
        $existing = $db->prepare("SELECT image_path FROM projects WHERE id = ?");
        $existing->execute([$id]);
        $row       = $existing->fetch();
        $imagePath = $row['image_path'] ?? '';

        if (!empty($_FILES['image']['name'])) {
            $up = uploadImage($_FILES['image'], 'project');
            if (!$up) jsonError('Invalid image file.');
            $imagePath = $up;
        }

        $stmt = $db->prepare("UPDATE projects SET
            title=?, description=?, short_description=?, technologies=?, category=?,
            image_path=?, github_url=?, live_url=?, is_featured=?, display_order=?
            WHERE id=?");
        $stmt->execute([$title,$desc,$short,$tech,$cat,$imagePath,$github,$live,$featured,$order,$id]);
        jsonSuccess([], 'Project updated successfully.');
        break;

    // ── Admin: delete project ─────────────────────────────────
    case 'delete':
        requireLogin();
        $id   = (int)($_POST['id'] ?? 0);
        if (!$id) jsonError('Invalid project ID.');
        $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        jsonSuccess([], 'Project deleted.');
        break;

    default:
        jsonError('Unknown action.', 404);
}
