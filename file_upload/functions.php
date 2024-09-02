<?php
require 'config.php';

function getUploadedFiles()
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT file_name, file_type, file_path FROM uploads");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$files = getUploadedFiles();

?>