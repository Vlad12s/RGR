<?php
require __DIR__ . '/db.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $rows = $pdo->query("SELECT * FROM Commander ORDER BY commander_id")->fetchAll();
    echo "<a href='?entity=commander&action=create'>Create commander</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Name</th><th>Rank</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['commander_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>".htmlspecialchars($r['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>".htmlspecialchars($r['commander_rank'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</td>
            <td>
                <a href='?entity=commander&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=commander&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create' || $action === 'edit') {
    $edit = $action === 'edit';
    $commander = ['name'=>'','commander_rank'=>''];

    if ($edit) {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM Commander WHERE commander_id=:id");
        $stmt->execute(['id'=>$id]);
        $commander = $stmt->fetch();
        if (!$commander) { echo "Not found"; exit; }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $rank = trim($_POST['rank'] ?? '');
        $errors = [];

        if ($name === '') $errors[] = "Name required";
        if ($rank === '') $errors[] = "Rank required";

        if (!$errors) {
            if ($edit) {
                $stmt = $pdo->prepare("UPDATE Commander SET name=:name, commander_rank=:rank WHERE commander_id=:id");
                $stmt->execute(['name'=>$name,'rank'=>$rank,'id'=>$id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO Commander (name, commander_rank) VALUES (:name, :rank)");
                $stmt->execute(['name'=>$name,'rank'=>$rank]);
            }
            header("Location: ?entity=commander");
            exit;
        } else {
            foreach ($errors as $e) echo "<p style='color:red'>".htmlspecialchars($e)."</p>";
        }
    }

    require __DIR__ .'/../views/commander_form.php';
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Commander WHERE commander_id=:id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=commander");
    exit;
}
