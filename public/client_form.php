<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getPDO();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$client = ['name' => '', 'phone' => '', 'notes' => ''];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $client = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE clients SET name = :name, phone = :phone, notes = :notes WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'notes' => $notes !== '' ? $notes : null,
            'id' => $id,
        ]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO clients (name, phone, notes) VALUES (:name, :phone, :notes)');
        $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'notes' => $notes !== '' ? $notes : null,
        ]);
    }

    header('Location: /clients.php');
    exit;
}

$pageTitle = $id > 0 ? 'Edit Client' : 'Add Client';
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="h4 mb-3"><?= e($pageTitle) ?></h1>
<form method="post" class="card card-body shadow-sm">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required maxlength="120" value="<?= e($client['name']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" required maxlength="30" value="<?= e($client['phone']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3"><?= e((string) $client['notes']) ?></textarea>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Save</button>
        <a class="btn btn-outline-secondary" href="/clients.php">Cancel</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
