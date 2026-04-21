<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getPDO();
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE name LIKE :q ORDER BY created_at DESC');
    $stmt->execute(['q' => '%' . $q . '%']);
} else {
    $stmt = $pdo->query('SELECT * FROM clients ORDER BY created_at DESC');
}

$clients = $stmt->fetchAll();

$pageTitle = 'Clients';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
    <h1 class="h4 mb-0">Clients</h1>
    <form class="d-flex" method="get">
        <input name="q" value="<?= e($q) ?>" class="form-control form-control-sm me-2" placeholder="Search by name">
        <button class="btn btn-primary btn-sm">Search</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clients)): ?>
                <tr><td colspan="4" class="text-center text-muted">No clients found.</td></tr>
            <?php endif; ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= e($client['name']) ?></td>
                    <td><?= e($client['phone']) ?></td>
                    <td><?= e((string) $client['notes']) ?></td>
                    <td class="d-flex gap-1">
                        <a class="btn btn-outline-secondary btn-sm" href="/client_form.php?id=<?= (int) $client['id'] ?>">Edit</a>
                        <a class="btn btn-outline-danger btn-sm" href="/client_delete.php?id=<?= (int) $client['id'] ?>" onclick="return confirm('Delete this client?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
