<?php
require __DIR__ .'/db.php';

echo "<h1>Розрахункова-графічна робота</h1>";
echo "<p>Інформаційна система управління замовленнями</p>";
echo "<ul>
<li><a href='?entity=product'>Product</a></li>
<li><a href='?entity=commander'>Commander</a></li>
<li><a href='?entity=unit'>Unit</a></li>
<li><a href='?entity=orders'>Orders</a></li>
</ul>";

$allowed = ['product','commander','unit','orders'];
$entity = $_GET['entity'] ?? null;

if ($entity) {
    if (!in_array($entity, $allowed)) {
        echo "<p>Невідома сутність.</p>";
        exit;
    }
    $file = __DIR__ . '/crud_' . $entity . '.php';
    if (file_exists($file)) {
        require $file;
    } else {
        echo "<p>Функціонал для {$entity} відсутній.</p>";
    }
}
