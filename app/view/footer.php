    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script> 
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>
<?php foreach ($jsplugins as $jsplugin) { ?>
    <script src="js/<?php echo $jsplugin ?>.js"></script>
<?php } ?>
    <script>
      if (typeof jquery_ready == 'function') {
        $(document).ready(jquery_ready);
      }
    </script>
  </body>
</html>
