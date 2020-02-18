<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($main_variation)): ?>
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="hidden" name="common_id" value="<?php echo $main_variation->common_id; ?>">
    <input type="hidden" name="lang_id" value="<?php echo $main_variation->lang_id; ?>">
    <div class="modal-header">
        <h5 class="modal-title"><?php echo trans("edit_variation"); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="icon-close"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 tab-variation">
                <div class="form-group">
                    <label class="control-label"><?php echo trans('label'); ?></label>
                    <?php if (!empty($languages)): ?>
                        <?php if (count($languages) <= 1): ?>
                            <input type="text" id="input_variation_label_edit" class="form-control form-input input-variation-label" name="label_lang_<?php echo $selected_lang->id; ?>" value="<?php echo $main_variation->label; ?>" maxlength="255" required>
                        <?php else: ?>
                            <?php foreach ($languages as $language): ?>
                                <?php if ($language->id == $selected_lang->id): ?>
                                    <input type="text" id="input_variation_label_edit" class="form-control form-input input-variation-label" name="label_lang_<?php echo $language->id; ?>" value="<?php echo $main_variation->label; ?>" placeholder="<?php echo $language->name; ?>" maxlength="255" required>
                                <?php else:
                                    $variation = $this->variation_model->get_variation($main_variation->common_id, $language->id); ?>
                                    <input type="text" class="form-control form-input input-variation-label" name="label_lang_<?php echo $language->id; ?>" value="<?php echo @$variation->label; ?>" placeholder="<?php echo $language->name . ' (' . trans("optional") . ')'; ?>" maxlength="255">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('variation_type'); ?></label>
                    <div class="selectdiv">
                        <select name="variation_type" class="form-control" onchange="show_variation_options_box(this.value);" required>
                            <option value="text" <?php echo ($main_variation->variation_type == 'text') ? 'selected' : ''; ?>><?php echo trans('text'); ?></option>
                            <option value="number" <?php echo ($main_variation->variation_type == 'number') ? 'selected' : ''; ?>><?php echo trans('number'); ?></option>
                            <option value="checkbox" <?php echo ($main_variation->variation_type == 'checkbox') ? 'selected' : ''; ?>><?php echo trans('checkbox'); ?></option>
                            <option value="radio_button" <?php echo ($main_variation->variation_type == 'radio_button') ? 'selected' : ''; ?>><?php echo trans('radio_button'); ?></option>
                            <option value="dropdown" <?php echo ($main_variation->variation_type == 'dropdown') ? 'selected' : ''; ?>><?php echo trans('dropdown'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-0">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <label class="control-label"><?php echo trans('visible'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="visible" value="1" id="visible_1" class="custom-control-input" <?php echo ($main_variation->visible == 1) ? 'checked' : ''; ?>>
                                <label for="visible_1" class="custom-control-label"><?php echo trans('yes'); ?></label>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="visible" value="0" id="visible_2" class="custom-control-input" <?php echo ($main_variation->visible != 1) ? 'checked' : ''; ?>>
                                <label for="visible_2" class="custom-control-label"><?php echo trans('no'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row-custom">
            <button type="button" class="btn btn-md btn-danger color-white float-left hidden btn-show-variation-form"><i class="icon-arrow-left"></i><?php echo trans("back") ?></button>
            <button type="submit" class="btn btn-md btn-secondary btn-variation float-right"><?php echo trans("save_changes"); ?></button>
        </div>
    </div>
<?php endif; ?>