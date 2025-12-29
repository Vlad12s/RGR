<h1><?= $edit ? 'Edit unit' : 'Create unit' ?></h1>
<form method='post'>
  Name: <input name='name' value='<?= htmlspecialchars($unit['unit_name'] ?? '') ?>' required><br><br>
  Type: <input name='type' value='<?= htmlspecialchars($unit['type'] ?? '') ?>' required><br><br>
  Commander:
  <select name='commander_id'>
    <option value=''>-- none --</option>
    <?php foreach ($cmds as $c): ?>
      <option value='<?= $c['commander_id'] ?>'
        <?= (isset($unit['commander_id']) && $unit['commander_id']==$c['commander_id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
      </option>
    <?php endforeach; ?>
  </select><br><br>
  <button>Save</button>
</form>
