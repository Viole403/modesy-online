<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($product_variations)): ?>
    <div class="row">
        <div class="col-12">
            <table class="table table-product-variations">
                <thead>
                <tr>
                    <th scope="col"><?php echo trans("label"); ?></th>
                    <th scope="col"><?php echo trans("variation_type"); ?></th>
                    <th scope="col"><?php echo trans("visible"); ?></th>
                    <th scope="col" style="width: 250px;"><?php echo trans("options"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $last_common_id = "";
                foreach ($product_variations as $variation):
                    if ($last_common_id != $variation->common_id):?>
                        <tr>
                            <td><?php echo html_escape($variation->label); ?></td>
                            <td>
                                <?php echo trans($variation->variation_type); ?>&nbsp;
                                <?php if ($variation->variation_type != 'text' && $variation->variation_type != 'number'): ?>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-info btn-variation-table" onclick="edit_product_variation_options('<?php echo $variation->common_id; ?>','<?php echo $variation->product_id; ?>','<?php echo $variation->lang_id; ?>');">
                                        <span id="btn-variation-text-options-<?php echo $variation->common_id; ?>"><i class="icon-menu"></i><?php echo trans('edit_options'); ?></span>
                                        <div id="sp-options-<?php echo $variation->common_id; ?>" class="spinner spinner-btn-variation">
                                            <div class="bounce1"></div>
                                            <div class="bounce2"></div>
                                            <div class="bounce3"></div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($variation->visible == 1):
                                    echo trans("yes");
                                else:
                                    echo trans("no");
                                endif; ?>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-sm btn-secondary btn-variation-table" onclick="edit_product_variation('<?php echo $variation->common_id; ?>','<?php echo $variation->product_id; ?>','<?php echo $variation->lang_id; ?>');">
                                    <span id="btn-variation-text-<?php echo $variation->common_id; ?>"><i class="icon-edit"></i><?php echo trans('edit'); ?></span>
                                    <div id="sp-<?php echo $variation->common_id; ?>" class="spinner spinner-btn-variation">
                                        <div class="bounce1"></div>
                                        <div class="bounce2"></div>
                                        <div class="bounce3"></div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-secondary btn-variation-table" onclick="delete_product_variation('<?php echo $variation->common_id; ?>','<?php echo $variation->product_id; ?>','<?php echo trans("confirm_variation"); ?>');"><i class="icon-trash"></i><?php echo trans('delete'); ?></a>
                            </td>
                        </tr>
                    <?php endif;
                    $last_common_id = $variation->common_id;
                endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
