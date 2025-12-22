<?php
require_once dirname(__DIR__) . "/Database/conexion.php";

function loadStripeSettings(): array
{
    $settings = [];
    $result = ejecutarConsulta("SELECT setting_key, setting_value FROM settings WHERE deleted_at IS NULL");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings;
}

$stored = loadStripeSettings();

return [
    "secret_key"            => $stored['stripe_secret_key'] ?? (getenv("STRIPE_SECRET") ?: ""),
    "publishable_key"       => $stored['stripe_publishable_key'] ?? (getenv("STRIPE_PUBLISHABLE") ?: ""),
    "connect_account_id"    => $stored['stripe_connect_account_id'] ?? (getenv("STRIPE_ACCOUNT") ?: ""),
    "platform_fee_percent"  => $stored['stripe_platform_fee_percent'] ?? 5,
    "platform_fee_fixed"    => $stored['stripe_platform_fee_fixed'] ?? 0,
    "success_url"           => $stored['stripe_success_url'] ?? "",
    "cancel_url"            => $stored['stripe_cancel_url'] ?? "",
    "webhook_secret"        => $stored['stripe_webhook_secret'] ?? (getenv("STRIPE_WEBHOOK_SECRET") ?: ""),
    "online_branch_office_id" => $stored['online_branch_office_id'] ?? null,
    "online_user_id"        => $stored['online_user_id'] ?? null
];
