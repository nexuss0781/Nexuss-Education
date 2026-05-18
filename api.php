<?php
session_start();

// Configuration
$booksDir = __DIR__ . '/books/';
$maxFileSize = 50 * 1024 * 1024; // 50MB
$allowedTypes = ['application/pdf'];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_upload'])) {
    header('Content-Type: application/json');
    
    $file = $_FILES['pdf_upload'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Upload error occurred']);
        exit;
    }
    
    if ($file['size'] > $maxFileSize) {
        echo json_encode(['success' => false, 'error' => 'File too large (max 50MB)']);
        exit;
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => 'Only PDF files are allowed']);
        exit;
    }
    
    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
    $targetPath = $booksDir . $safeName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode([
            'success' => true, 
            'filename' => $safeName,
            'path' => 'books/' . $safeName
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save file']);
    }
    exit;
}

// Get list of books
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'list') {
    header('Content-Type: application/json');
    
    $books = [];
    if (is_dir($booksDir)) {
        $files = scandir($booksDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                $filePath = $booksDir . $file;
                $books[] = [
                    'name' => $file,
                    'path' => 'books/' . $file,
                    'size' => filesize($filePath),
                    'modified' => date('Y-m-d H:i:s', filemtime($filePath))
                ];
            }
        }
    }
    
    echo json_encode(['success' => true, 'books' => $books]);
    exit;
}

// Delete book
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['file'])) {
    header('Content-Type: application/json');
    
    $file = basename($_GET['file']);
    $targetPath = $booksDir . $file;
    
    if (file_exists($targetPath) && unlink($targetPath)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File not found or could not be deleted']);
    }
    exit;
}
?>
