<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="blog-item">
    <div class="blog-item-img">
        <a href="<?php echo lang_base_url(); ?>blog/<?php echo $item->category_slug; ?>/<?php echo $item->slug; ?>">
            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo get_blog_image_url($item, 'image_small'); ?>" alt="<?php echo html_escape($item->title); ?>"
                 class="img-fluid <?php echo (isset($class)) ? $class : 'lazyload'; ?>" onerror="this.src='<?php echo $img_bg_blog_small; ?>'">
        </a>
    </div>
    <h3 class="blog-post-title">
        <a href="<?php echo lang_base_url(); ?>blog/<?php echo $item->category_slug; ?>/<?php echo $item->slug; ?>">
            <?php echo html_escape(character_limiter($item->title, 56, '...')); ?>
        </a>
    </h3>
    <div class="blog-post-meta">
        <a href="<?php echo lang_base_url(); ?>blog/<?php echo $item->category_slug; ?>">
            <i class="icon-folder"></i><?php echo html_escape($item->category_name); ?>
        </a>
        <span><i class="icon-clock"></i><?php echo time_ago($item->created_at); ?></span>
    </div>
    <div class="blog-post-description">
        <?php echo html_escape(character_limiter($item->summary, 100, '...')); ?>
    </div>
</div>
