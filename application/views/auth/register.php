<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="auth-container">
			<div class="auth-box">
				<div class="row">
					<div class="col-12">
						<h1 class="title"><?php echo trans("register"); ?></h1>
						<!-- form start -->
						<?php
						if ($recaptcha_status) {
							echo form_open('auth_controller/register_post', ['id' => 'form_validate', 'class' => 'validate_terms',
								'onsubmit' => "var serializedData = $(this).serializeArray();var recaptcha = ''; $.each(serializedData, function (i, field) { if (field.name == 'g-recaptcha-response') {recaptcha = field.value;}});if (recaptcha.length < 5) { $('.g-recaptcha>div').addClass('is-invalid');return false;} else { $('.g-recaptcha>div').removeClass('is-invalid');}"]);
						} else {
							echo form_open('auth_controller/register_post', ['id' => 'form_validate', 'class' => 'validate_terms']);
						}
						?>

						<div class="social-login-cnt">
							<?php $this->load->view("partials/_social_login", ['or_text' => trans("register_with_email")]); ?>
						</div>
						<!-- include message block -->
						<div id="result-register">
							<?php $this->load->view('partials/_messages'); ?>
						</div>
						<div class="spinner display-none spinner-activation-register">
							<div class="bounce1"></div>
							<div class="bounce2"></div>
							<div class="bounce3"></div>
						</div>
						<div class="form-group">
							<input type="text" name="username" class="form-control auth-form-input" placeholder="<?php echo trans("username"); ?>" value="<?php echo old("username"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
						</div>
						<div class="form-group">
							<input type="email" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" value="<?php echo old("email"); ?>" required>
						</div>
						<div class="form-group">
							<input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" value="<?php echo old("password"); ?>" required>
						</div>
						<div class="form-group">
							<input type="password" name="confirm_password" class="form-control auth-form-input" placeholder="<?php echo trans("password_confirm"); ?>" required>
						</div>
						<div class="form-group m-t-5 m-b-20">
							<div class="custom-control custom-checkbox custom-control-validate-input">
								<input type="checkbox" class="custom-control-input" name="terms" id="checkbox_terms" required>
								<label for="checkbox_terms" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>&nbsp;<a href="<?php echo lang_base_url(); ?>terms-conditions" class="link-terms" target="_blank"><strong><?php echo trans("terms_conditions"); ?></strong></a></label>
							</div>
						</div>
						<?php if ($recaptcha_status): ?>
							<div class="recaptcha-cnt">
								<?php generate_recaptcha(); ?>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<button type="submit" class="btn btn-md btn-custom btn-block"><?php echo trans("register"); ?></button>
						</div>
						<p class="p-social-media m-0 m-t-15"><?php echo trans("have_account"); ?>&nbsp;<a href="javascript:void(0)" class="link" data-toggle="modal" data-target="#loginModal"><?php echo trans("login"); ?></a></p>

						<?php echo form_close(); ?>
						<!-- form end -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wrapper End-->
