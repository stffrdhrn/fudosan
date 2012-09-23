<?php foreach ($model as $item) { ?>
<div id="<?php echo $item['_id'] ?>" class="draggable">
 <table class="table table-hover">
  <tr>
    <td width="48px"><?php echo gravatar($item, 32) ?>
    <td><a href="index.php?url=login/edit/<?php echo $item['_id']?>"><?php echo $item['name'] ?></a>
  </tr>
 </table>
</div>
<?php } ?>
