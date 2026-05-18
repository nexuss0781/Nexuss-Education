<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { 
    http_response_code(200);
    exit(0); 
}

$booksDir = __DIR__ . '/books/';
$categoriesDir = __DIR__ . '/categories/';

// Ensure directories exist
if (!file_exists($booksDir)) { mkdir($booksDir, 0755, true); }
if (!file_exists($categoriesDir)) { mkdir($categoriesDir, 0755, true); }

// Get action from query string or POST body
$action = $_GET['action'] ?? '';
if (empty($action) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Try to get action from POST data
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'multipart/form-data') !== false) {
        $action = $_POST['action'] ?? '';
    } elseif (strpos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
    }
}

// Handle category management
if ($action === 'categories') {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        // List all categories
        $files = array_diff(scandir($categoriesDir), ['.', '..']);
        $categoryList = [];
        foreach ($files as $file) {
            $path = $categoriesDir . $file;
            if (is_dir($path)) {
                $categoryId = pathinfo($file, PATHINFO_FILENAME);
                $metaFile = $path . '/meta.json';
                $name = $categoryId;
                $pdfs = [];
                
                if (file_exists($metaFile)) {
                    $meta = json_decode(file_get_contents($metaFile), true);
                    $name = $meta['name'] ?? $categoryId;
                    $pdfs = $meta['pdfs'] ?? [];
                } else {
                    // Legacy: read pdfs from directory
                    $pdfFiles = array_diff(scandir($path), ['.', '..']);
                    $pdfs = array_map(fn($f) => ['name' => $f, 'path' => "categories/$categoryId/$f"], $pdfFiles);
                }
                
                $categoryList[] = ['id' => $categoryId, 'name' => $name, 'pdfs' => $pdfs];
            }
        }
        
        // Add default category if no categories exist
        if (empty($categoryList)) {
            $defaultMeta = ['name' => 'My Books', 'pdfs' => []];
            file_put_contents($categoriesDir . 'default/meta.json', json_encode($defaultMeta));
            $categoryList = [['id' => 'default', 'name' => 'My Books', 'pdfs' => []]];
        }
        
        echo json_encode(['success' => true, 'categories' => $categoryList]);
        exit;
    }
    
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $type = $input['type'] ?? '';
        
        // Create category
        if ($type === 'create') {
            $name = trim($input['name'] ?? '');
            if (empty($name)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Category name is required']);
                exit;
            }
            
            $id = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
            $catDir = $categoriesDir . $id;
            
            if (file_exists($catDir)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Category already exists']);
                exit;
            }
            
            mkdir($catDir, 0755, true);
            file_put_contents($catDir . '/meta.json', json_encode(['name' => $name, 'pdfs' => []]));
            
            echo json_encode(['success' => true, 'id' => $id, 'name' => $name]);
            exit;
        }
        
        // Rename category
        if ($type === 'rename') {
            $id = $input['id'] ?? '';
            $newName = trim($input['name'] ?? '');
            
            if (empty($id) || empty($newName)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
                exit;
            }
            
            $catDir = $categoriesDir . $id;
            if (!file_exists($catDir)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Category not found']);
                exit;
            }
            
            $metaFile = $catDir . '/meta.json';
            $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : ['name' => $id, 'pdfs' => []];
            $meta['name'] = $newName;
            file_put_contents($metaFile, json_encode($meta));
            
            echo json_encode(['success' => true]);
            exit;
        }
        
        // Delete category
        if ($type === 'delete') {
            $id = $input['id'] ?? '';
            
            if (empty($id)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Category ID is required']);
                exit;
            }
            
            $catDir = $categoriesDir . $id;
            if (!file_exists($catDir)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Category not found']);
                exit;
            }
            
            // Remove directory recursively
            $iterator = new RecursiveDirectoryIterator($catDir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }
            rmdir($catDir);
            
            echo json_encode(['success' => true]);
            exit;
        }
        
        // Update category PDFs
        if ($type === 'update') {
            $id = $input['id'] ?? '';
            $pdfs = $input['pdfs'] ?? [];
            
            if (empty($id)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Category ID is required']);
                exit;
            }
            
            $catDir = $categoriesDir . $id;
            if (!file_exists($catDir)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Category not found']);
                exit;
            }
            
            $metaFile = $catDir . '/meta.json';
            $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : ['name' => $id, 'pdfs' => []];
            $meta['pdfs'] = $pdfs;
            file_put_contents($metaFile, json_encode($meta));
            
            echo json_encode(['success' => true]);
            exit;
        }
    }
    
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid category operation']);
    exit;
}

// Handle PDF upload
if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error: ' . ($_FILES['pdf']['error'] ?? 'unknown')]);
        exit;
    }
    
    $file = $_FILES['pdf'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Only PDF files are allowed']);
        exit;
    }
    
    // Get category from POST
    $category = $_POST['category'] ?? 'default';
    $safeCategory = preg_replace('/[^a-zA-Z0-9_-]/', '', $category);
    
    // Determine target directory
    $targetDir = $categoriesDir . $safeCategory . '/';
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
    $targetPath = $targetDir . $safeName;
    $relativePath = "categories/$safeCategory/$safeName";
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        chmod($targetPath, 0644);
        
        // Update category metadata
        $metaFile = $targetDir . 'meta.json';
        $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : ['name' => $safeCategory, 'pdfs' => []];
        $meta['pdfs'][] = ['name' => $safeName, 'path' => $relativePath];
        file_put_contents($metaFile, json_encode($meta));
        
        // Set aggressive caching headers for the uploaded file
        header('Cache-Control: public, max-age=31536000, immutable');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        echo json_encode(['success' => true, 'filename' => $safeName, 'path' => $relativePath]);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to save file. Check permissions.']);
        exit;
    }
}

// Handle PDF list for a category
if ($action === 'list') {
    $category = $_GET['category'] ?? 'default';
    $safeCategory = preg_replace('/[^a-zA-Z0-9_-]/', '', $category);
    $targetDir = $categoriesDir . $safeCategory . '/';
    
    if (!file_exists($targetDir)) {
        echo json_encode(['success' => true, 'files' => []]);
        exit;
    }
    
    $metaFile = $targetDir . 'meta.json';
    if (file_exists($metaFile)) {
        $meta = json_decode(file_get_contents($metaFile), true);
        echo json_encode(['success' => true, 'files' => $meta['pdfs'] ?? []]);
    } else {
        $files = array_diff(scandir($targetDir), ['.', '..']);
        $pdfs = array_map(fn($f) => ['name' => $f, 'path' => "categories/$safeCategory/$f"], 
                         array_filter($files, fn($f) => pathinfo($f, PATHINFO_EXTENSION) === 'pdf'));
        echo json_encode(['success' => true, 'files' => $pdfs]);
    }
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Invalid action: ' . ($action ?: 'none provided')]);
?>
