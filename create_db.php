<?php
try {
    $db = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $db->exec('CREATE DATABASE IF NOT EXISTS db_presensi_2;');
    echo 'Database created successfully';
} catch (PDOException $e) {
    echo $e->getMessage();
}
