<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getPDO();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$policy = ['client_id' => '', 'insurance_type' => 'auto', 'expiration_date' => ''];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM policies WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $policy = $found;
    }
}

$clients = $pdo->query('SELECT id, name, phone FROM clients ORDER BY name ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = (int) ($_POST['client_id'] ?? 0);
    $insuranceType = $_POST['insurance_type'] ?? 'auto';
    $expirationDate = $_POST['expiration_date'] ?? '';

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE policies SET client_id = :client_id, insurance_type = :insurance_type, expiration_date = :expiration_date WHERE id = :id');
        $stmt->execute([
            'client_id' => $clientId,
            'insurance_type' => $insuranceType,
            'expiration_date' => $expirationDate,
            'id' => $id,
        ]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO policies (client_id, insurance_type, expiration_date) VALUES (:client_id, :insurance_type, :expiration_date)');
        $stmt->execute([
            'client_id' => $clientId,
            'insurance_type' => $insuranceType,
            'expiration_date' => $expirationDate,
        ]);
    }

    header('Location: /policies.php');
    exit;
}

$pageTitle = $id > 0 ? 'Edit Policy' : 'Add Policy';
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="h4 mb-3"><?= e($pageTitle) ?></h1>
<form method="post" class="card card-body shadow-sm">
    <div class="mb-3">
        <label class="form-label">Client</label>
        <select name="client_id" class="form-select" required>
            <option value="">Select a client</option>
            <?php foreach ($clients as $client): ?>
                <option value="<?= (int) $client['id'] ?>" <?= (string) $client['id'] === (string) $policy['client_id'] ? 'selected' : '' ?>>
                    <?= e($client['name'] . ' - ' . $client['phone']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Insurance Type</label>
        <select name="insurance_type" class="form-select" required>
            <?php foreach (['auto', 'life', 'home'] as $type): ?>
                <option value="<?= e($type) ?>" <?= $policy['insurance_type'] === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Expiration Date</label>
        <input type="date" name="expiration_date" class="form-control" required value="<?= e($policy['expiration_date']) ?>">
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Save</button>
        <a class="btn btn-outline-secondary" href="/policies.php">Cancel</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
