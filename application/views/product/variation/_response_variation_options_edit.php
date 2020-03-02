<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($main_variation)): ?>
    <div class="modal-header">
        <h5 class="modal-title"><?php echo trans("edit_variation"); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="icon-close"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 box-variation-options">
                <label class="lbl-variation-options"><?php echo trans("options"); ?></label>
                <form id="form_edit_product_variation_options" novalidate>
                    <input type="hidden" name="variation_common_id" value="<?php echo $main_variation->common_id; ?>">
                    <input type="hidden" name="lang_id" value="<?php echo $selected_lang->id; ?>">
                    <div id="variation_options_accordion" class="variation-options-container">
                        <?php $main_options = $this->variation_model->get_variation_options($main_variation->common_id, $main_variation->lang_id);
                        if (!empty($main_options)):
                            foreach ($main_options as $main_option): ?>
                                <div id="<?php echo $main_option->option_common_id; ?>" class="card variation-option-item">
                                    <div class="card-header">
                                        <a class="collapsed card-link" data-toggle="collapse" href="#collapse_<?php echo $main_option->option_common_id; ?>">
                                            <?php echo $main_option->option_text; ?>&nbsp;&nbsp;<i class="icon-angle-down"></i></a>
                                        <a href="javascript:void(0)" class="btn-delete-variation-option" data-option-id="<?php echo $main_option->option_common_id; ?>" onclick="delete_product_variation_option('<?php echo $main_option->option_common_id; ?>','<?php echo $main_option->variation_common_id; ?>')"><i class="icon-trash"></i></a>
                                    </div>
                                    <div id="collapse_<?php echo $main_option->option_common_id; ?>" class="collapse" data-parent="#variation_options_accordion">
                                        <div class="card-body">
                                            <?php if (!empty($languages)):
                                                foreach ($languages as $language):
                                                    $option = $this->variation_model->get_variation_option_by_option_common_id($main_option->option_common_id, $language->id);
                                                    if (!empty($option)):?>
                                                        <input type="text" name="option_text_<?php echo $option->id; ?>" value="<?php echo $option->option_text; ?>" class="form-control form-input" placeholder="<?php echo $language->name; ?>">
                                                    <?php endif;
                                                endforeach;
                                            endif; ?>
                                            <div class="form-group m-b-5">
                                                <div class="row">
                                                    <div class="col-sm-5 col-12">
                                                        <label class="m-l-5"><?php echo trans('available_in_stock'); ?></label>
                                                    </div>
                                                    <div class="col-sm-3 col-12 col-option">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="available_in_stock_<?php echo $main_option->option_common_id; ?>" value="1" id="available_in_stock_<?php echo $main_option->id; ?>_1" class="custom-control-input" <?php echo ($main_option->available_in_stock == 1) ? 'checked' : ''; ?>>
                                                            <label for="available_in_stock_<?php echo $main_option->id; ?>_1" class="custom-control-label"><?php echo trans('yes'); ?></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 col-12 col-option">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="available_in_stock_<?php echo $main_option->option_common_id; ?>" value="0" id="available_in_stock_<?php echo $main_option->id; ?>_2" class="custom-control-input" <?php echo ($main_option->available_in_stock != 1) ? 'checked' : ''; ?>>
                                                            <label for="available_in_stock_<?php echo $main_option->id; ?>_2" class="custom-control-label"><?php echo trans('no'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </form>
                <?php if (!empty($main_options)): ?>
                    <hr>
                <?php endif; ?>
                <form id="form_add_product_variation_option" novalidate>
                    <input type="hidden" name="variation_common_id" value="<?php echo $main_variation->common_id; ?>">
                    <input type="hidden" name="lang_id" value="<?php echo $selected_lang->id; ?>">
                    <div class="form-group m-b-5">
                        <label class="control-label"><?php echo trans("add_option"); ?></label>
                        <?php if (!empty($languages)): ?>
                            <?php if (count($languages) <= 1): ?>
                                <input type="text" id="input_variation_option" class="form-control form-input input-variation-option" name="option_text_<?php echo $this->selected_lang->id; ?>" maxlength="255">
                            <?php else: ?>
                                <?php foreach ($languages as $language): ?>
                                    <?php if ($language->id == $this->selected_lang->id): ?>
                                        <input type="text" id="input_variation_option" class="form-control form-input input-variation-option" name="option_text_<?php echo $language->id; ?>" placeholder="<?php echo $language->name; ?>" maxlength="255">
                                    <?php else: ?>
                                        <input type="text" class="form-control form-input input-variation-option" name="option_text_<?php echo $language->id; ?>" placeholder="<?php echo $language->name . ' (' . trans("optional") . ')'; ?>" maxlength="255">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="form-group m-b-5">
                        <div class="row">
                            <div class="col-sm-5 col-12">
                                <label class="m-l-5"><?php echo trans('available_in_stock'); ?></label>
                            </div>
                            <div class="col-sm-3 col-12 col-option">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="available_in_stock" value="1" id="available_in_stock_1" class="custom-control-input" checked>
                                    <label for="available_in_stock_1" class="custom-control-label"><?php echo trans('yes'); ?></label>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 col-option">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="available_in_stock" value="0" id="available_in_stock_2" class="custom-control-input">
                                    <label for="available_in_stock_2" class="custom-control-label"><?php echo trans('no'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-info color-white btn_add_variation_option"><?php echo trans('add_option'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row-custom">
            <button type="submit" class="btn btn-md btn-secondary btn-variation float-right" onclick="$('#form_edit_product_variation_options').submit();"><?php echo trans("save_changes"); ?></button>
        </div>
    </div>
<?php endif; ?>
