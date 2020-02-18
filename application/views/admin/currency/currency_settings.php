<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("currency_settings"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('admin_controller/currency_settings_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('msg_settings'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('default_product_currency'); ?></label>
                    <select name="default_product_currency" class="form-control">
                        <?php if (!empty($currencies)):
                            foreach ($currencies as $item): ?>
                                <option value="<?php echo $item->code; ?>" <?php echo ($this->payment_settings->default_product_currency == $item->code) ? 'selected' : ''; ?>><?php echo $item->name . " (" . $item->hex . ")"; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-5">
                            <label><?php echo trans('allow_all_currencies_classified_ads'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="allow_all_currencies_for_classied" value="1" id="allow_1" class="square-purple" <?php echo ($this->payment_settings->allow_all_currencies_for_classied == 1) ? 'checked' : ''; ?>>
                            <label for="allow_1" class="option-label"><?php echo trans("yes"); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="allow_all_currencies_for_classied" value="0" id="allow_2" class="square-purple" <?php echo ($this->payment_settings->allow_all_currencies_for_classied != 1) ? 'checked' : ''; ?>>
                            <label for="allow_2" class="option-label"><?php echo trans("no"); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('promoted_products_payment_currency'); ?></label>
                    <select name="promoted_products_payment_currency" class="form-control">
                        <?php if (!empty($currencies)):
                            foreach ($currencies as $item): ?>
                                <option value="<?php echo $item->code; ?>" <?php echo ($payment_settings->promoted_products_payment_currency == $item->code) ? 'selected' : ''; ?>><?php echo $item->name . " (" . $item->hex . ")"; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-5">
                            <label><?php echo trans('currency_format'); ?> (Thousands Seperator)</label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_format" value="us" id="currency_format_1" class="square-purple" <?php echo ($this->payment_settings->currency_format == 'us') ? 'checked' : ''; ?>>
                            <label for="currency_format_1" class="option-label">1<strong>,</strong>234<strong>,</strong>567<strong>.</strong>89</label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_format" value="european" id="currency_format_2" class="square-purple" <?php echo ($this->payment_settings->currency_format == 'european') ? 'checked' : ''; ?>>
                            <label for="currency_format_2" class="option-label">1<strong>.</strong>234<strong>.</strong>567<strong>,</strong>89</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-5">
                            <label><?php echo trans('currency_symbol_format'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_symbol_format" value="left" id="currency_symbol_format_1" class="square-purple" <?php echo ($this->payment_settings->currency_symbol_format == 'left') ? 'checked' : ''; ?>>
                            <label for="currency_symbol_format_1" class="option-label">$100 (<?php echo trans("left"); ?>)</label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_symbol_format" value="right" id="currency_symbol_format_2" class="square-purple" <?php echo ($this->payment_settings->currency_symbol_format == 'right') ? 'checked' : ''; ?>>
                            <label for="currency_symbol_format_2" class="option-label">100$ (<?php echo trans("right"); ?>)</label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("add_currency"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('admin_controller/add_currency_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('msg_add'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <label><?php echo trans("currency_name"); ?></label>
                    <input type="text" class="form-control" name="name" placeholder="Ex: US Dollar" maxlength="200" required>
                </div>

                <div class="form-group">
                    <label><?php echo trans("currency_code"); ?></label>
                    <input type="text" class="form-control" name="code" placeholder="Ex: USD" maxlength="99" required>
                </div>

                <div class="form-group">
                    <label><?php echo trans("currency_symbol"); ?></label>
                    <input type="text" class="form-control" name="symbol" placeholder="Ex: $" maxlength="99" required>
                </div>

                <div class="form-group">
                    <label><?php echo trans("currency_hexcode"); ?></label>
                    <input type="text" class="form-control" name="hex" placeholder="Ex: <?php echo htmlentities("&#x24;") ?>" maxlength="99" required>
                </div>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_currency'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>

    <div class="col-lg-7 col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="pull-left">
                    <h3 class="box-title"><?php echo trans('currencies'); ?></h3>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="table-responsive">
                        <!-- include message block -->
                        <div class="col-sm-12">
                            <?php if (!empty($this->session->flashdata('msg_table'))):
                                $this->load->view('admin/includes/_messages');
                            endif; ?>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped dataTable" id="cs_datatable" role="grid"
                                   aria-describedby="example1_info">
                                <thead>
                                <tr role="row">
                                    <th width="20"><?php echo trans('id'); ?></th>
                                    <th><?php echo trans('currency'); ?></th>
                                    <th><?php echo trans('currency_code'); ?></th>
                                    <th><?php echo trans('currency_symbol'); ?></th>
                                    <th class="th-options"><?php echo trans('options'); ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ($currencies as $item): ?>
                                    <tr>
                                        <td><?php echo html_escape($item->id); ?></td>
                                        <td><?php echo html_escape($item->name); ?>&nbsp</td>
                                        <td><?php echo html_escape($item->code); ?></td>
                                        <td><?php echo html_escape($item->symbol); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                        type="button"
                                                        data-toggle="dropdown"><?php echo trans('select_option'); ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu options-dropdown">
                                                    <li>
                                                        <a href="<?php echo admin_url(); ?>update-currency/<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="delete_item('admin_controller/delete_currency_post','<?php echo $item->id; ?>','<?php echo trans("confirm_delete"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
