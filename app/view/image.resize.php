<div class="image-decorator">
  <img id="resizable" src="index.php?url=image/get.data/<?php echo $id ?>" alt="resizee" />
</div>

<form action="crop.php" method="post" onsubmit="return validateForm();">
  <input id="x" name="x" type="hidden" />
  <input id="y" name="y" type="hidden" />
  <input id="width" name="width" type="hidden" />
  <input id="height" name="height" type="hidden" />
  <input type="submit" value="Crop Image" />
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
