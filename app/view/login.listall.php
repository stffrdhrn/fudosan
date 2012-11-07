<h4>My Clients</h4>
<p>List of clients currently covered
<div id="allclients">
<?php foreach ($model as $item) { 
  if($item['_id'] == $login['_id']) {
    continue;
  }
?>
<div id="<?php echo $item['_id'] ?>" class="draggable">
 <table class="table table-hover">
  <tr>
    <td width="48px"><?php echo gravatar($item, 32) ?>
    <td><a href="<?php echo AppHelper::route('login', 'get', $item['_id'])?>"><?php echo $item['name'] ?></a>
  </tr>
 </table>
</div>
<?php } ?>
</div>
