 <div class="container-fluid">
  <div class="row-fluid">
  <div id="property" class="span9">
    <h4>Property</h4>
    <table class="table">
      <tr><td>Name     <td><?php echo $model['name']  ?></tr>
      <tr><td>Address  <td><?php echo $model['address']  ?></tr>
    </table>

    <h4>Clients Viewing Property</h4>
    
    <div id="propertyclients-container" class="droppable">
    <div id="propertyclients">
    <?php if ($clients) { ?>
    <table class="table">
      <?php  foreach ($clients as $client) { ?>
      <tr>
         <td width="40px"><?php echo gravatar($client, 24)  ?>
         <td><?php echo $client['name']  ?>
         <td><a class="btn btn-danger btn-mini" href="javascript:unlink('<?php echo $model['_id']."','".$client['_id'] ?>')">x</a>
      </tr>
      <?php } ?>
    </table>
    <?php } else {  ?>
      No clients viewing
    <?php } ?>
    </div>
    </div>

    <div class="form-actions">
      <a class="btn btn-primary" href="index.php?url=property/edit/<?php echo $model['_id'] ?>" ><i class="icon-edit icon-white"></i> Edit</a>
      <a class="btn" href="index.php?url=property/listall">Back to List</a>
    </div>
  </div>

  <div class="span3">
    <h4>Add clients to property?</h4>
    <div id="allclients">
  
    </div>
  </div>
  </div>
</div>
<script>
  function jquery_ready() {
    $('#allclients').load('index.php?url=login/listall.body', 
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
        var loginid = ui.draggable.attr('id');
        var linkurl = 'index.php?url=property/link.json/<?php echo $model['_id']?>'; 

        $.get(linkurl, {login: loginid}, function(data) {
          reload_property();
        }, 'json');

      },
      hoverClass: 'droppable-hover'
    });  
  }

  function unlink(propertyid, loginid) {
    var unlinkurl = 'index.php?url=property/unlink.json/' + propertyid; 
    $.get(unlinkurl, {login: loginid}, function(data) {
      reload_property();
    }, 'json');
  }

  function reload_property() {
    var reloadurl = 'index.php?url=property/get.body/<?php echo $model['_id']?> #propertyclients';
    $('#propertyclients-container').load(reloadurl);
  }
</script>
