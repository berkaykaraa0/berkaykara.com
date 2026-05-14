<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$category = isset($_GET['category']) ? sanitize($_GET['category']) : 'all';
$projects = getProjects($category, true);
echo json_encode(['success' => true, 'projects' => $projects]);
