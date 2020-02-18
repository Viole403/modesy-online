<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="profile-tabs">
    <ul class="nav">
        <li class="nav-item <?php echo ($active_tab == 'active_sales') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo lang_base_url(); ?>sales">
                <span><?php echo trans("active_sales"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'completed_sales') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo lang_base_url(); ?>sales/completed-sales">
                <span><?php echo trans("completed_sales"); ?></span>
            </a>
        </li>
    </ul>
</div>