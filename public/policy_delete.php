<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM policies WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

header('Location: /policies.php');
exit;
