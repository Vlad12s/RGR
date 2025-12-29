<h1><?= $edit ? 'Edit product' : 'Create product' ?></h1>
<form method='post'>
  Name: <input name='name' value='<?= htmlspecialchars($product['name'] ?? '') ?>' required><br><br>
  Price: <input name='price' type='number' step='0.01' value='<?= htmlspecialchars($product['price'] ?? '') ?>' required><br><br>
  <button>Save</button>
</form>
