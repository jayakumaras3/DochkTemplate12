<?= $this->include('templates/header_mp4') ?>
<body  oncontextmenu="return false;">
<div class="container">
                  <video width="100%" controls controlsList="nodownload">
                <source src="<?php echo base_url() ?>/upload/client/<?php echo $demoid . '/' . $filename ?>" type="video/mp4">
                Your browser does not support HTML5 video.
            </video>
      </div>
</body>
<script>

    $(document).ready(function () {
       
		$(document).bind("contextmenu",function(e){
			return false;
		});
    });
	</script>
</html>
<?= $this->include('templates/footer_view') ?>