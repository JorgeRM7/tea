<?php
require_once "../Models/Setting.php";
$Setting = new Setting();

switch ($_GET["op"]) {
    case 'get-online-config':
        $settings = $Setting->all();
        $keys = [
            'online_branch_office_id',
            'online_user_id',
            'stripe_secret_key',
            'stripe_publishable_key',
            'stripe_connect_account_id',
            'stripe_platform_fee_percent',
            'stripe_platform_fee_fixed',
            'stripe_success_url',
            'stripe_cancel_url',
            'stripe_webhook_secret'
        ];
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $settings[$key] ?? '';
        }
        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
    break;

    case 'save-online-config':
        $payload = [
            'online_branch_office_id'   => $_POST['online_branch_office_id'] ?? '',
            'online_user_id'            => $_POST['online_user_id'] ?? '',
            'stripe_secret_key'         => $_POST['stripe_secret_key'] ?? '',
            'stripe_publishable_key'    => $_POST['stripe_publishable_key'] ?? '',
            'stripe_connect_account_id' => $_POST['stripe_connect_account_id'] ?? '',
            'stripe_platform_fee_percent' => $_POST['stripe_platform_fee_percent'] ?? '',
            'stripe_platform_fee_fixed' => $_POST['stripe_platform_fee_fixed'] ?? '',
            'stripe_success_url'        => $_POST['stripe_success_url'] ?? '',
            'stripe_cancel_url'         => $_POST['stripe_cancel_url'] ?? '',
            'stripe_webhook_secret'     => $_POST['stripe_webhook_secret'] ?? ''
        ];
        $Setting->saveMany($payload);
        echo json_encode(["success" => true]);
    break;
}
