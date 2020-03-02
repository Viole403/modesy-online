<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">

            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?php echo $title; ?></h1>
            </div>

            <div class="col-12">
                <div class="page-contact">
                    <div class="row">
                        <?php if (!empty($favorites)):
                            foreach ($favorites as $favorite):
                                $product = get_available_product($favorite);
                                if (!empty($product)): ?>
                                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">
                                        <?php $this->load->view('product/_product_item', ['product' => $product, 'promoted_badge' => false]); ?>
                                    </div>
                                <?php
                                endif;
                            endforeach;
                        else: ?>
                            <div class="col-12">
                                <p class="text-center"><?php echo trans("no_products_found"); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- Wrapper End-->
