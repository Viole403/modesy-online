<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-lg-7 col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo trans("update_category"); ?></h3>
			</div>
			<!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open_multipart('category_controller/update_category_post'); ?>

			<input type="hidden" name="id" value="<?php echo $category->id; ?>">

			<div class="box-body">

				<!-- include message block -->
				<?php $this->load->view('admin/includes/_messages'); ?>
				<?php foreach ($languages as $language): ?>
					<div class="form-group">
						<label><?php echo trans("category_name"); ?> (<?php echo $language->name; ?>)</label>
						<input type="text" class="form-control" name="name_lang_<?php echo $language->id; ?>" placeholder="<?php echo trans("category_name"); ?>"
							   value="<?php echo $this->category_model->get_category_name_by_lang_not_completed($category->id, $language->id); ?>" maxlength="255" required>
					</div>
				<?php endforeach; ?>

				<div class="form-group">
					<label class="control-label"><?php echo trans("slug"); ?>
						<small>(<?php echo trans("slug_exp"); ?>)</small>
					</label>
					<input type="text" class="form-control" name="slug" value="<?php echo html_escape($category->slug); ?>" placeholder="<?php echo trans("slug"); ?>">
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('title'); ?> (<?php echo trans('meta_tag'); ?>)</label>
					<input type="text" class="form-control" name="title_meta_tag"
						   placeholder="<?php echo trans('title'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo html_escape($category->title_meta_tag); ?>">
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('description'); ?> (<?php echo trans('meta_tag'); ?>)</label>
					<input type="text" class="form-control" name="description"
						   placeholder="<?php echo trans('description'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo html_escape($category->description); ?>">
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)</label>
					<input type="text" class="form-control" name="keywords"
						   placeholder="<?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo html_escape($category->keywords); ?>">
				</div>


				<div class="form-group">
					<label><?php echo trans('order'); ?></label>
					<input type="number" class="form-control" name="category_order" placeholder="<?php echo trans('order'); ?>"
						   value="<?php echo html_escape($category->category_order); ?>" min="1" max="99999" required>
				</div>

				<div class="form-group">
					<label><?php echo trans('homepage_order'); ?></label>
					<input type="number" class="form-control" name="homepage_order" placeholder="<?php echo trans('homepage_order'); ?>"
						   value="<?php echo html_escape($category->homepage_order); ?>" min="1" max="99999" required>
				</div>

				<div class="form-group">
					<label><?php echo trans('parent_category'); ?></label>
					<select class="form-control" name="parent_id[]" required onchange="get_subcategories(this.value, 0);">
						<option value="0"><?php echo trans('none'); ?></option>
						<?php foreach ($parent_categories as $item):
							if ($item->id != $category->id):
								if (!empty($parent_categories_array[0]) && $item->id == $parent_categories_array[0]->id):?>
									<option value="<?php echo $item->id; ?>" selected><?php echo html_escape(get_category_name_by_lang($item->id, $this->selected_lang->id)); ?></option>
								<?php else: ?>
									<option value="<?php echo $item->id; ?>"><?php echo html_escape(get_category_name_by_lang($item->id, $this->selected_lang->id)); ?></option>
								<?php endif;
							endif;
						endforeach; ?>
					</select>
					<div id="subcategories_container">
						<?php if (!empty($parent_categories_array)):
							for ($i = 1; $i < count($parent_categories_array); $i++):
								if (!empty($parent_categories_array[$i])):?>
									<?php $subcategories = $this->category_model->get_subcategories_by_parent_id_except_one($parent_categories_array[$i]->parent_id, $category->id);
									if (!empty($subcategories)):?>
										<select name="parent_id[]" class="form-control subcategory-select" data-select-id="<?php echo $i; ?>" onchange="get_subcategories(this.value,'<?php echo $i; ?>');">
											<option value=""><?php echo trans('none'); ?></option>
											<?php foreach ($subcategories as $subcategory): ?>
												<option value="<?php echo $subcategory->id; ?>" <?php echo ($subcategory->id == $parent_categories_array[$i]->id) ? 'selected' : ''; ?>><?php echo html_escape($subcategory->name); ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif;
								endif;
							endfor;
						endif; ?>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-4 col-xs-12">
							<label><?php echo trans('visibility'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="visibility" value="1" id="visibility_1"
								   class="square-purple" <?php echo ($category->visibility == '1') ? 'checked' : ''; ?>>
							<label for="visibility_1" class="option-label cursor-pointer"><?php echo trans('show'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="visibility" value="0" id="visibility_2"
								   class="square-purple" <?php echo ($category->visibility == '0') ? 'checked' : ''; ?>>
							<label for="visibility_2" class="option-label cursor-pointer"><?php echo trans('hide'); ?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-4 col-xs-12">
							<label><?php echo trans('show_on_homepage'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="show_on_homepage" value="1" id="show_on_homepage_1" class="square-purple" <?php echo ($category->show_on_homepage == '1') ? 'checked' : ''; ?>>
							<label for="show_on_homepage_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="show_on_homepage" value="0" id="show_on_homepage_2" class="square-purple" <?php echo ($category->show_on_homepage == '0') ? 'checked' : ''; ?>>
							<label for="show_on_homepage_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-4 col-xs-12">
							<label><?php echo trans('show_image_on_navigation'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="show_image_on_navigation" value="1" id="show_image_on_navigation_1" class="square-purple" <?php echo ($category->show_image_on_navigation == '1') ? 'checked' : ''; ?>>
							<label for="show_image_on_navigation_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-4 col-xs-12 col-option">
							<input type="radio" name="show_image_on_navigation" value="0" id="show_image_on_navigation_2" class="square-purple" <?php echo ($category->show_image_on_navigation == '0') ? 'checked' : ''; ?>>
							<label for="show_image_on_navigation_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('image'); ?></label>
					<?php if (!empty($category->image_1)): ?>
						<div class="display-block m-b-15">
							<img src="<?php echo get_category_image_url($category, 'image_1'); ?>" alt="" style="height: 200px;">
						</div>
					<?php endif; ?>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_image'); ?>
							<input type="file" id="Multifileupload" name="file" size="40" accept=".png, .jpg, .jpeg, .gif">
						</a>
					</div>

					<div id="MultidvPreview" class="image-preview"></div>
				</div>

			</div>


			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?> </button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>
		<!-- /.box -->
	</div>
</div>

<script>
    function get_subcategories(category_id, data_select_id) {
        var subcategories = get_subcategories_array(category_id);
        var date = new Date();
        //reset subcategories
        $('.subcategory-select').each(function () {
            if (parseInt($(this).attr('data-select-id')) > parseInt(data_select_id)) {
                $(this).remove();
            }
        });
        if (category_id == 0) {
            return false;
        }
        if (subcategories.length > 0) {
            var new_data_select_id = date.getTime();
            var select_tag = '<select class="form-control subcategory-select" data-select-id="' + new_data_select_id + '" name="parent_id[]" onchange="get_subcategories(this.value,' + new_data_select_id + ');">' +
                '<option value=""><?php echo trans('none'); ?></option>';
            for (i = 0; i < subcategories.length; i++) {
                select_tag += '<option value="' + subcategories[i].id + '">' + subcategories[i].name + '</option>';
            }
            select_tag += '</select>';
            $('#subcategories_container').append(select_tag);
        }
    }

    function get_subcategories_array(category_id) {
        var categories_array = <?php echo get_categories_json($this->selected_lang->id); ?>;
        var current_category_id = '<?php echo $category->id; ?>';
        var subcategories_array = [];
        for (i = 0; i < categories_array.length; i++) {
            if (categories_array[i].id != current_category_id && categories_array[i].parent_id == category_id) {
                subcategories_array.push(categories_array[i]);
            }
        }
        return subcategories_array;
    }
</script>
