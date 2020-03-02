<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('email/_header', ['title' => $subject]); ?>
<!-- START CENTERED WHITE CONTAINER -->
<table role="presentation" class="main">
	<!-- START MAIN CONTENT AREA -->
	<tr>
		<td class="wrapper">
			<table role="presentation" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<h1 style="text-decoration: none; font-size: 24px;line-height: 28px;font-weight: bold"><?php echo $subject; ?></h1>
						<div class="mailcontent" style="line-height: 26px;font-size: 14px;">
							<div class="mailcontent" style="line-height: 26px;font-size: 14px;">
								<?php echo $message; ?>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- END MAIN CONTENT AREA -->
</table>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin-top: 10px;">
	<tr>
		<td class="content-block" style="text-align: center;width: 100%;">
			<?php if (!empty($settings->facebook_url)) : ?>
				<a href="<?php echo html_escape($settings->facebook_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
					<img src="<?php echo base_url(); ?>assets/img/social-icons/facebook.png" alt="" style="width: 28px; height: 28px;"/>
				</a>
			<?php endif; ?>
			<?php if (!empty($settings->twitter_url)) : ?>
				<a href="<?php echo html_escape($settings->twitter_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
					<img src="<?php echo base_url(); ?>assets/img/social-icons/twitter.png" alt="" style="width: 28px; height: 28px;"/>
				</a>
			<?php endif; ?>
			<?php if (!empty($settings->pinterest_url)) : ?>
				<a href="<?php echo html_escape($settings->pinterest_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
					<img src="<?php echo base_url(); ?>assets/img/social-icons/pinterest.png" alt="" style="width: 28px; height: 28px;"/>
				</a>
			<?php endif; ?>
			<?php if (!empty($settings->instagram_url)) : ?>
				<a href="<?php echo html_escape($settings->instagram_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
					<img src="<?php echo base_url(); ?>assets/img/social-icons/instagram.png" alt="" style="width: 28px; height: 28px;"/>
				</a>
			<?php endif; ?>
			<?php if (!empty($settings->linkedin_url)) : ?>
				<a href="<?php echo html_escape($settings->linkedin_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
					<img src="<?php echo base_url(); ?>assets/img/social-icons/linkedin.png" alt="" style="width: 28px; height: 28px;"/>
				</a>
			<?php endif; ?>
			<?php if (!empty($settings->vk_url)) : ?>
				<a href="<?php echo html_escape($settings->vk_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
					<img src="<?php echo base_url(); ?>assets/img/social-icons/vk.png" alt="" style="width: 28px; height: 28px;"/>
				</a>
			<?php endif; ?>
			<?php if (!empty($settings->youtube_url)) : ?>
				<a href="<?php echo html_escape($settings->youtube_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
					<img src="<?php echo base_url(); ?>assets/img/social-icons/youtube.png" alt="" style="width: 28px; height: 28px;"/>
				</a>
			<?php endif; ?>
		</td>
	</tr>
</table>

<!-- START FOOTER -->
<div class="footer">
	<table role="presentation" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="content-block powered-by">
				<span class="apple-link"><?php echo html_escape($settings->contact_address); ?></span><br>
				<?php echo html_escape($settings->copyright); ?>
			</td>
		</tr>
		<tr>
			<td class="content-block">
				<?php echo trans("dont_want_receive_emails"); ?> <a href="<?php echo base_url(); ?>unsubscribe?token=<?php echo !empty($subscriber->token) ? $subscriber->token : ''; ?>"><?php echo trans("unsubscribe"); ?></a>.
			</td>
		</tr>
	</table>
</div>
<!-- END FOOTER -->

<style>
	img {
		max-width: 100% !important;
		height: auto !important;
	}

	table tr td img {
		max-width: 100% !important;
		height: auto !important;
	}

	.table-products {
		border-bottom: 1px solid #d1d1d1;
		padding-bottom: 30px;
		margin-top: 20px;
	}

	.table-products th, td {
		padding: 8px 5px;
	}

	.wrapper table tr td img {
		height: auto !important;
	}
</style>
</body>
</html>
