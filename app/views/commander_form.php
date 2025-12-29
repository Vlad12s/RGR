<h1><?= $edit ? 'Edit commander' : 'Create commander' ?></h1>
<form method='post'>
  Name: <input name='name' value='<?= htmlspecialchars($commander['name'] ?? '') ?>' required><br><br>
  Rank: <input name='rank' value='<?= htmlspecialchars($commander['commander_rank'] ?? '') ?>' required><br><br>
  <button>Save</button>
</form>
