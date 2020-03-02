<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($product->product_type == 'digital'):
	if ($product->is_free_product == 1):
		if ($this->auth_check):?>
			<div class="row-custom m-t-10">
				<?php echo form_open('file_controller/download_free_digital_file'); ?>
				<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
				<button class="btn btn-instant-download"><i class="icon-download-solid"></i><?php echo trans("download") ?></button>
				<?php echo form_close(); ?>
			</div>
		<?php else: ?>
			<div class="row-custom m-t-10">
				<button class="btn btn-instant-download" data-toggle="modal" data-target="#loginModal"><i class="icon-download-solid"></i><?php echo trans("download") ?></button>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<?php if (!empty($digital_sale)): ?>
			<div class="row-custom m-t-10">
				<?php echo form_open('file_controller/download_purchased_digital_file'); ?>
				<input type="hidden" name="sale_id" value="<?php echo $digital_sale->id; ?>">
				<button class="btn btn-instant-download"><i class="icon-download-solid"></i><?php echo trans("download") ?></button>
				<?php echo form_close(); ?>
			</div>
		<?php else: ?>
			<label class="label-instant-download"><i class="icon-download-solid"></i><?php echo trans("instant_download"); ?></label>
		<?php endif;
	endif;
endif; ?>
<h2 class="product-title"><?php echo html_escape($product->title); ?></h2>
<?php if ($product->status == 0): ?>
	<label class="badge badge-warning badge-product-status"><?php echo trans("pending"); ?></label>
<?php elseif ($product->visibility == 0): ?>
	<label class="badge badge-danger badge-product-status"><?php echo trans("hidden"); ?></label>
<?php endif; ?>
<div class="row-custom meta">
	<?php echo trans("by"); ?>&nbsp;<a href="<?php echo lang_base_url() . 'profile' . '/' . $product->user_slug; ?>"><?php echo character_limiter(get_shop_name_product($product), 30, '..'); ?></a>
	<?php if ($general_settings->product_reviews == 1): ?>
		<span><i class="icon-comment"></i><?php echo html_escape($comment_count); ?></span>
	<?php endif; ?>
	<span><i class="icon-heart"></i><?php echo get_product_favorited_count($product->id); ?></span>
	<span><i class="icon-eye"></i><?php echo html_escape($product->hit); ?></span>
</div>
<div class="row-custom price">
	<?php if ($product->listing_type != 'bidding'): ?>
		<?php if ($product->is_sold == 1): ?>
			<strong class="lbl-price" style="color: #9a9a9a;"><?php echo print_price($product->price, $product->currency); ?><span class="price-line"></span></strong>
			<strong class="lbl-sold"><?php echo trans("sold"); ?></strong>
		<?php elseif ($product->is_free_product == 1): ?>
			<strong class="lbl-free"><?php echo trans("free"); ?></strong>
		<?php else:
			if (!empty($product->price)):?>
				<strong class="lbl-price"><?php echo print_price($product->price, $product->currency); ?></strong>
			<?php endif;
		endif; ?>
	<?php endif; ?>
	<?php if (auth_check()): ?>
		<button class="btn btn-contact-seller" data-toggle="modal" data-target="#messageModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
	<?php else: ?>
		<button class="btn btn-contact-seller" data-toggle="modal" data-target="#loginModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
	<?php endif; ?>
</div>

<div class="row-custom details">
	<?php if (!empty($product->product_condition)): ?>
		<div class="item-details">
			<div class="left">
				<label><?php echo trans("condition"); ?></label>
			</div>
			<div class="right">
				<?php $product_condition = get_product_condition_by_key($product->product_condition, $selected_lang->id);
				if (!empty($product_condition)):?>
					<span><?php echo html_escape($product_condition->option_label); ?></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($product->product_type == 'digital'): ?>
		<div class="item-details">
			<div class="left">
				<label><?php echo trans("files_included"); ?></label>
			</div>
			<div class="right">
				<span><?php echo html_escape($product->files_included); ?></span>
			</div>
		</div>
	<?php endif; ?>
	<div class="item-details">
		<div class="left">
			<label><?php echo trans("uploaded"); ?></label>
		</div>
		<div class="right">
			<span><?php echo time_ago($product->created_at); ?></span>
		</div>
	</div>
</div>

<!--Include buttons-->
<?php $this->load->view("product/details/_buttons", ['input_id_suffix' => 'mb', 'input_form_suffix' => 'mobile']); ?>

<!--Include social share-->
<?php $this->load->view("product/details/_product_share"); ?>
