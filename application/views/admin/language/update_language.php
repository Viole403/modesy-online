<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("update_language"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('language_controller/update_language_post'); ?>

            <input type="hidden" name="id" value="<?php echo html_escape($language->id); ?>">

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <label><?php echo trans("language_name"); ?></label>
                    <input type="text" class="form-control" name="name" placeholder="<?php echo trans("language_name"); ?>"
                           value="<?php echo $language->name; ?>" maxlength="200" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
                    <small>(Ex: English)</small>
                </div>

                <?php if ($language->short_form == "en"): ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("short_form"); ?> </label>
                        <input type="text" class="form-control" name="short_form" placeholder="<?php echo trans("short_form"); ?>"
                               value="<?php echo $language->short_form; ?>" maxlength="200" readonly <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
                        <small>(Ex: en)</small>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("short_form"); ?> </label>
                        <input type="text" class="form-control" name="short_form" placeholder="<?php echo trans("short_form"); ?>"
                               value="<?php echo $language->short_form; ?>" maxlength="200" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
                        <small>(Ex: en)</small>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans("language_code"); ?> </label>
                    <input type="text" class="form-control" name="language_code" placeholder="<?php echo trans("language_code"); ?>"
                           value="<?php echo $language->language_code; ?>" maxlength="200" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
                    <small>(Ex: en_us)</small>
                </div>

                <div class="form-group">
                    <label><?php echo trans('order'); ?></label>
                    <input type="number" class="form-control" name="language_order" placeholder="<?php echo trans('order'); ?>"
                           value="<?php echo $language->language_order; ?>" min="1" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>

                <div class="form-group">
                    <label><?php echo trans('text_editor_language'); ?></label>
                    <select name="ckeditor_lang" class="form-control" required>
                        <option value="af" <?php echo ($this->selected_lang->ckeditor_lang == 'af') ? 'selected' : ''; ?>>Afrikaans</option>
                        <option value="sq" <?php echo ($this->selected_lang->ckeditor_lang == 'sq') ? 'selected' : ''; ?>>Albanian</option>
                        <option value="ar" <?php echo ($this->selected_lang->ckeditor_lang == 'ar') ? 'selected' : ''; ?>>Arabic</option>
                        <option value="az" <?php echo ($this->selected_lang->ckeditor_lang == 'az') ? 'selected' : ''; ?>>Azerbaijani</option>
                        <option value="eu" <?php echo ($this->selected_lang->ckeditor_lang == 'eu') ? 'selected' : ''; ?>>Basque</option>
                        <option value="bn" <?php echo ($this->selected_lang->ckeditor_lang == 'bn') ? 'selected' : ''; ?>>Bengali/Bangla</option>
                        <option value="bs" <?php echo ($this->selected_lang->ckeditor_lang == 'bs') ? 'selected' : ''; ?>>Bosnian</option>
                        <option value="bg" <?php echo ($this->selected_lang->ckeditor_lang == 'bg') ? 'selected' : ''; ?>>Bulgarian</option>
                        <option value="ca" <?php echo ($this->selected_lang->ckeditor_lang == 'ca') ? 'selected' : ''; ?>>Catalan</option>
                        <option value="zh-cn" <?php echo ($this->selected_lang->ckeditor_lang == 'zh-cn') ? 'selected' : ''; ?>>Chinese Simplified</option>
                        <option value="zh" <?php echo ($this->selected_lang->ckeditor_lang == 'zh') ? 'selected' : ''; ?>>Chinese Traditional</option>
                        <option value="hr" <?php echo ($this->selected_lang->ckeditor_lang == 'hr') ? 'selected' : ''; ?>>Croatian</option>
                        <option value="cs" <?php echo ($this->selected_lang->ckeditor_lang == 'cs') ? 'selected' : ''; ?>>Czech</option>
                        <option value="da" <?php echo ($this->selected_lang->ckeditor_lang == 'da') ? 'selected' : ''; ?>>Danish</option>
                        <option value="nl" <?php echo ($this->selected_lang->ckeditor_lang == 'nl') ? 'selected' : ''; ?>>Dutch</option>
                        <option value="en" <?php echo ($this->selected_lang->ckeditor_lang == 'en') ? 'selected' : ''; ?>>English</option>
                        <option value="en-au" <?php echo ($this->selected_lang->ckeditor_lang == 'en-au') ? 'selected' : ''; ?>>English (Australia)</option>
                        <option value="en-ca" <?php echo ($this->selected_lang->ckeditor_lang == 'en-ca') ? 'selected' : ''; ?>>English (Canadian)</option>
                        <option value="en-gb" <?php echo ($this->selected_lang->ckeditor_lang == 'en-gb') ? 'selected' : ''; ?>>English (United Kingdom)</option>
                        <option value="eo" <?php echo ($this->selected_lang->ckeditor_lang == 'eo') ? 'selected' : ''; ?>>Esperanto</option>
                        <option value="et" <?php echo ($this->selected_lang->ckeditor_lang == 'et') ? 'selected' : ''; ?>>Estonian</option>
                        <option value="fo" <?php echo ($this->selected_lang->ckeditor_lang == 'fo') ? 'selected' : ''; ?>>Faroese</option>
                        <option value="fi" <?php echo ($this->selected_lang->ckeditor_lang == 'fi') ? 'selected' : ''; ?>>Finnish</option>
                        <option value="fr" <?php echo ($this->selected_lang->ckeditor_lang == 'fr') ? 'selected' : ''; ?>>French</option>
                        <option value="fr-ca" <?php echo ($this->selected_lang->ckeditor_lang == 'fr-ca') ? 'selected' : ''; ?>>French (Canada)</option>
                        <option value="gl" <?php echo ($this->selected_lang->ckeditor_lang == 'gl') ? 'selected' : ''; ?>>Galician</option>
                        <option value="ka" <?php echo ($this->selected_lang->ckeditor_lang == 'ka') ? 'selected' : ''; ?>>Georgian</option>
                        <option value="de" <?php echo ($this->selected_lang->ckeditor_lang == 'de') ? 'selected' : ''; ?>>German</option>
                        <option value="de-ch" <?php echo ($this->selected_lang->ckeditor_lang == 'de-ch') ? 'selected' : ''; ?>>German (Switzerland)</option>
                        <option value="el" <?php echo ($this->selected_lang->ckeditor_lang == 'el') ? 'selected' : ''; ?>>Greek</option>
                        <option value="gu" <?php echo ($this->selected_lang->ckeditor_lang == 'gu') ? 'selected' : ''; ?>>Gujarati</option>
                        <option value="he" <?php echo ($this->selected_lang->ckeditor_lang == 'he') ? 'selected' : ''; ?>>Hebrew</option>
                        <option value="hi" <?php echo ($this->selected_lang->ckeditor_lang == 'hi') ? 'selected' : ''; ?>>Hindi</option>
                        <option value="hu" <?php echo ($this->selected_lang->ckeditor_lang == 'hu') ? 'selected' : ''; ?>>Hungarian</option>
                        <option value="is" <?php echo ($this->selected_lang->ckeditor_lang == 'is') ? 'selected' : ''; ?>>Icelandic</option>
                        <option value="id" <?php echo ($this->selected_lang->ckeditor_lang == 'id') ? 'selected' : ''; ?>>Indonesian</option>
                        <option value="it" <?php echo ($this->selected_lang->ckeditor_lang == 'it') ? 'selected' : ''; ?>>Italian</option>
                        <option value="ja" <?php echo ($this->selected_lang->ckeditor_lang == 'ja') ? 'selected' : ''; ?>>Japanese</option>
                        <option value="km" <?php echo ($this->selected_lang->ckeditor_lang == 'km') ? 'selected' : ''; ?>>Khmer</option>
                        <option value="ko" <?php echo ($this->selected_lang->ckeditor_lang == 'ko') ? 'selected' : ''; ?>>Korean</option>
                        <option value="ku" <?php echo ($this->selected_lang->ckeditor_lang == 'ku') ? 'selected' : ''; ?>>Kurdish</option>
                        <option value="lv" <?php echo ($this->selected_lang->ckeditor_lang == 'lv') ? 'selected' : ''; ?>>Latvian</option>
                        <option value="lt" <?php echo ($this->selected_lang->ckeditor_lang == 'lt') ? 'selected' : ''; ?>>Lithuanian</option>
                        <option value="mk" <?php echo ($this->selected_lang->ckeditor_lang == 'mk') ? 'selected' : ''; ?>>Macedonian</option>
                        <option value="ms" <?php echo ($this->selected_lang->ckeditor_lang == 'ms') ? 'selected' : ''; ?>>Malay</option>
                        <option value="mn" <?php echo ($this->selected_lang->ckeditor_lang == 'mn') ? 'selected' : ''; ?>>Mongolian</option>
                        <option value="no" <?php echo ($this->selected_lang->ckeditor_lang == 'no') ? 'selected' : ''; ?>>Norwegian</option>
                        <option value="nb" <?php echo ($this->selected_lang->ckeditor_lang == 'nb') ? 'selected' : ''; ?>>Norwegian Bokmal</option>
                        <option value="oc" <?php echo ($this->selected_lang->ckeditor_lang == 'oc') ? 'selected' : ''; ?>>Occitan</option>
                        <option value="fa" <?php echo ($this->selected_lang->ckeditor_lang == 'fa') ? 'selected' : ''; ?>>Persian</option>
                        <option value="pl" <?php echo ($this->selected_lang->ckeditor_lang == 'pl') ? 'selected' : ''; ?>>Polish</option>
                        <option value="pt-br" <?php echo ($this->selected_lang->ckeditor_lang == 'pt-br') ? 'selected' : ''; ?>>Portuguese (Brazil)</option>
                        <option value="pt" <?php echo ($this->selected_lang->ckeditor_lang == 'pt') ? 'selected' : ''; ?>>Portuguese (Portugal)</option>
                        <option value="ro" <?php echo ($this->selected_lang->ckeditor_lang == 'ro') ? 'selected' : ''; ?>>Romanian</option>
                        <option value="ru" <?php echo ($this->selected_lang->ckeditor_lang == 'ru') ? 'selected' : ''; ?>>Russian</option>
                        <option value="sr" <?php echo ($this->selected_lang->ckeditor_lang == 'sr') ? 'selected' : ''; ?>>Serbian (Cyrillic)</option>
                        <option value="sr-latn" <?php echo ($this->selected_lang->ckeditor_lang == 'sr-latn') ? 'selected' : ''; ?>>Serbian (Latin)</option>
                        <option value="si" <?php echo ($this->selected_lang->ckeditor_lang == 'si') ? 'selected' : ''; ?>>Sinhala</option>
                        <option value="sk" <?php echo ($this->selected_lang->ckeditor_lang == 'sk') ? 'selected' : ''; ?>>Slovak</option>
                        <option value="sl" <?php echo ($this->selected_lang->ckeditor_lang == 'sl') ? 'selected' : ''; ?>>Slovenian</option>
                        <option value="es" <?php echo ($this->selected_lang->ckeditor_lang == 'es') ? 'selected' : ''; ?>>Spanish</option>
                        <option value="es-mx" <?php echo ($this->selected_lang->ckeditor_lang == 'es-mx') ? 'selected' : ''; ?>>Spanish (Mexico)</option>
                        <option value="sv" <?php echo ($this->selected_lang->ckeditor_lang == 'sv') ? 'selected' : ''; ?>>Swedish</option>
                        <option value="tt" <?php echo ($this->selected_lang->ckeditor_lang == 'tt') ? 'selected' : ''; ?>>Tatar</option>
                        <option value="th" <?php echo ($this->selected_lang->ckeditor_lang == 'th') ? 'selected' : ''; ?>>Thai</option>
                        <option value="tr" <?php echo ($this->selected_lang->ckeditor_lang == 'tr') ? 'selected' : ''; ?>>Turkish</option>
                        <option value="ug" <?php echo ($this->selected_lang->ckeditor_lang == 'ug') ? 'selected' : ''; ?>>Uighur</option>
                        <option value="uk" <?php echo ($this->selected_lang->ckeditor_lang == 'uk') ? 'selected' : ''; ?>>Ukrainian</option>
                        <option value="vi" <?php echo ($this->selected_lang->ckeditor_lang == 'vi') ? 'selected' : ''; ?>>Vietnamese</option>
                        <option value="cy" <?php echo ($this->selected_lang->ckeditor_lang == 'cy') ? 'selected' : ''; ?>>Welsh</option>
                    </select>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <label><?php echo trans('status'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="status" value="1" id="status1" class="square-purple" <?php echo ($language->status == "1") ? 'checked' : ''; ?>>&nbsp;&nbsp;
                            <label for="status1" class="option-label"><?php echo trans('active'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="status" value="0" id="status2" class="square-purple" <?php echo ($language->status != "1") ? 'checked' : ''; ?>>&nbsp;&nbsp;
                            <label for="status2" class="option-label"><?php echo trans('inactive'); ?></label>
                        </div>
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
