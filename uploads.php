<?php
$path = realpath('./uploads');
$mostRecentFilePath = "";
$mostRecentFileMTime = 0;
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isFile()) {
        if ($fileinfo->getMTime() > $mostRecentFileMTime) {
            $mostRecentFileMTime = $fileinfo->getMTime();
            $mostRecentFilePath = $fileinfo->getPathname();
        }
    }
}
if ($mostRecentFilePath) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($mostRecentFilePath).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($mostRecentFilePath));
    flush();
    readfile($mostRecentFilePath);
    exit;
}
echo 'You haven`t loaded any file yet:)';