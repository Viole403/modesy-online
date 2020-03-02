<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">
	<div class="box-header with-border">
		<div class="left">
			<h3 class="box-title"><?php echo trans('shop_opening_requests'); ?></h3>
		</div>
	</div><!-- /.box-header -->

	<div class="box-body">
		<div class="row">
			<!-- include message block -->
			<div class="col-sm-12">
				<?php $this->load->view('admin/includes/_messages'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped dataTable" id="cs_datatable" role="grid"
						   aria-describedby="example1_info">
						<thead>
						<tr role="row">
							<th width="20"><?php echo trans('id'); ?></th>
							<th><?php echo trans('image'); ?></th>
							<th><?php echo trans('username'); ?></th>
							<th><?php echo trans('email'); ?></th>
							<th><?php echo trans('shop_name'); ?></th>
							<th><?php echo trans('shop_description'); ?></th>
							<th class="max-width-120"><?php echo trans('options'); ?></th>
						</tr>
						</thead>
						<tbody>

						<?php foreach ($requests as $user): ?>
							<tr>
								<td><?php echo html_escape($user->id); ?></td>
								<td>
									<img src="<?php echo get_user_avatar($user); ?>" alt="user" class="img-responsive" style="height: 50px;">
								</td>
								<td><?php echo html_escape($user->username); ?></td>
								<td>
									<?php echo html_escape($user->email);
									if ($user->email_status == 1): ?>
										<small class="text-success">(<?php echo trans("confirmed"); ?>)</small>
									<?php else: ?>
										<small class="text-danger">(<?php echo trans("unconfirmed"); ?>)</small>
									<?php endif; ?>
								</td>
								<td><?php echo html_escape($user->shop_name); ?></td>
								<td><?php echo html_escape($user->about_me); ?></td>
								<td>
									<?php echo form_open('admin_controller/approve_shop_opening_request'); ?>
									<input type="hidden" name="id" value="<?php echo $user->id; ?>">
									<div class="dropdown">
										<button class="btn bg-purple dropdown-toggle btn-select-option"
												type="button"
												data-toggle="dropdown"><?php echo trans('select_option'); ?>
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu options-dropdown">
											<li>
												<button type="submit" name="submit" value="1" class="btn-list-button">
													<i class="fa fa-check option-icon"></i><?php echo trans('approve'); ?>
												</button>
											</li>
											<li>
												<button type="submit" name="submit" value="0" class="btn-list-button">
													<i class="fa fa-times option-icon"></i><?php echo trans('decline'); ?>
												</button>
											</li>
										</ul>
									</div>
									<?php echo form_close(); ?>
								</td>
							</tr>

						<?php endforeach; ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div><!-- /.box-body -->
</div>

<?php if (!empty($this->session->userdata('mds_send_email_data'))): ?>
	<script>
        $(document).ready(function () {
            var data = JSON.parse(<?php echo json_encode($this->session->userdata("mds_send_email_data"));?>);
            if (data) {
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                data['form_lang_base_url'] = '<?php echo lang_base_url(); ?>';
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: base_url + "ajax_controller/send_email",
                    data: data,
                    success: function (response) {
                    }
                });
            }
        });
	</script>
<?php endif;
$this->session->unset_userdata('mds_send_email_data'); ?>
