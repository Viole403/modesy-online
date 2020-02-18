<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row-custom product-variations">
	<?php if (!empty($full_width_product_variations)):
		foreach ($full_width_product_variations as $variation):
			//checkbox
			if ($variation->variation_type == 'checkbox'):?>
				<div class="row row-product-variation item-variation">
					<div class="col-12 col-product-variation">
						<label class="label-product-variation"><?php echo html_escape($variation->label); ?></label>
					</div>
					<div class="col-12 col-product-variation">
						<?php $variation_options = get_product_variation_options($variation->common_id, $selected_lang->id);
						if (!empty($variation_options)):
							foreach ($variation_options as $option):
								if ($option->available_in_stock == 1): ?>
									<div class="custom-control custom-control-variation custom-control-validate-input">
										<input type="checkbox" name="variation<?php echo $variation->id; ?>[]" value="<?php echo html_escape($option->option_text); ?>" id="checkbox<?php echo $option->id . $input_id_suffix; ?>" class="custom-control-input" required>
										<label for="checkbox<?php echo $option->id . $input_id_suffix; ?>" data-input-name="variation<?php echo $variation->id; ?>[]" class="custom-control-label"><?php echo html_escape($option->option_text); ?></label>
									</div>
								<?php endif;
							endforeach;
						endif; ?>
					</div>
				</div>
			<?php
			//radio button
			elseif ($variation->variation_type == 'radio_button'): ?>
				<div class="row row-product-variation item-variation">
					<div class="col-12 col-product-variation">
						<label class="label-product-variation"><?php echo html_escape($variation->label); ?></label>
					</div>
					<div class="col-12 col-product-variation">
						<?php $variation_options = get_product_variation_options($variation->common_id, $selected_lang->id);
						if (!empty($variation_options)):
							foreach ($variation_options as $option):
								if ($option->available_in_stock == 1): ?>
									<div class="custom-control custom-control-variation custom-control-validate-input">
										<input type="radio" name="variation<?php echo $variation->id; ?>" data-name="variation<?php echo $variation->id; ?>" value="<?php echo html_escape($option->option_text); ?>" id="radio<?php echo $option->id . $input_id_suffix; ?>" class="custom-control-input" required>
										<label for="radio<?php echo $option->id . $input_id_suffix; ?>" data-input-name="variation<?php echo $variation->id; ?>" class="custom-control-label label-variation<?php echo $variation->id; ?>"><?php echo html_escape($option->option_text); ?></label>
									</div>
								<?php endif;
							endforeach;
						endif; ?>
					</div>
				</div>
			<?php
			endif;
		endforeach; ?>

	<?php endif; ?>

	<?php if (!empty($half_width_product_variations)): ?>
		<div class="row row-product-variation">
			<?php foreach ($half_width_product_variations as $variation):
				if ($variation->variation_type == 'text'):?>
					<div class="col-12 col-lg-6 col-product-variation item-variation">
						<div class="form-group">
							<label class="control-label"><?php echo html_escape($variation->label); ?></label>
							<input type="text" name="variation<?php echo $variation->id; ?>" class="form-control form-input" required>
						</div>
					</div>
				<?php elseif ($variation->variation_type == 'number'): ?>
					<div class="col-12 col-lg-6 col-product-variation item-variation">
						<div class="form-group">
							<label class="control-label"><?php echo html_escape($variation->label); ?></label>
							<input type="number" name="variation<?php echo $variation->id; ?>" class="form-control form-input" min="1" required>
						</div>
					</div>
				<?php elseif ($variation->variation_type == 'dropdown'): ?>
					<div class="col-12 col-lg-6 col-product-variation item-variation">
						<div class="form-group">
							<label class="control-label"><?php echo html_escape($variation->label); ?></label>
							<div class="selectdiv">
								<select name="variation<?php echo $variation->id; ?>" class="form-control" required>
									<option value=""><?php echo trans('select'); ?></option>
									<?php $variation_options = get_product_variation_options($variation->common_id, $selected_lang->id);
									if (!empty($variation_options)):
										foreach ($variation_options as $item):
											if ($item->available_in_stock == 1): ?>
												<option value="<?php echo html_escape($item->option_text); ?>"><?php echo html_escape($item->option_text); ?></option>
											<?php endif;
										endforeach;
									endif; ?>
								</select>
							</div>
						</div>
					</div>
				<?php endif;
			endforeach; ?>
		</div>
	<?php endif; ?>
</div>
