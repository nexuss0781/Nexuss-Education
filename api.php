<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

$booksDir = __DIR__ . '/books/';
if (!file_exists($booksDir)) { mkdir($booksDir, 0755, true); }

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'list') {
    $files = array_diff(scandir($booksDir), ['.', '..']);
    $pdfs = array_values(array_filter($files, fn($f) => pathinfo($f, PATHINFO_EXTENSION) === 'pdf'));
    echo json_encode(['success' => true, 'files' => $pdfs]);
    exit;
}

if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
        exit;
    }
    
    $file = $_FILES['pdf'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Only PDF files are allowed']);
        exit;
    }
    
    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
    $targetPath = $booksDir . $safeName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        chmod($targetPath, 0644);
        echo json_encode(['success' => true, 'filename' => $safeName]);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to save file']);
        exit;
    }
}

echo json_encode(['error' => 'Invalid action']);
?>
