<div class="span2">
<?php
  $gravatar = gravatar($model, 128);
  if ($gravatar) {
    echo $gravatar; ?>
<a target="blank" href="http://www.gravatar.com">change picture</a>
<?php } ?>
</div>
<div class="span10">
<form method="post" action="index.php?url=login/save">
 <legend>Edit Profile</legend>
 <?php if ($model['_id']) { ?>
 <input type="hidden" name="_id" value ="<?php echo $model['_id']?>"/>
 <input type="hidden" name="_rev" value ="<?php echo $model['_rev']?>"/>
 <?php } ?>
 <input type="hidden" name="login" value ="<?php echo $login?>"/>
 <label>Name</label>
 <input name="name" type="text" value="<?php echo $model['name']  ?>"/>
 <label>E-Mail</label>
 <input name="email" type="text" value="<?php echo $model['email']  ?>"/>

 <div class="form-actions">
 <button type="submit" class="btn btn-primary">Submit</button>
 </div>
</form>
</div>
