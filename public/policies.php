<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getPDO();

$sql = 'SELECT p.*, c.name AS client_name, c.phone
        FROM policies p
        JOIN clients c ON c.id = p.client_id
        ORDER BY p.expiration_date ASC';
$policies = $pdo->query($sql)->fetchAll();

$pageTitle = 'Policies';
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="h4 mb-3">Policies</h1>

<div class="table-responsive">
    <table class="table table-striped table-sm align-middle">
        <thead>
            <tr>
                <th>Client</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Expiration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($policies)): ?>
                <tr><td colspan="5" class="text-center text-muted">No policies found.</td></tr>
            <?php endif; ?>
            <?php foreach ($policies as $policy): ?>
                <tr class="<?= e(policyRowClass($policy['expiration_date'])) ?>">
                    <td><?= e($policy['client_name']) ?></td>
                    <td><?= e($policy['phone']) ?></td>
                    <td class="text-capitalize"><?= e($policy['insurance_type']) ?></td>
                    <td><?= e(formatDate($policy['expiration_date'])) ?></td>
                    <td class="d-flex gap-1">
                        <a class="btn btn-outline-secondary btn-sm" href="/policy_form.php?id=<?= (int) $policy['id'] ?>">Edit</a>
                        <a class="btn btn-outline-danger btn-sm" href="/policy_delete.php?id=<?= (int) $policy['id'] ?>" onclick="return confirm('Delete this policy?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
