<?php
$path = realpath('./uploads');
$files = array_diff(scandir($path), array('.', '..'));

if (!empty($files)) {
    $filePath = $path . '/' . end($files);
    if(file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        flush();
        readfile($filePath);
        exit;
    }
}
echo 'You haven`t loaded any file yet:)';