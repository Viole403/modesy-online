<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $image_count = 0;
if (!empty($product_images)) {
	$image_count = count($product_images);
} ?>

<?php if ($image_count == 0):

	if (!empty($video)): ?>
		<div class="product-video-preview">
			<video id="player" playsinline controls>
				<source src="<?php echo get_product_video_url($video); ?>" type="video/mp4">
			</video>
		</div>
	<?php endif;

	if (!empty($audio)):
		$this->load->view('product/details/_audio_player');
	endif; ?>

<?php elseif ($image_count == 1): ?>

	<?php if (!empty($video)): ?>
		<div class="product-video-preview">
			<video id="player" playsinline controls>
				<source src="<?php echo get_product_video_url($video); ?>" type="video/mp4">
			</video>
		</div>

		<?php if (!empty($audio)):
			$this->load->view('product/details/_audio_player');
		endif; ?>
	<?php else: ?>
		<?php if (!empty($audio)):
			$this->load->view('product/details/_audio_player');
		else: ?>
			<?php foreach ($product_images as $image): ?>
				<div class="product-image-preview">
					<a class="image-popup lightbox" href="<?php echo get_product_image_url($image, 'image_big'); ?>" data-effect="mfp-zoom-out" title="">
						<img src="<?php echo get_product_image_url($image, 'image_default'); ?>" alt="<?php echo html_escape($product->title); ?>" class="img-fluid">
					</a>
				</div>
				<?php break;
			endforeach; ?>
		<?php endif; ?>

	<?php endif; ?>

<?php else: ?>

	<div class="product-slider-container">
		<div class="left">
			<div class="slider-custom-scrollbar">
				<div class="dots-container slider-dots <?php echo (count($product_images) < 2) ? 'hide-dosts-mobile' : ''; ?>">
					<?php if (!empty($product_images)):
						foreach ($product_images as $image): ?>
							<button class="dot dot_slider"><img src="<?php echo get_product_image_url($image, 'image_small'); ?>" alt="dot"></button>
						<?php endforeach;
					endif; ?>
					<?php if (!empty($video)): ?>
						<button class="dot dot_slider">
							<img src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="dot">
							<img src="<?php echo base_url() . 'assets/img/play-circle.svg'; ?>" class="dot-video-icon" alt="dot">
						</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="right">
			<div id="product-slider" class="owl-carousel product-slider">
				<?php if (!empty($product_images)):
					foreach ($product_images as $image): ?>
						<div class="item">
							<a class="image-popup lightbox" href="<?php echo get_product_image_url($image, 'image_big'); ?>" data-effect="mfp-zoom-out" title="">
								<img src="<?php echo base_url(); ?>assets/img/slider_bg.png" class="img-fluid" alt="slider-bg">
								<img src="<?php echo get_product_image_url($image, 'image_default'); ?>" class="img-product-slider" alt="<?php echo html_escape($product->title); ?>">
							</a>
						</div>
					<?php endforeach;
				endif; ?>
				<?php if (!empty($video)): ?>
					<div class="item">
						<video id="player" playsinline controls>
							<source src="<?php echo get_product_video_url($video); ?>" type="video/mp4">
						</video>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if (!empty($audio)):
		$this->load->view('product/details/_audio_player');
	endif;
endif; ?>

<script>
	$(document).ready(function () {
		setTimeout(function () {
			$(".product-video-preview").css("opacity", "1");
		}, 300);
		setTimeout(function () {
			$(".product-audio-preview").css("opacity", "1");
		}, 300);
	});
</script>
