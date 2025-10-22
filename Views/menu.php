<?php
require_once dirname(__DIR__) . "/Database/conexion.php";

$user_type_id = $_SESSION['user_type_id'] ?? 3;

$sql = "
    SELECT 
        views.title,
        views.route,
        views.module,
        views.icon,
        permissions.permission_create,
        permissions.permission_view,
        permissions.permission_update,
        permissions.permission_delete
    FROM permissions
    INNER JOIN views ON views.id = permissions.view_id
    INNER JOIN users_types ON users_types.id = permissions.user_type_id
    WHERE permissions.user_type_id = '$user_type_id' AND views.deleted_at is null AND permissions.permission_view = 1
    ORDER BY views.module, views.title
";
$result = ejecutarConsulta($sql);

$menu_items = [];
while ($row = $result->fetch_assoc()) {
    $menu_items[$row['module']][] = $row;
}
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <center>
                <img src="../assets/img/logo.png" alt="TEA" width="50" height="50" style="padding:2px;">
            </center>
            <span class="app-brand-text demo menu-text fw-bold">TEA</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
       
        <li class="menu-item">
            <a href="inicio.php" class="menu-link">
                <i class="menu-icon tf-icons ti ti-home"></i>
                <div data-i18n="INCIO">INICIO </div>
            </a>
        </li>

        <?php foreach ($menu_items as $module => $views): ?>
            <li class="menu-item">
                <a href="<?= htmlspecialchars($module) ?>" class="menu-link menu-toggle">
                    <div><?= htmlspecialchars($module) ?></div>
                </a>
                <ul class="menu-sub">
                    <?php foreach ($views as $view): ?>
                        <li class="menu-item">
                            <a href="<?= htmlspecialchars($view['route']) ?>" class="menu-link">
                                <!--  -->
                                <div><?= htmlspecialchars($view['title']) ?></div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>

