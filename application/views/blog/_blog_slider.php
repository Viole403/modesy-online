<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="blog-slider" class="owl-carousel blog-slider">
    <!--print blog slider posts-->
    <?php foreach ($blog_slider_posts as $item):
        $this->load->view('blog/_blog_item', ['item' => $item, 'class' => "owl-lazy"]);
    endforeach; ?>
</div>