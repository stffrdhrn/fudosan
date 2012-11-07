<h4>My Properties</h4>
<p>List of properties my agent wants me to check out, this should only be available to users
<table class="table table-hover">
<?php foreach ($model as $item) { ?>
  <tr>
    <td><a href="<?php echo AppHelper::route('property', 'get', $item['_id'])?>"><?php echo $item['name'] ?></a>
  </tr>
<?php } ?>
</table>
