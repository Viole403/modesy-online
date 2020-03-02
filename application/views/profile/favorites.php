<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <!-- Wrapper -->
    <div id="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo trans("favorites"); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="profile-page-top">
                        <!-- load profile details -->
                        <?php $this->load->view("profile/_profile_user_info"); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <!-- load profile nav -->
                    <?php $this->load->view("profile/_profile_tabs"); ?>
                </div>

                <div class="col-sm-12 col-md-9">
                    <div class="profile-tab-content">
                        <div class="row row-product-items row-product">
                            <!--print products-->
                            <?php foreach ($products as $product): ?>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-product">
                                    <?php $this->load->view('product/_product_item', ['product' => $product, 'promoted_badge' => true]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="row-custom">
                            <!--Include banner-->
                            <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "profile", "class" => "m-t-30"]); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Wrapper End-->

    <!-- include send message modal -->
<?php $this->load->view("partials/_modal_send_message", ["subject" => null]); ?>