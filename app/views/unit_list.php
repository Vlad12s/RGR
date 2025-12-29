<?php
// expects $unit
?>
<h1>Units</h1>
<a href="/?entity=unit&create=1">Create</a>

<table border="1" cellpadding="6">
<tr>
<th>ID</th><th>Name</th><th>Type</th><th>Commander</th><th>Actions</th>
</tr>

<?php foreach ($units as $u): ?>
<tr>
<td><?= $u['unit_id'] ?></td>
<td><?= htmlspecialchars($u['unit_name']) ?></td>
<td><?= htmlspecialchars($u['type']) ?></td>
<td><?= htmlspecialchars($u['commander_name'] ?? '-') ?></td>
<td>
<a href="/?entity=unit&edit=<?= $u['unit_id'] ?>">Edit</a>
<form method="post" style="display:inline">
<button name="delete" value="<?= $u['unit_id'] ?>"
onclick="return confirm('Delete?')">Delete</button>
</form>
</td>
</tr>
<?php endforeach ?>
</table>
