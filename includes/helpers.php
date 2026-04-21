<?php

declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatDate(string $date): string
{
    $timestamp = strtotime($date);
    return $timestamp ? date('d/m/Y', $timestamp) : $date;
}

function sanitizePhone(string $phone): string
{
    $cleaned = preg_replace('/[^\d+]/', '', $phone) ?? '';

    if (str_starts_with($cleaned, '+')) {
        return '+' . preg_replace('/\D/', '', substr($cleaned, 1));
    }

    return preg_replace('/\D/', '', $cleaned) ?? '';
}

function buildWhatsAppUrl(string $phone, string $name, string $expirationDate): string
{
    $sanitized = sanitizePhone($phone);
    $sanitized = ltrim($sanitized, '+');

    $message = sprintf(
        'Hola %s, tu póliza vence el %s. ¿Querés renovarla?',
        $name,
        formatDate($expirationDate)
    );

    return sprintf('https://wa.me/%s?text=%s', $sanitized, urlencode($message));
}

function policyRowClass(string $expirationDate): string
{
    $today = date('Y-m-d');
    return $expirationDate <= $today ? 'table-danger' : '';
}
