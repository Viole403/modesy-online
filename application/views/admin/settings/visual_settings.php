<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-sm-12 col-xs-12 col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="left">
					<h3 class="box-title"><?php echo trans('visual_settings'); ?></h3>
				</div>
			</div><!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open_multipart('admin_controller/visual_settings_post'); ?>

			<div class="box-body">

				<!-- include message block -->
				<?php $this->load->view('admin/includes/_messages'); ?>

				<div class="form-group">
					<label class="control-label"><?php echo trans('site_color'); ?></label>
					<div class="col-sm-12">
						<div class="row">
							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color0" value="default"
									   class="regular-checkbox" <?php echo ($visual_settings->site_color === "default" ||
									$visual_settings->site_color === "") ? "checked" : ""; ?>/>
								<label for="color0" style="background-color: #222;"></label>
							</div>
							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color1" value="pacific_blue"
									   class="regular-checkbox" <?php echo ($visual_settings->site_color === "pacific_blue") ? "checked" : ""; ?>/>
								<label for="color1" style="background-color: #09b1ba;"></label>
							</div>

							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color2" value="violet" class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "violet") ? "checked" : ""; ?>/>
								<label for="color2" style="background-color:  #6770B7;"></label>
							</div>

							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color3" value="meadow"
									   class="regular-checkbox" <?php echo ($visual_settings->site_color === "meadow") ? "checked" : ""; ?>/>
								<label for="color3" style="background-color: #1abc9c;"></label>
							</div>

							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color4" value="blue"
									   class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "blue") ? "checked" : ""; ?>/>
								<label for="color4" style="background-color:  #1da7da;"></label>
							</div>

							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color5" value="amaranth"
									   class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "amaranth") ? "checked" : ""; ?>/>
								<label for="color5" style="background-color:  #e91e63;"></label>
							</div>

							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color6" value="red"
									   class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "red") ? "checked" : ""; ?>/>
								<label for="color6" style="background-color:  #e74c3c;"></label>
							</div>

							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color7" value="orange"
									   class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "orange") ? "checked" : ""; ?>/>
								<label for="color7" style="background-color:  #f86923;"></label>
							</div>

							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color8" value="yellow"
									   class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "yellow") ? "checked" : ""; ?>/>
								<label for="color8" style="background-color:  #ffbb02;"></label>
							</div>
							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color9" value="bayoux"
									   class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "bayoux") ? "checked" : ""; ?>/>
								<label for="color9" style="background-color:  #495d7f;"></label>
							</div>
							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color10" value="cascade"
									   class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "cascade") ? "checked" : ""; ?>/>
								<label for="color10" style="background-color:  #95a5a6;"></label>
							</div>
							<div class="custom-checkbox">
								<input type="radio" name="site_color" id="color11" value="royal_blue" class="regular-checkbox"
									<?php echo ($visual_settings->site_color === "royal_blue") ? "checked" : ""; ?>/>
								<label for="color11" style="background-color:  #2a41e8;"></label>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('logo'); ?> (180x50px)</label>
					<div style="margin-bottom: 10px;">
						<img src="<?php echo get_logo($visual_settings); ?>" alt="logo" style="max-width: 160px; max-height: 160px;">
					</div>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_logo'); ?>
							<input type="file" name="logo" size="40" accept=".png, .jpg, .jpeg, .gif, .svg" onchange="$('#upload-file-info1').html($(this).val());">
						</a>
						(.png, .jpg, .jpeg, .gif, .svg)
					</div>
					<span class='label label-info' id="upload-file-info1"></span>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('logo_email'); ?></label>
					<div style="margin-bottom: 10px;">
						<img src="<?php echo get_logo_email($visual_settings); ?>" alt="logo" style="max-width: 160px; max-height: 160px;">
					</div>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_logo'); ?>
							<input type="file" name="logo_email" size="40" accept=".png, .jpg, .jpeg" onchange="$('#upload-file-info3').html($(this).val());">
						</a>
						(.png, .jpg, .jpeg)
					</div>
					<span class='label label-info' id="upload-file-info3"></span>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('favicon'); ?> (16x16px)</label>
					<div style="margin-bottom: 10px;">
						<img src="<?php echo get_favicon($visual_settings); ?>" alt="favicon" style="max-width: 100px; max-height: 100px;">
					</div>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_favicon'); ?>
							<input type="file" name="favicon" size="40" accept=".png" onchange="$('#upload-file-info2').html($(this).val());">
						</a>
						(.png)
					</div>
					<span class='label label-info' id="upload-file-info2"></span>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>
		<!-- /.box -->
	</div>
	<div class="col-sm-12 col-xs-12 col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="left">
					<h3 class="box-title"><?php echo trans('watermark'); ?></h3>
				</div>
			</div><!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open_multipart('admin_controller/update_watermark_settings_post'); ?>

			<div class="box-body">

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<label><?php echo trans('add_watermark_product_images'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_product_images" value="1" id="watermark_product_images_1" class="square-purple" <?php echo ($general_settings->watermark_product_images == 1) ? 'checked' : ''; ?>>
							<label for="watermark_product_images_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_product_images" value="0" id="watermark_product_images_2" class="square-purple" <?php echo ($general_settings->watermark_product_images != 1) ? 'checked' : ''; ?>>
							<label for="watermark_product_images_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<label><?php echo trans('add_watermark_blog_images'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_blog_images" value="1" id="watermark_blog_images_1" class="square-purple" <?php echo ($general_settings->watermark_blog_images == 1) ? 'checked' : ''; ?>>
							<label for="watermark_blog_images_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_blog_images" value="0" id="watermark_blog_images_2" class="square-purple" <?php echo ($general_settings->watermark_blog_images != 1) ? 'checked' : ''; ?>>
							<label for="watermark_blog_images_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<label><?php echo trans('add_watermark_thumbnail_images'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_thumbnail_images" value="1" id="watermark_thumbnail_images_1" class="square-purple" <?php echo ($general_settings->watermark_thumbnail_images == 1) ? 'checked' : ''; ?>>
							<label for="watermark_thumbnail_images_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_thumbnail_images" value="0" id="watermark_thumbnail_images_2" class="square-purple" <?php echo ($general_settings->watermark_thumbnail_images != 1) ? 'checked' : ''; ?>>
							<label for="watermark_thumbnail_images_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label"><?php echo trans('vertical_alignment'); ?></label>
							<select class="form-control" name="watermark_vrt_alignment" required>
								<option value="top" <?php echo ($general_settings->watermark_vrt_alignment == 'top') ? 'selected' : ''; ?>><?php echo trans('top'); ?></option>
								<option value="middle" <?php echo ($general_settings->watermark_vrt_alignment == 'middle') ? 'selected' : ''; ?>><?php echo trans('middle'); ?></option>
								<option value="bottom" <?php echo ($general_settings->watermark_vrt_alignment == 'bottom') ? 'selected' : ''; ?>><?php echo trans('bottom'); ?></option>
							</select>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label"><?php echo trans('horizontal_alignment'); ?></label>
							<select class="form-control" name="watermark_hor_alignment" required>
								<option value="left" <?php echo ($general_settings->watermark_hor_alignment == 'left') ? 'selected' : ''; ?>><?php echo trans('left'); ?></option>
								<option value="center" <?php echo ($general_settings->watermark_hor_alignment == 'center') ? 'selected' : ''; ?>><?php echo trans('center'); ?></option>
								<option value="right" <?php echo ($general_settings->watermark_hor_alignment == 'right') ? 'selected' : ''; ?>><?php echo trans('right'); ?></option>
							</select>
						</div>
					</div>
				</div>

				<div class="form-group m-t-10">
					<label class="control-label"><?php echo trans('watermark_image'); ?></label>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_image'); ?>
							<input type="file" name="watermark_image" size="40" accept=".png" onchange="$('#upload-file-info4').html($(this).val().replace(/.*[\/\\]/, ''));">
						</a>
						(.png)
					</div>
					<span class='label label-info' id="upload-file-info4"></span>
					<div style="margin-top: 10px;">
						<img src="<?php echo base_url() . $general_settings->watermark_image_large; ?>" alt="" style="max-width: 300px; max-height: 300px; background-color: #ccc;padding: 5px;">
					</div>
				</div>

			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>
		<!-- /.box -->
	</div>
</div>

<style>
	.form-group {
		margin-bottom: 30px !important;
	}
</style>
