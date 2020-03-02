<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-lg-5 col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo trans("add_product_condition"); ?></h3>
			</div>
			<!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open('settings_controller/add_product_condition_post'); ?>

			<div class="box-body">
				<!-- include message block -->
				<?php $this->load->view('admin/includes/_messages_form'); ?>
				<?php foreach ($languages as $language): ?>
					<div class="form-group">
						<label><?php echo trans("option_label"); ?> (<?php echo $language->name; ?>)</label>
						<input type="text" class="form-control" name="option_label_<?php echo $language->id; ?>" placeholder="<?php echo trans("option_label"); ?>" maxlength="255" required>
					</div>
				<?php endforeach; ?>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-4 col-xs-12">
							<label><?php echo trans('visible'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="is_visible" value="1" id="visible_1" class="square-purple" checked>
							<label for="visible_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="is_visible" value="0" id="visible_2" class="square-purple">
							<label for="visible_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>

			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_option'); ?></button>
			</div>

		</div>
		<!-- /.box-footer -->
		<?php echo form_close(); ?><!-- form end -->
	</div>
	<!-- /.box -->

	<div class="col-lg-7 col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<div class="pull-left">
					<h3 class="box-title"><?php echo trans('product_conditions'); ?></h3>
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
									<th><?php echo trans('option_label'); ?></th>
									<th><?php echo trans('visible'); ?></th>
									<th class="max-width-120"><?php echo trans('options'); ?></th>
								</tr>
								</thead>
								<tbody>

								<?php foreach ($product_conditions as $item):
									$product_condition = get_product_condition_by_lang($item->common_id, $selected_lang->id);
									if (!empty($product_condition)):?>
										<tr>
											<td><?php echo html_escape($product_condition->id); ?></td>
											<td><?php echo html_escape($product_condition->option_label); ?></td>
											<td>
												<?php if ($product_condition->is_visible == 1): ?>
													<label class="label label-success"><?php echo trans("yes"); ?></label>
												<?php else: ?>
													<label class="label label-danger"><?php echo trans("no"); ?></label>
												<?php endif; ?>
											</td>
											<td>
												<div class="dropdown">
													<button class="btn bg-purple dropdown-toggle btn-select-option"
															type="button"
															data-toggle="dropdown"><?php echo trans('select_option'); ?>
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu options-dropdown">
														<li>
															<a href="<?php echo admin_url(); ?>form-settings/edit-product-condition/<?php echo $product_condition->id; ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
														</li>
														<li>
															<a href="javascript:void(0)" onclick="delete_item('settings_controller/delete_product_condition_post','<?php echo $product_condition->common_id; ?>','<?php echo trans("confirm_option"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
														</li>
													</ul>
												</div>
											</td>
										</tr>

									<?php endif;
								endforeach; ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div><!-- /.box-body -->
		</div>
	</div>
</div>
