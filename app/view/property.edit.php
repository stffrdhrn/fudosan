<form method="post" action="index.php?url=property/save">
 <legend><?php echo $title ?></legend>
 <?php if ($model['_id']) { ?>
 <input type="hidden" name="_id" value ="<?php echo $model['_id']?>"/>
 <input type="hidden" name="_rev" value ="<?php echo $model['_rev']?>"/>
 <?php } ?>
 <label>Name</label>
 <input name="name" type="text" value="<?php echo $model['name']  ?>"/>

 <label>Address</label>
 <input name="address" type="text" value="<?php echo $model['address']  ?>"/>
 <div class="form-actions">
 <button type="submit" class="btn btn-primary">Submit</button>
 <a class="btn" href="index.php?url=property/get/<?php echo $model['_id']?>">Back</a>
 </div>
</form>
