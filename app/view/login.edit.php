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

<?php if($login['role'] == 'admin') { ?>
        <label>Role</label>
        <select name="role">
	  <option value="admin" <?php if($model['role'] == 'admin') { echo 'selected';} ?>>Admin</option>
	  <option value="manager" <?php if($model['role'] == 'manager') { echo 'selected';} ?>>Property Manager</option>
	  <option value="user" <?php if($model['role'] == 'user') { echo 'selected';} ?>>User</option>
        </select>
<?php } ?>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
