<?php
require __DIR__ .'/db.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $rows = $pdo->query("SELECT * FROM Product ORDER BY product_id")->fetchAll();
    echo "<a href='?entity=product&action=create'>Create product</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['product_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>".htmlspecialchars($r['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>{$r['price']}</td>
            <td>
                <a href='?entity=product&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=product&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create' || $action === 'edit') {
    $edit = $action === 'edit';
    $product = ['name'=>'','price'=>''];

    if ($edit) {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM Product WHERE product_id=:id");
        $stmt->execute(['id'=>$id]);
        $product = $stmt->fetch();
        if (!$product) { echo "Not found"; exit; }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $errors = [];

        if ($name === '') $errors[] = "Name required";
        if (!is_numeric($price) || $price < 0) $errors[] = "Price must be a positive number";

        if (!$errors) {
            if ($edit) {
                $stmt = $pdo->prepare("UPDATE Product SET name=:name, price=:price WHERE product_id=:id");
                $stmt->execute(['name'=>$name,'price'=>$price,'id'=>$id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO Product (name, price) VALUES (:name, :price)");
                $stmt->execute(['name'=>$name,'price'=>$price]);
            }
            header("Location: ?entity=product");
            exit;
        } else {
            foreach ($errors as $e) echo "<p style='color:red'>".htmlspecialchars($e)."</p>";
        }
    }

    require __DIR__ .'/../views/product_form.php';
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Product WHERE product_id=:id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=product");
    exit;
}
