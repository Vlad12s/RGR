<?php
require __DIR__ . '/db.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $rows = $pdo->query("SELECT u.*, c.name as commander_name FROM Unit u LEFT JOIN Commander c ON u.commander_id=c.commander_id ORDER BY unit_id")->fetchAll();
    echo "<a href='?entity=unit&action=create'>Create unit</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Name</th><th>Type</th><th>Commander</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['unit_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>".htmlspecialchars($r['unit_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>".htmlspecialchars($r['type'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>".htmlspecialchars($r['commander_name'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>
                <a href='?entity=unit&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=unit&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create' || $action === 'edit') {
    $edit = $action === 'edit';
    $unit = ['unit_name'=>'','type'=>'','commander_id'=>''];
    $cmds = $pdo->query("SELECT * FROM Commander")->fetchAll();

    if ($edit) {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM Unit WHERE unit_id=:id");
        $stmt->execute(['id'=>$id]);
        $unit = $stmt->fetch();
        if (!$unit) { echo "Not found"; exit; }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $commander_id = $_POST['commander_id'] ?: null;
        $errors = [];

        if ($name === '') $errors[] = "Name required";
        if ($type === '') $errors[] = "Type required";
        if ($commander_id !== null && !is_numeric($commander_id)) $errors[] = "Commander ID invalid";

        if (!$errors) {
            if ($edit) {
                $stmt = $pdo->prepare("UPDATE Unit SET unit_name=:name, type=:type, commander_id=:commander_id WHERE unit_id=:id");
                $stmt->execute(['name'=>$name,'type'=>$type,'commander_id'=>$commander_id,'id'=>$id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO Unit (unit_name, type, commander_id) VALUES (:name, :type, :commander_id)");
                $stmt->execute(['name'=>$name,'type'=>$type,'commander_id'=>$commander_id]);
            }
            header("Location: ?entity=unit");
            exit;
        } else {
            foreach ($errors as $e) echo "<p style='color:red'>".htmlspecialchars($e)."</p>";
        }
    }

    require __DIR__ .'/../views/unit_form.php';
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Unit WHERE unit_id=:id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=unit");
    exit;
}
