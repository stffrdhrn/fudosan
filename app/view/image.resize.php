<div class="container-fluid">
  <div class="row-fluid">
      <div id="property" class="span12">

<div class="image-decorator">
  <img id="resizable" src="<?php echo route('image', 'get.data', $id) ?>&w=600&h=600" alt="resizee" />
</div>

<form action="<?php echo route('image', 'crop', $id) ?>" method="post">
  <input id="img_width" name="img_width" type="hidden" value="600" />
  <input id="img_height" name="img_height" type="hidden" value="600" />
  <input id="x" name="x" type="hidden" />
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
    var image = $('img#resizable');

    image.imageCrop({
      displayPreview: true,
      displaySize: true,
      overlayOpacity : 0.25,
      aspectRatio : 1.5,
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
