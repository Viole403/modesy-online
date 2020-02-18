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
        </div>


        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <!-- load profile nav -->
                    <?php $this->load->view("earnings/_earnings_tabs"); ?>
                </div>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="row-custom">
                    <div class="earnings-boxes">
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-6 m-b-sm-15">
                                <div class="earnings-box">
                                    <p class="title"><?php echo trans("sales"); ?></p>
                                    <p class="price"><?php echo $user->number_of_sales; ?></p>
                                    <p class="description"><?php echo trans("number_of_total_sales"); ?></p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="earnings-box">
                                    <p class="title"><?php echo trans("balance"); ?></p>
                                    <p class="price"><?php echo print_price($user->balance, $this->payment_settings->default_product_currency); ?></p>
                                    <p class="description"><?php echo trans("balance_exp"); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-custom table-earnings-container">
                    <div class="table-responsive">
                        <table class="table table-orders table-striped">
                            <thead>
                            <tr>
                                <th scope="col"><?php echo trans("order"); ?></th>
                                <th scope="col"><?php echo trans("price"); ?></th>
                                <th scope="col"><?php echo trans("commission_rate"); ?></th>
                                <th scope="col"><?php echo trans("shipping_cost"); ?></th>
                                <th scope="col"><?php echo trans("earned_amount"); ?></th>
                                <th scope="col"><?php echo trans("date"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($earnings as $earning): ?>
                                <tr>
                                    <td>#<?php echo $earning->order_number; ?></td>
                                    <td><?php echo print_price($earning->price, $earning->currency); ?></td>
                                    <td><?php echo $earning->commission_rate; ?>%</td>
                                    <td><?php echo print_price($earning->shipping_cost, $earning->currency); ?></td>
                                    <td><?php echo print_price($earning->earned_amount, $earning->currency); ?></td>
                                    <td><?php echo date("Y-m-d / h:i", strtotime($earning->created_at)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (empty($earnings)): ?>
                        <p class="text-center">
                            <?php echo trans("no_records_found"); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="row-custom m-t-15">
                    <div class="float-right">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Wrapper End-->

