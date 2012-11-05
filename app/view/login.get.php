<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">
     <div class="center">
<?php
      $gravatar = gravatar($model, 128);
      if ($gravatar) {
        echo $gravatar; ?>
<?php } ?>
     </div>
    </div>
    <div class="span8">
      <h4>Client Profile</h4>
      <table class="table">
        <tr><td>Name     <td><?php echo $model['name']  ?></tr>
        <tr><td>E-Mail  <td><a href="<?php echo $model['email']  ?>"><?php echo $model['email']  ?></a></tr>
      </table>

      <h4>Properties client is viewing</h4>

      <div id="propertyclients-container" class="droppable">
        <div id="propertyclients">
          <?php if ($properties) { ?>
          <table class="table">
          <?php  foreach ($properties as $property) { ?>
           <tr>
             <td><?php echo $property['name']  ?>
             <td><a href="javascript:unlink_client('<?php echo $property['_id']."','".$model['_id'] ?>')"><i class="icon-remove"></i></a>
           </tr>
          <?php } ?>
          </table>
          <?php } else {  ?>
          Client not viewing any properties. Drag from right to show client properties
          <?php } ?>
        </div>
      </div>

      <div class="form-actions">
        <a class="btn" href="<?php echo route('login', 'listall') ?>">Back to My Clients</a>
<?php if ($login['role'] == 'admin') { ?>
        <a class="btn btn-warning" href="<?php echo route('login', 'edit', $model['_id']) ?>">Edit Client</a>
<?php } ?>
      </div>
    </div>

    <div class="span2">
      <h4>Available Properties</h4>
      <div id="allproperties"></div>
    </div>
  </div>
</div>
<script>
  function jquery_ready() {
    $('#allproperties').load('index.php?url=property/listall.body #allproperties', 
      function(response,status,xhr) {
        $('.draggable').draggable({
          revert: true,
          cursor: 'move',
          helper: 'clone',
          zIndex: 100
        });
    });
    $('.droppable').droppable({
      drop: function(event, ui) {
        var this_droppable = this;
        var id = ui.draggable.attr('id');
        var linkurl = 'index.php?url=property/link.json/'+id; 

        $.get(linkurl, {login: '<?php echo $model['_id'] ?>'}, function(data) {
          reload_client();
        }, 'json');

      },
      hoverClass: 'droppable-hover'
    });  
  }

  function unlink_client(propertyid, loginid) {
    var unlinkurl = 'index.php?url=property/unlink_client.json/' + propertyid; 
    $.get(unlinkurl, {login: loginid}, function(data) {
      reload_client();
    }, 'json');
  }

  function reload_client() {
    var reloadurl = 'index.php?url=login/get.body/<?php echo $model['_id']?> #propertyclients';
    $('#propertyclients-container').load(reloadurl);
  }

</script>


