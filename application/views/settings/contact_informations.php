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

                        <?php echo form_open("profile_controller/contact_informations_post", ['id' => 'form_validate']); ?>

                        <div class="form-group">
                            <label class="control-label"><?php echo trans('location'); ?></label>
                            <div class="row">
                                <div class="col-12 col-sm-4 m-b-15">
                                    <div class="selectdiv">
                                        <select id="countries" name="country_id" class="form-control" onchange="get_states(this.value);">
                                            <option value=""><?php echo trans('country'); ?></option>
                                            <?php foreach ($countries as $item): ?>
                                                <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $user->country_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4 m-b-15">
                                    <div class="selectdiv">
                                        <select id="states" name="state_id" class="form-control" onchange="get_cities(this.value);">
                                            <option value=""><?php echo trans('state'); ?></option>
                                            <?php
                                            if (!empty($states)):
                                                foreach ($states as $item): ?>
                                                    <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $user->state_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                                <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 m-b-15">
                                    <div class="selectdiv">
                                        <select id="cities" name="city_id" class="form-control">
                                            <option value=""><?php echo trans('city'); ?></option>
                                            <?php
                                            if (!empty($cities)):
                                                foreach ($cities as $item): ?>
                                                    <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $user->city_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                                <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6 m-b-sm-15">
                                    <input type="text" name="address" class="form-control form-input" value="<?php echo html_escape($user->address); ?>" placeholder="<?php echo trans("address") ?>">
                                </div>

                                <div class="col-12 col-sm-3">
                                    <input type="text" name="zip_code" class="form-control form-input" value="<?php echo html_escape($user->zip_code); ?>" placeholder="<?php echo trans("zip_code") ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo trans("phone_number"); ?></label>
                            <input type="text" name="phone_number" class="form-control form-input" value="<?php echo html_escape($user->phone_number); ?>" placeholder="<?php echo trans("phone_number"); ?>">
                        </div>
                        <div class="form-group m-t-15">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="show_email" value="1" id="checkbox_show_email" class="custom-control-input" <?php echo ($user->show_email == 1) ? 'checked' : ''; ?>>
                                <label for="checkbox_show_email" class="custom-control-label"><?php echo trans("show_my_email"); ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="show_phone" value="1" id="checkbox_show_phone" class="custom-control-input" <?php echo ($user->show_phone == 1) ? 'checked' : ''; ?>>
                                <label for="checkbox_show_phone" class="custom-control-label"><?php echo trans("show_my_phone"); ?></label>
                            </div>
                        </div>
                        <div class="form-group m-b-30">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="show_location" value="1" id="checkbox_show_location" class="custom-control-input" <?php echo ($user->show_location == 1) ? 'checked' : ''; ?>>
                                <label for="checkbox_show_location" class="custom-control-label"><?php echo trans("show_my_location"); ?></label>
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

