<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $count = 1;
foreach ($promoted_products as $product):
    if ($count > $limit && $count <= $new_limit): ?>
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">
            <?php $this->load->view('product/_product_item', ['product' => $product, 'promoted_badge' => false]); ?>
        </div>
    <?php endif;
    $count++;
endforeach; ?>