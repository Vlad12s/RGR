<?php
require __DIR__ .'/db.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $rows = $pdo->query("SELECT o.*, p.name as product_name FROM Orders o LEFT JOIN Product p ON o.product_id=p.product_id ORDER BY order_id")->fetchAll();
    echo "<a href='?entity=orders&action=create'>Create order</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Customer</th><th>Address</th><th>Contract</th><th>Date</th><th>Product</th><th>Quantity</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['order_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>".htmlspecialchars($r['customer_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>".htmlspecialchars($r['customer_address'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>".htmlspecialchars($r['contract_number'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>{$r['contract_date']}</td>
            <td>".htmlspecialchars($r['product_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>{$r['planned_quantity']}</td>
            <td>
                <a href='?entity=orders&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=orders&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create' || $action === 'edit') {
    $edit = $action === 'edit';
    $order = ['customer_name'=>'','customer_address'=>'','contract_number'=>'','contract_date'=>'','product_id'=>'','planned_quantity'=>''];
    $products = $pdo->query("SELECT * FROM Product")->fetchAll();

    if ($edit) {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM Orders WHERE order_id=:id");
        $stmt->execute(['id'=>$id]);
        $order = $stmt->fetch();
        if (!$order) { echo "Not found"; exit; }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $customer_name = trim($_POST['customer_name'] ?? '');
        $customer_address = trim($_POST['customer_address'] ?? '');
        $contract_number = trim($_POST['contract_number'] ?? '');
        $contract_date = trim($_POST['contract_date'] ?? '');
        $product_id = $_POST['product_id'] ?? null;
        $planned_quantity = $_POST['planned_quantity'] ?? null;

        $errors = [];
        if ($customer_name === '') $errors[] = "Customer name required";
        if ($customer_address === '') $errors[] = "Customer address required";
        if ($contract_number === '') $errors[] = "Contract number required";
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $contract_date)) $errors[] = "Date format invalid (YYYY-MM-DD)";
        if (!is_numeric($product_id)) $errors[] = "Product invalid";
        if (!is_numeric($planned_quantity) || $planned_quantity < 0) $errors[] = "Quantity invalid";

        if (!$errors) {
            if ($edit) {
                $stmt = $pdo->prepare("UPDATE Orders SET customer_name=:cname, customer_address=:addr, contract_number=:cnum, contract_date=:cdate, product_id=:pid, planned_quantity=:qty WHERE order_id=:id");
                $stmt->execute(['cname'=>$customer_name,'addr'=>$customer_address,'cnum'=>$contract_number,'cdate'=>$contract_date,'pid'=>$product_id,'qty'=>$planned_quantity,'id'=>$id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO Orders (customer_name, customer_address, contract_number, contract_date, product_id, planned_quantity) VALUES (:cname, :addr, :cnum, :cdate, :pid, :qty)");
                $stmt->execute(['cname'=>$customer_name,'addr'=>$customer_address,'cnum'=>$contract_number,'cdate'=>$contract_date,'pid'=>$product_id,'qty'=>$planned_quantity]);
            }
            header("Location: ?entity=orders");
            exit;
        } else {
            foreach ($errors as $e) echo "<p style='color:red'>".htmlspecialchars($e)."</p>";
        }
    }

    require __DIR__ .'/../views/orders_form.php';
    exit;
}
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Orders WHERE order_id=:id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=orders");
    exit;
}
