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

                <h1 class="page-title"><?php echo trans("settings"); ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <!-- load profile nav -->
                    <?php $this->load->view("settings/_setting_tabs"); ?>
                </div>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="row-custom">
                    <div class="profile-tab-content">
                        <!-- include message block -->
                        <?php $this->load->view('partials/_messages'); ?>

                        <?php echo form_open("profile_controller/shipping_address_post", ['id' => 'form_validate']); ?>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 col-md-6 m-b-sm-15">
                                    <label class="control-label"><?php echo trans("first_name"); ?></label>
                                    <input type="text" name="shipping_first_name" class="form-control form-input" value="<?php echo $user->shipping_first_name; ?>" placeholder="<?php echo trans("first_name"); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label"><?php echo trans("last_name"); ?></label>
                                    <input type="text" name="shipping_last_name" class="form-control form-input" value="<?php echo $user->shipping_last_name; ?>" placeholder="<?php echo trans("last_name"); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 col-md-6 m-b-sm-15">
                                    <label class="control-label"><?php echo trans("email"); ?></label>
                                    <input type="email" name="shipping_email" class="form-control form-input" value="<?php echo $user->shipping_email; ?>" placeholder="<?php echo trans("email"); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label"><?php echo trans("phone_number"); ?></label>
                                    <input type="text" name="shipping_phone_number" class="form-control form-input" value="<?php echo $user->shipping_phone_number; ?>" placeholder="<?php echo trans("phone_number"); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?php echo trans("address"); ?> 1</label>
                            <input type="text" name="shipping_address_1" class="form-control form-input" value="<?php echo $user->shipping_address_1; ?>" placeholder="<?php echo trans("address"); ?> 1" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?php echo trans("address"); ?> 2 (<?php echo trans("optional"); ?>)</label>
                            <input type="text" name="shipping_address_2" class="form-control form-input" value="<?php echo $user->shipping_address_2; ?>" placeholder="<?php echo trans("address"); ?> 2 (<?php echo trans("optional"); ?>)">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 col-md-6 m-b-sm-15">
                                    <label class="control-label"><?php echo trans("country"); ?></label>
                                    <div class="selectdiv">
                                        <select id="countries" name="shipping_country_id" class="form-control" required>
                                            <option value="" selected><?php echo trans("select_country"); ?></option>
                                            <?php foreach ($countries as $item): ?>
                                                <option value="<?php echo $item->id; ?>" <?php echo ($user->shipping_country_id == $item->id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label"><?php echo trans("state"); ?></label>
                                    <input type="text" name="shipping_state" class="form-control form-input" value="<?php echo $user->shipping_state; ?>" placeholder="<?php echo trans("state"); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 col-md-6 m-b-sm-15">
                                    <label class="control-label"><?php echo trans("city"); ?></label>
                                    <input type="text" name="shipping_city" class="form-control form-input" value="<?php echo $user->shipping_city; ?>" placeholder="<?php echo trans("city"); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="control-label"><?php echo trans("zip_code"); ?></label>
                                    <input type="text" name="shipping_zip_code" class="form-control form-input" value="<?php echo $user->shipping_zip_code; ?>" placeholder="<?php echo trans("zip_code"); ?>" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-md btn-custom"><?php echo trans("save_changes") ?></button>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->

