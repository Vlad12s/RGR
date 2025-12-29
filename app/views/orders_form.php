<h1><?= $edit ? 'Edit order' : 'Create order' ?></h1>
<form method='post'>
  Customer: <input name='customer_name' value='<?= htmlspecialchars($order['customer_name'] ?? '') ?>' required><br><br>
  Address: <input name='customer_address' value='<?= htmlspecialchars($order['customer_address'] ?? '') ?>' required><br><br>
  Contract #: <input name='contract_number' value='<?= htmlspecialchars($order['contract_number'] ?? '') ?>' required><br><br>
  Date: <input type='date' name='contract_date' value='<?= htmlspecialchars($order['contract_date'] ?? '') ?>' required><br><br>
  Product:
  <select name='product_id' required>
    <option value=''>-- choose --</option>
    <?php foreach($products as $p): ?>
      <option value='<?= $p['product_id'] ?>' <?= (isset($order['product_id']) && $order['product_id']==$p['product_id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($p['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
      </option>
    <?php endforeach; ?>
  </select><br><br>
  Quantity: <input type='number' name='planned_quantity' min='0' value='<?= htmlspecialchars($order['planned_quantity'] ?? '') ?>' required><br><br>
  <button>Save</button>
</form>
