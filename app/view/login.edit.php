<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">
     <div class="center">
<?php
      $gravatar = gravatar($model, 128);
      if ($gravatar) {
        echo $gravatar; ?>
      <p><a target="blank" href="http://www.gravatar.com">change picture</a></p>
<?php } ?>
     </div>
    </div>
    <div class="span10">
      <form method="post" action="index.php?url=login/save">
        <legend>Edit Profile</legend>
        <input type="hidden" name="_id" value ="<?php echo $model['_id']?>"/>
 <?php if ($model['_rev']) { ?>
        <input type="hidden" name="_rev" value ="<?php echo $model['_rev']?>"/>
 <?php } ?>
        <label>Name</label>
        <input name="name" type="text" value="<?php echo $model['name']  ?>"/>
        <label>E-Mail</label>
        <input name="email" type="text" value="<?php echo $model['email']  ?>"/>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
