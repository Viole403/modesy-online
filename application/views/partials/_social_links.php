<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<ul>
    <?php if (!empty($settings->facebook_url)): ?>
        <li><a href="<?php echo html_escape($settings->facebook_url); ?>"><i class="icon-facebook"></i></a></li>
    <?php endif; ?>
    <?php if (!empty($settings->twitter_url)): ?>
        <li><a href="<?php echo html_escape($settings->twitter_url); ?>"><i class="icon-twitter"></i></a></li>
    <?php endif; ?>
    <?php if (!empty($settings->instagram_url)): ?>
        <li><a href="<?php echo html_escape($settings->instagram_url); ?>"><i class="icon-instagram"></i></a></li>
    <?php endif; ?>
    <?php if (!empty($settings->pinterest_url)): ?>
        <li><a href="<?php echo html_escape($settings->pinterest_url); ?>"><i class="icon-pinterest"></i></a></li>
    <?php endif; ?>

    <?php if (!empty($settings->linkedin_url)): ?>
        <li><a href="<?php echo html_escape($settings->linkedin_url); ?>"><i class="icon-linkedin"></i></a></li>
    <?php endif; ?>

    <?php if (!empty($settings->vk_url)): ?>
        <li><a href="<?php echo html_escape($settings->vk_url); ?>"><i class="icon-vk"></i></a></li>
    <?php endif; ?>
    <?php if (!empty($settings->youtube_url)): ?>
        <li><a href="<?php echo html_escape($settings->youtube_url); ?>"><i class="icon-youtube"></i></a></li>
    <?php endif; ?>
    <?php if ($this->general_settings->rss_system == 1 && isset($show_rss)): ?>
        <li><a href="<?php echo lang_base_url(); ?>rss-feeds"><i class="icon-rss"></i></a></li>
    <?php endif; ?>
</ul>