<h4>Properties</h4>
<p>List of properties I am managing
<div id="allproperties">
<?php foreach ($model as $item) { ?>
<div id="<?php echo $item['_id'] ?>" class="draggable">
 <table class="table table-hover">
  <tr>
    <td><a href="<?php echo route('property', 'get', $item['_id'])?>"><?php echo $item['name'] ?></a>
  </tr>
 </table>
</div>
<?php } ?>
</div>
<a class="btn" href="<?php echo route('property','edit') ?>">Create</a>
