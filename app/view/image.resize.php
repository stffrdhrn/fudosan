<div class="container-fluid">
  <div class="row-fluid">
      <div id="property" class="span12">

<div class="image-decorator">
  <img id="resizable" src="index.php?url=image/get.data/<?php echo $id ?>" alt="resizee" />
</div>

<form action="index.php?url=image/crop/<?php echo $id ?>" method="post">
  <input id="x" name="x" type="hidden" />
  <input id="y" name="y" type="hidden" />
  <input id="width" name="width" type="hidden" />
  <input id="height" name="height" type="hidden" />
  <div class="form-actions">
  <input class="btn" type="submit" value="Crop Image" />
  </div>
</form>


<script>
  function jquery_ready() {
    $('img#resizable').imageCrop({
      displayPreview: true,
      displaySize: true,
      overlayOpacity : 0.25,
      onSelect: updateForm
    });

    function updateForm(crop) {
      $('input#x').val(crop.selectionX);
      $('input#y').val(crop.selectionY);
      $('input#width').val(crop.selectionWidth);
      $('input#height').val(crop.selectionHeight);
    };

  }
</script>
</div>
</div>
</div>
