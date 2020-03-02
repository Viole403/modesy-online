<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="map-container">
    <iframe src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo $map_address; ?>&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true" id="IframeMap" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
</div>
<script>
    $(document).ready(function () {
        $("#IframeMap").attr("src", "https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo $map_address; ?>&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true");
    });
</script>
