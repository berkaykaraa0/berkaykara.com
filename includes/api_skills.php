<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

$db       = getDB();
$action   = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $stmt = $db->query("SELECT * FROM skills WHERE is_active=1 ORDER BY category, display_order ASC");
        jsonSuccess($stmt->fetchAll());
        break;

    case 'create':
        requireLogin();
        $name  = sanitize($_POST['name'] ?? '');
        $cat   = sanitize($_POST['category'] ?? 'frontend');
        $prof  = (int)($_POST['proficiency'] ?? 70);
        $icon  = sanitize($_POST['icon'] ?? '');
        $order = (int)($_POST['display_order'] ?? 0);
        if (!$name) jsonError('Skill name is required.');
        $stmt = $db->prepare("INSERT INTO skills (name,category,proficiency,icon,display_order) VALUES (?,?,?,?,?)");
        $stmt->execute([$name,$cat,$prof,$icon,$order]);
        jsonSuccess(['id' => $db->lastInsertId()], 'Skill added.');
        break;

    case 'delete':
        requireLogin();
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) jsonError('Invalid ID.');
        $db->prepare("DELETE FROM skills WHERE id=?")->execute([$id]);
        jsonSuccess([], 'Skill deleted.');
        break;

    default:
        jsonError('Unknown action.', 404);
}
