<?php

declare(strict_types=1);

if (!isset($pageTitle)) {
    $pageTitle = 'Insurance CRM';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <div class="container">
        <a class="navbar-brand" href="/index.php">Insurance CRM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navMain" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/clients.php">Clients</a></li>
                <li class="nav-item"><a class="nav-link" href="/policies.php">Policies</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="/client_form.php" class="btn btn-sm btn-outline-light">Add Client</a>
                <a href="/policy_form.php" class="btn btn-sm btn-warning">Add Policy</a>
            </div>
        </div>
    </div>
</nav>
<div class="container pb-4">
