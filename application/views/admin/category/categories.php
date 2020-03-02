<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<div class="left">
					<h3 class="box-title"><?php echo trans('categories'); ?></h3>
				</div>
				<div class="right">
					<a href="<?php echo admin_url(); ?>add-category" class="btn btn-success btn-add-new">
						<i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo trans('add_category'); ?>
					</a>
				</div>
			</div><!-- /.box-header -->

			<!-- include message block -->
			<div class="col-sm-12">
				<?php $this->load->view('admin/includes/_messages'); ?>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable" id="cs_datatable" role="grid"
								   aria-describedby="example1_info">
								<thead>
								<tr role="row">
									<th width="20"><?php echo trans('id'); ?></th>
									<th><?php echo trans('category_name'); ?></th>
									<th><?php echo trans('parent_category'); ?></th>
									<th><?php echo trans('order'); ?></th>
									<th><?php echo trans('visibility'); ?></th>
									<th><?php echo trans('show_on_homepage'); ?></th>
									<th class="th-options"><?php echo trans('options'); ?></th>
								</tr>
								</thead>
								<tbody>

								<?php foreach ($categories as $item):
									$category = get_category_joined($item->id);
									if (!empty($category)):?>
										<tr>
											<td><?php echo html_escape($category->id); ?></td>
											<td><?php echo html_escape($category->name); ?></td>
											<td>
												<?php
												$parent = get_category($category->parent_id);
												if (!empty($parent)) {
													echo get_category_name_by_lang($parent->id, $selected_lang->id);
												} else {
													echo "-";
												} ?>
											</td>
											<td><?php echo html_escape($category->category_order); ?></td>
											<td>
												<?php if ($category->visibility == 1): ?>
													<label class="label label-success label-table"><i class="fa fa-eye"></i></label>
												<?php else: ?>
													<label class="label label-danger label-table"><i class="fa fa-eye"></i></label>
												<?php endif; ?>
											</td>
											<td>
												<?php if ($category->show_on_homepage == 1): ?>
													<label class="label label-success label-table"><?php echo trans('yes'); ?></label>
												<?php else: ?>
													<label class="label label-danger label-table"><?php echo trans('no'); ?></label>
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
															<a href="<?php echo admin_url(); ?>update-category/<?php echo html_escape($category->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
														</li>
														<li>
															<a href="javascript:void(0)" onclick="delete_item('category_controller/delete_category_post','<?php echo $category->id; ?>','<?php echo trans("confirm_category"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
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
