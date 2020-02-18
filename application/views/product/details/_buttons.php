<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (!empty($input_form_suffix)) {
	$input_form_suffix = '_' . $input_form_suffix;
} ?>

<?php if ($product->listing_type == 'sell_on_site'): ?>
	<?php echo form_open(lang_base_url() . 'add-to-cart', ['id' => 'form_add_cart' . $input_form_suffix]); ?>
	<?php $this->load->view('product/details/_product_variations', ['input_id_suffix' => $input_id_suffix]); ?>
	<?php if ($product->is_sold == 0): ?>
		<?php if ($product->quantity > 1 && $product->product_type == 'physical'): ?>
			<div class="row-custom">
				<label class="lbl-quantity"><?php echo trans("quantity"); ?></label>
			</div>
			<div class="row-custom">
				<div class="touchspin-container">
					<input id="quantity_touchspin<?php echo $input_form_suffix; ?>" type="text" value="1" class="form-input">
				</div>
			</div>
		<?php endif; ?>

		<?php $this->load->view('product/details/_messages'); ?>

		<?php if ($product->is_free_product != 1): ?>
			<div class="row-custom m-t-15">
				<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
				<input type="hidden" name="product_quantity" value="1">
				<button class="btn btn-md btn-block"><?php echo trans("add_to_cart") ?></button>
			</div>
		<?php endif; ?>

	<?php endif; ?>
	<?php echo form_close(); ?>
<?php elseif ($product->listing_type == 'bidding'): ?>
	<?php echo form_open(lang_base_url() . 'request-quote', ['id' => 'form_add_cart' . $input_form_suffix]); ?>
	<?php $this->load->view('product/details/_product_variations', ['input_id_suffix' => $input_id_suffix]); ?>
	<?php if ($product->is_sold == 0): ?>
		<?php if ($product->quantity > 1 && $product->product_type == 'physical'): ?>
			<div class="row-custom">
				<label class="lbl-quantity"><?php echo trans("quantity"); ?></label>
			</div>
			<div class="row-custom">
				<div class="touchspin-container">
					<input id="quantity_touchspin<?php echo $input_form_suffix; ?>" type="text" value="1" class="form-input">
				</div>
			</div>
		<?php endif; ?>

		<?php $this->load->view('product/details/_messages'); ?>
		<?php if ($this->auth_check): ?>
			<div class="row-custom m-t-15">
				<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
				<input type="hidden" name="product_quantity" value="1">
				<button class="btn btn-md btn-block"><?php echo trans("request_a_quote") ?></button>
			</div>
		<?php else: ?>
			<div class="row-custom m-t-15">
				<button type="button" data-toggle="modal" data-target="#loginModal" class="btn btn-md btn-block"><?php echo trans("request_a_quote") ?></button>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php echo form_close(); ?>
<?php else:
	if (!empty($product->external_link)): ?>
		<div class="row-custom">
			<a href="<?php echo $product->external_link; ?>" class="btn btn-md btn-block" target="_blank"><?php echo trans("buy_now") ?></a>
		</div>
	<?php endif;
endif; ?>

<?php if (!empty($product->demo_url)): ?>
	<div class="row-custom m-t-10">
		<a href="<?php echo $product->demo_url; ?>" target="_blank" class="btn btn-favorite"><i class="icon-preview"></i><?php echo trans("live_preview") ?></a>
	</div>
<?php endif; ?>

<div class="row-custom m-t-10">
	<?php echo form_open('product_controller/add_remove_favorites'); ?>
	<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
	<?php if (is_product_in_favorites($product->id)): ?>
		<button class="btn btn-favorite"><i class="icon-heart"></i><?php echo trans("remove_from_favorites") ?></button>
	<?php else: ?>
		<button class="btn btn-favorite"><i class="icon-heart-o"></i><?php echo trans("add_to_favorites") ?></button>
	<?php endif; ?>
	<?php echo form_close(); ?>
</div>
