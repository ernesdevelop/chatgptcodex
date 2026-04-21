<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getPDO();

function fetchPoliciesByCondition(PDO $pdo, string $whereClause, array $params = []): array
{
    $sql = "SELECT p.id, p.insurance_type, p.expiration_date, c.name AS client_name, c.phone
            FROM policies p
            JOIN clients c ON c.id = p.client_id
            WHERE {$whereClause}
            ORDER BY p.expiration_date ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

$todayPolicies = fetchPoliciesByCondition($pdo, 'p.expiration_date = CURDATE()');
$weekPolicies = fetchPoliciesByCondition($pdo, 'p.expiration_date > CURDATE() AND p.expiration_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)');
$expiredPolicies = fetchPoliciesByCondition($pdo, 'p.expiration_date < CURDATE()');

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';

function renderPolicyTable(array $policies): void
{
    if (empty($policies)) {
        echo '<div class="alert alert-secondary py-2 mb-0">No records found.</div>';
        return;
    }

    echo '<div class="table-responsive"><table class="table table-sm align-middle">';
    echo '<thead><tr><th>Client</th><th>Phone</th><th>Type</th><th>Expiration</th><th>Actions</th></tr></thead><tbody>';

    foreach ($policies as $policy) {
        $rowClass = policyRowClass($policy['expiration_date']);
        $waUrl = buildWhatsAppUrl($policy['phone'], $policy['client_name'], $policy['expiration_date']);
        echo "<tr class=\"{$rowClass}\">";
        echo '<td>' . e($policy['client_name']) . '</td>';
        echo '<td>' . e($policy['phone']) . '</td>';
        echo '<td class="text-capitalize">' . e($policy['insurance_type']) . '</td>';
        echo '<td>' . e(formatDate($policy['expiration_date'])) . '</td>';
        echo '<td><a class="btn btn-success btn-sm" href="' . e($waUrl) . '" target="_blank" rel="noopener">WhatsApp</a></td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}
?>

<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Expiring Today</h5>
                <?php renderPolicyTable($todayPolicies); ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Expiring in 7 Days</h5>
                <?php renderPolicyTable($weekPolicies); ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Already Expired</h5>
                <?php renderPolicyTable($expiredPolicies); ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
