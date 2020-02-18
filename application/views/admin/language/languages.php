<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-lg-5 col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo trans("default_language"); ?></h3>
			</div>
			<!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open('language_controller/set_language_post'); ?>

			<div class="box-body">
				<!-- include message block -->
				<?php if (!empty($this->session->flashdata('mes_set_language'))):
					$this->load->view('admin/includes/_messages_form');
				endif; ?>

				<div class="form-group">
					<label><?php echo trans("language"); ?></label>
					<select name="site_lang" class="form-control">
						<?php foreach ($languages as $language): ?>
							<option value="<?php echo $language->id; ?>" <?php echo ($selected_lang->id == $language->id) ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>

			</div>

			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>

		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo trans("add_language"); ?></h3>
			</div>
			<!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open('language_controller/add_language_post'); ?>

			<div class="box-body">
				<!-- include message block -->
				<?php if (empty($this->session->flashdata('mes_set_language'))):
					$this->load->view('admin/includes/_messages_form');
				endif; ?>

				<div class="form-group">
					<label><?php echo trans("language_name"); ?></label>
					<input type="text" class="form-control" name="name" placeholder="<?php echo trans("language_name"); ?>"
						   value="<?php echo old('name'); ?>" maxlength="200" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
					<small>(Ex: English)</small>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans("short_form"); ?> </label>
					<input type="text" class="form-control" name="short_form" placeholder="<?php echo trans("short_form"); ?>"
						   value="<?php echo old('short_form'); ?>" maxlength="200" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
					<small>(Ex: en)</small>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans("language_code"); ?> </label>
					<input type="text" class="form-control" name="language_code" placeholder="<?php echo trans("language_code"); ?>"
						   value="<?php echo old('language_code'); ?>" maxlength="200" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
					<small>(Ex: en_us)</small>
				</div>

				<div class="form-group">
					<label><?php echo trans('order'); ?></label>
					<input type="number" class="form-control" name="language_order" placeholder="<?php echo trans('order'); ?>" value="1" min="1" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
				</div>

				<div class="form-group">
					<label><?php echo trans('text_editor_language'); ?></label>
					<select name="ckeditor_lang" class="form-control" required>
						<option value="af">Afrikaans</option>
						<option value="sq">Albanian</option>
						<option value="ar">Arabic</option>
						<option value="az">Azerbaijani</option>
						<option value="eu">Basque</option>
						<option value="bn">Bengali/Bangla</option>
						<option value="bs">Bosnian</option>
						<option value="bg">Bulgarian</option>
						<option value="ca">Catalan</option>
						<option value="zh-cn">Chinese Simplified</option>
						<option value="zh">Chinese Traditional</option>
						<option value="hr">Croatian</option>
						<option value="cs">Czech</option>
						<option value="da">Danish</option>
						<option value="nl">Dutch</option>
						<option value="en">English</option>
						<option value="en-au">English (Australia)</option>
						<option value="en-ca">English (Canadian)</option>
						<option value="en-gb">English (United Kingdom)</option>
						<option value="eo">Esperanto</option>
						<option value="et">Estonian</option>
						<option value="fo">Faroese</option>
						<option value="fi">Finnish</option>
						<option value="fr">French</option>
						<option value="fr-ca">French (Canada)</option>
						<option value="gl">Galician</option>
						<option value="ka">Georgian</option>
						<option value="de">German</option>
						<option value="de-ch">German (Switzerland)</option>
						<option value="el">Greek</option>
						<option value="gu">Gujarati</option>
						<option value="he">Hebrew</option>
						<option value="hi">Hindi</option>
						<option value="hu">Hungarian</option>
						<option value="is">Icelandic</option>
						<option value="id">Indonesian</option>
						<option value="it">Italian</option>
						<option value="ja">Japanese</option>
						<option value="km">Khmer</option>
						<option value="ko">Korean</option>
						<option value="ku">Kurdish</option>
						<option value="lv">Latvian</option>
						<option value="lt">Lithuanian</option>
						<option value="mk">Macedonian</option>
						<option value="ms">Malay</option>
						<option value="mn">Mongolian</option>
						<option value="no">Norwegian</option>
						<option value="nb">Norwegian Bokmal</option>
						<option value="oc">Occitan</option>
						<option value="fa">Persian</option>
						<option value="pl">Polish</option>
						<option value="pt-br">Portuguese (Brazil)</option>
						<option value="pt">Portuguese (Portugal)</option>
						<option value="ro">Romanian</option>
						<option value="ru">Russian</option>
						<option value="sr">Serbian (Cyrillic)</option>
						<option value="sr-latn">Serbian (Latin)</option>
						<option value="si">Sinhala</option>
						<option value="sk">Slovak</option>
						<option value="sl">Slovenian</option>
						<option value="es">Spanish</option>
						<option value="es-mx">Spanish (Mexico)</option>
						<option value="sv">Swedish</option>
						<option value="tt">Tatar</option>
						<option value="th">Thai</option>
						<option value="tr">Turkish</option>
						<option value="ug">Uighur</option>
						<option value="uk">Ukrainian</option>
						<option value="vi">Vietnamese</option>
						<option value="cy">Welsh</option>
					</select>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-4 col-xs-12">
							<label><?php echo trans('status'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="status" value="1" id="status1" class="square-purple" checked>
							<label for="status1" class="option-label"><?php echo trans('active'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="status" value="0" id="status2" class="square-purple">
							<label for="status2" class="option-label"><?php echo trans('inactive'); ?></label>
						</div>
					</div>
				</div>


			</div>

			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_language'); ?></button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>
	</div>

	<div class="col-lg-7 col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<div class="pull-left">
					<h3 class="box-title"><?php echo trans('languages'); ?></h3>
				</div>
			</div><!-- /.box-header -->
			<div class="box-body">
				<div class="row">
					<div class="table-responsive">
						<!-- include message block -->
						<div class="col-sm-12">
							<?php $this->load->view('admin/includes/_messages'); ?>
						</div>
						<div class="col-sm-12">
							<table class="table table-bordered table-striped dataTable" id="cs_datatable" role="grid"
								   aria-describedby="example1_info">
								<thead>
								<tr role="row">
									<th width="20"><?php echo trans('id'); ?></th>
									<th><?php echo trans('language_name'); ?></th>
									<th><?php echo trans('translation'); ?></th>
									<th><?php echo trans('status'); ?></th>
									<th class="th-options"><?php echo trans('options'); ?></th>
								</tr>
								</thead>
								<tbody>

								<?php foreach ($languages as $item): ?>
									<tr>
										<td><?php echo html_escape($item->id); ?></td>
										<td><?php echo html_escape($item->name); ?></td>
										<td>
											<a href="<?php echo admin_url(); ?>update-phrases/<?php echo $item->id; ?>?page=1" class="btn btn-sm btn-success float-right">
												<i class="fa fa-exchange"></i>&nbsp;&nbsp;<?php echo trans('edit_translations'); ?>
											</a>
										</td>
										<td>
											<?php if ($item->status == 1): ?>
												<label class="label label-success lbl-lang-status"><?php echo trans('active'); ?></label>
											<?php else: ?>
												<label class="label label-danger lbl-lang-status"><?php echo trans('inactive'); ?></label>
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
														<a href="<?php echo admin_url(); ?>update-language/<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
													</li>
													<li>
														<a href="javascript:void(0)" onclick="delete_item('language_controller/delete_language_post','<?php echo $item->id; ?>','<?php echo trans("confirm_language"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
													</li>
												</ul>
											</div>
										</td>
									</tr>

								<?php endforeach; ?>

								</tbody>
							</table>
						</div>
					</div>
				</div><!-- /.box-body -->
			</div>
		</div>
	</div>
</div>
