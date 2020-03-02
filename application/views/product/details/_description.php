<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-12">
		<div class="product-description">
			<h4 class="section-title"><?php echo trans("description"); ?></h4>
			<div class="description">
				<?php echo $product->description; ?>
			</div>
			<div class="description m-t-20">
				<div class="row">
					<div class="col-12">
						<div class="card-columns">
							<?php if (!empty($custom_fields)): ?>
								<?php foreach ($custom_fields as $custom_field):
									if (!empty($custom_field->field_value) || !empty($custom_field->field_common_ids)):?>
										<div class="card item-details">
											<div class="row">
												<div class="col-12">
													<strong><?php echo html_escape($custom_field->name); ?></strong>:&nbsp;&nbsp;<?php echo get_custom_field_value($custom_field); ?>
												</div>
											</div>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php if (!empty($product->shipping_cost_type) && $this->form_settings->shipping == 1): ?>
								<div class="card item-details">
									<div class="row">
										<div class="col-12">
											<strong><?php echo trans("shipping"); ?></strong>:&nbsp;&nbsp;<?php echo trans($product->shipping_time); ?>
										</div>
									</div>
								</div>
								<div class="card item-details">
									<div class="row">
										<div class="col-12">
											<?php $shipping_cost_type = get_shipping_option_by_key($product->shipping_cost_type, $selected_lang->id);
											if (!empty($shipping_cost_type)):
												if ($shipping_cost_type->shipping_cost != 1):?>
													<strong><?php echo trans("shipping_cost"); ?></strong>:&nbsp;&nbsp;<?php echo $shipping_cost_type->option_label; ?>
												<?php else: ?>
													<strong><?php echo trans("shipping_cost"); ?></strong>:&nbsp;&nbsp;<?php echo print_price($product->shipping_cost, $product->currency); ?>
												<?php endif;
											endif; ?>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="row-custom row-bn">
			<!--Include banner-->
			<?php $this->load->view("partials/_ad_spaces", ["ad_space" => "product", "class" => "m-b-30"]); ?>
		</div>
	</div>
</div>
