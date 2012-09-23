<table class="table table-hover">
<thead>
<tr>
 <th>Name</th>
</tr>
</thead>
<tbody>
<?php foreach ($model as $property) { ?>
<tr>
  <td><a href="index.php?url=property/get/<?php echo $property['_id']?>"><?php echo $property['name'] ?></a></td>
</tr>
<?php } ?>
</tbody>
</table>
<a class="btn" href="index.php?url=property/edit">Create</a>
