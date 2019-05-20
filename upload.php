<?php
$uploads = realpath('./uploads');
$filename = $_SERVER['HTTP_X_FILE_NAME'];
$current = intval($_SERVER['HTTP_X_CURRENT']);
$total = intval($_SERVER['HTTP_X_TOTAL']);

$path = $uploads . '/' . $filename . '-' . $current . '-' . $total;

try {
    $data = fopen("php://input", "r");
    file_put_contents($path, $data);
    $data = file_get_contents($path);

    if ($current > 1) {
        $path_old = $uploads . '/' . $filename . '-' . ($current - 1) . '-' . $total;
        file_put_contents($path_old, $data, FILE_APPEND);
        rename($path_old, $path);
    }
    if ($current === $total) {
        rename($path, $uploads . '/' . $filename);
    }
} catch (Exception $exception) {
    http_response_code($exception->getCode());
    echo $exception->getMessage();
    exit;
}
http_response_code(204);