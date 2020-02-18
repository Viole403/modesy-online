<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="modal fade" id="addVariationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-custom modal-variation" role="document">
            <div class="modal-content">
                <form id="form_add_product_variation" novalidate>
                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo trans("add_variation"); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 tab-variation">
                                <div class="form-group m-b-10">
                                    <label class="control-label"><?php echo trans('label'); ?></label>
                                    <?php if (!empty($languages)): ?>
                                        <?php if (count($languages) <= 1): ?>
                                            <input type="text" id="input_variation_label" class="form-control form-input input-variation-label" name="label_lang_<?php echo $this->selected_lang->id; ?>" maxlength="255" required>
                                        <?php else: ?>
                                            <?php foreach ($languages as $language): ?>
                                                <?php if ($language->id == $this->selected_lang->id): ?>
                                                    <input type="text" id="input_variation_label" class="form-control form-input input-variation-label" name="label_lang_<?php echo $language->id; ?>" placeholder="<?php echo $language->name; ?>" maxlength="255" required>
                                                <?php else: ?>
                                                    <input type="text" class="form-control form-input input-variation-label" name="label_lang_<?php echo $language->id; ?>" placeholder="<?php echo $language->name . ' (' . trans("optional") . ')'; ?>" maxlength="255">
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo trans('variation_type'); ?></label>
                                    <div class="selectdiv">
                                        <select name="variation_type" class="form-control" onchange="show_variation_options_box(this.value);" required>
                                            <option value="text" checked><?php echo trans('text'); ?></option>
                                            <option value="number"><?php echo trans('number'); ?></option>
                                            <option value="checkbox"><?php echo trans('checkbox'); ?></option>
                                            <option value="radio_button"><?php echo trans('radio_button'); ?></option>
                                            <option value="dropdown"><?php echo trans('dropdown'); ?></option>
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
                                                <input type="radio" name="visible" value="1" id="edit_visible_1" class="custom-control-input" checked>
                                                <label for="edit_visible_1" class="custom-control-label"><?php echo trans('yes'); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12 col-option">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="visible" value="0" id="edit_visible_2" class="custom-control-input">
                                                <label for="edit_visible_2" class="custom-control-label"><?php echo trans('no'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-md btn-secondary btn-variation float-right"><?php echo trans("add_variation"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVariationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-custom modal-variation" role="document">
            <div class="modal-content">
                <form id="form_edit_product_variation" novalidate>
                    <div id="response_product_variation_edit"></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVariationOptionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-custom modal-variation" role="document">
            <div class="modal-content">
                <div id="response_product_variation_options_edit"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="variationModalSelect" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-custom modal-variation" role="document">
            <div class="modal-content">
                <form id="form_select_product_variation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo trans("created_variations"); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if (empty($user_variations)): ?>
                            <p class="text-center m-t-20"><?php echo trans("msg_no_created_variations"); ?></p>
                        <?php else: ?>
                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                            <div class="form-group">
                                <label class="control-label"><?php echo trans('select_variation'); ?></label>
                                <div class="selectdiv">
                                    <select name="common_id" class="form-control" required>
                                        <?php $last_common_id = "";
                                        foreach ($user_variations as $user_variation):
                                            if ($user_variation->insert_type == "new"):
                                                if ($user_variation->common_id != $last_common_id):?>
                                                    <option value="<?php echo $user_variation->common_id; ?>"><?php echo html_escape($user_variation->label) . ' (' . trans($user_variation->variation_type) . ' )'; ?></option>
                                                <?php endif;
                                                $last_common_id = $user_variation->common_id; ?>
                                            <?php endif;
                                        endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <?php if (!empty($user_variations)): ?>
                            <button type="submit" class="btn btn-md btn-secondary btn-variation"><?php echo trans("select"); ?></button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $this->load->view("product/variation/_js_variations"); ?>