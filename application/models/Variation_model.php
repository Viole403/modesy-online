<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Variation_model extends CI_Model
{
    //add variation
    public function add_variation()
    {
        $product_id = $this->input->post('product_id', true);
        $user_id = $this->auth_user->id;
        $common_id = 'vr' . $user_id . $product_id . '-' . generate_short_unique_id();
        foreach ($this->languages as $language) {
            $label = $this->input->post('label_lang_' . $language->id, true);
            if (empty($label)) {
                $label = $this->input->post('label_lang_' . $this->selected_lang->id, true);
            }
            $data = array(
                'common_id' => $common_id,
                'product_id' => $product_id,
                'lang_id' => $language->id,
                'user_id' => $user_id,
                'label' => $label,
                'variation_type' => $this->input->post('variation_type', true),
                'insert_type' => 'new',
                'visible' => $this->input->post('visible', true)
            );
            $this->db->insert('product_variations', $data);
        }
    }

    //add variation ootion
    public function add_variation_option($variation_common_id, $lang_id)
    {
        $main_variation = $this->get_variation($variation_common_id, $lang_id);
        if (!empty($main_variation)) {
            $option_common_id = 'op' . $this->auth_user->id . '-' . generate_short_unique_id();
            $i = 0;
            foreach ($this->languages as $language) {
                $sub_variation = $this->get_variation($variation_common_id, $language->id);
                if (!empty($sub_variation)) {
                    $option_text = remove_special_characters($this->input->post('option_text_' . $language->id, true));
                    if (empty($option_text)) {
                        $option_text = remove_special_characters($this->input->post('option_text_' . $this->selected_lang->id, true));
                    }
                    $data = array(
                        'variation_common_id' => $variation_common_id,
                        'option_common_id' => $option_common_id,
                        'option_text' => $option_text,
                        'lang_id' => $language->id,
                        'available_in_stock' => $this->input->post('available_in_stock', true),
                        'option_index' => $i
                    );
                    $this->db->insert('product_variations_options', $data);
                    $i++;
                }
            }
        }
    }

    //edit variation
    public function edit_variation($common_id)
    {
        foreach ($this->languages as $language) {
            $label = $this->input->post('label_lang_' . $language->id, true);
            if (empty($label)) {
                $label = $this->input->post('label_lang_' . $this->selected_lang->id, true);
            }
            $data = array(
                'label' => $label,
                'variation_type' => $this->input->post('variation_type', true),
                'visible' => $this->input->post('visible', true)
            );
            $this->db->where('common_id', $common_id);
            $this->db->where('lang_id', $language->id);
            $this->db->update('product_variations', $data);
        }
    }

    //edit variation ootions
    public function edit_variation_options($variation_common_id)
    {
        $options = $this->get_variation_options_by_variation_common_id($variation_common_id);
        if (!empty($options)) {
            foreach ($options as $option) {
                $data = array(
                    'option_text' => $this->input->post('option_text_' . $option->id, true),
                    'available_in_stock' => $this->input->post('available_in_stock_' . $option->option_common_id, true)
                );
                $this->db->where('id', $option->id);
                $this->db->update('product_variations_options', $data);
            }
        }
    }

    //select variation
    public function select_variation($variation_common_id, $product_id)
    {
        $product_id = clean_number($product_id);
        $variations = $this->get_variation_by_common_id($variation_common_id);
        $user_id = $this->auth_user->id;
        //copy variations
        $new_common_id = 'vr' . $user_id . $product_id . '-' . generate_short_unique_id();
        if (!empty($variations)) {
            foreach ($variations as $variation) {
                $data = array(
                    'common_id' => $new_common_id,
                    'product_id' => $product_id,
                    'lang_id' => $variation->lang_id,
                    'user_id' => $variation->user_id,
                    'label' => $variation->label,
                    'variation_type' => $variation->variation_type,
                    'insert_type' => 'copy',
                    'visible' => $variation->visible
                );
                $this->db->insert('product_variations', $data);
            }
        }

        //copy options
        $main_variation = $this->variation_model->get_variation($variation_common_id, $this->selected_lang->id);
        if (!empty($main_variation)) {
            $main_options = $this->get_variation_options($variation_common_id, $main_variation->lang_id);
            if (!empty($main_options)) {
                foreach ($main_options as $main_option) {
                    $options = $this->get_variation_options_by_option_common_id($main_option->option_common_id);
                    if (!empty($options)) {
                        $option_common_id = 'op' . $this->auth_user->id . '-' . generate_short_unique_id();
                        foreach ($options as $option) {
                            $data = array(
                                'variation_common_id' => $new_common_id,
                                'option_common_id' => $option_common_id,
                                'option_text' => $option->option_text,
                                'lang_id' => $option->lang_id,
                                'available_in_stock' => $option->available_in_stock,
                                'option_index' => $option->option_index,
                            );
                            $this->db->insert('product_variations_options', $data);
                        }
                    }
                }
            }
        }
    }

    //get variation
    public function get_variation($common_id, $lang_id)
    {
        $lang_id = clean_number($lang_id);
        $this->db->where('common_id', $common_id);
        $this->db->where('lang_id', $lang_id);
        $query = $this->db->get('product_variations');
        return $query->row();
    }

    //get variation by common id
    public function get_variation_by_common_id($common_id)
    {
        $this->db->where('common_id', $common_id);
        $query = $this->db->get('product_variations');
        return $query->result();
    }

    //get variation by user id
    public function get_variation_by_user_id($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('product_variations');
        return $query->result();
    }

    //get product variations
    public function get_product_variations($product_id)
    {
        $product_id = clean_number($product_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('product_variations');
        return $query->result();
    }

    //get product variations by lang
    public function get_product_variations_by_lang($product_id, $lang_id)
    {
        $product_id = clean_number($product_id);
        $lang_id = clean_number($lang_id);
        $this->db->where('product_id', $product_id);
        $this->db->where('lang_id', $lang_id);
        $this->db->where('visible', 1);
        $query = $this->db->get('product_variations');
        return $query->result();
    }

    //get half width product variations
    public function get_half_width_product_variations($product_id, $lang_id)
    {
        $product_id = clean_number($product_id);
        $lang_id = clean_number($lang_id);
        $this->db->where('product_id', $product_id);
        $this->db->where('lang_id', $lang_id);
        $this->db->where('visible', 1);
        $this->db->group_start();
        $this->db->where('variation_type', 'text');
        $this->db->or_where('variation_type', 'number');
        $this->db->or_where('variation_type', 'dropdown');
        $this->db->group_end();
        $query = $this->db->get('product_variations');
        return $query->result();
    }

    //get full width product variations
    public function get_full_width_product_variations($product_id, $lang_id)
    {
        $product_id = clean_number($product_id);
        $lang_id = clean_number($lang_id);
        $this->db->where('product_id', $product_id);
        $this->db->where('lang_id', $lang_id);
        $this->db->where('visible', 1);
        $this->db->group_start();
        $this->db->where('variation_type', 'checkbox');
        $this->db->or_where('variation_type', 'radio_button');
        $this->db->group_end();
        $query = $this->db->get('product_variations');
        return $query->result();
    }

    //get variation options
    public function get_variation_options($variation_common_id, $lang_id)
    {
        $lang_id = clean_number($lang_id);
        $this->db->where('variation_common_id', $variation_common_id);
        $this->db->where('lang_id', $lang_id);
        $query = $this->db->get('product_variations_options');
        return $query->result();
    }

    //get variation options by variation common id
    public function get_variation_options_by_variation_common_id($variation_common_id)
    {
        $this->db->where('variation_common_id', $variation_common_id);
        $query = $this->db->get('product_variations_options');
        return $query->result();
    }

    //get variation options by option common id
    public function get_variation_options_by_option_common_id($option_common_id)
    {
        $this->db->where('option_common_id', $option_common_id);
        $query = $this->db->get('product_variations_options');
        return $query->result();
    }

    //get variation option by option common id
    public function get_variation_option_by_option_common_id($option_common_id, $lang_id)
    {
        $lang_id = clean_number($lang_id);
        $this->db->where('option_common_id', $option_common_id);
        $this->db->where('lang_id', $lang_id);
        $query = $this->db->get('product_variations_options');
        return $query->row();
    }

    //delete variation
    public function delete_variation($common_id)
    {
        $variation = $this->get_variation_by_common_id($common_id);
        if (!empty($variation)) {
            foreach ($variation as $item):
                if (!empty($item->id)) {
                    $this->db->where('id', $item->id);
                    if ($this->db->delete('product_variations')) {
                        $this->delete_variation_options_by_variation_common_id($common_id);
                    }
                }
            endforeach;
        }
        return false;
    }

    //delete variation options by variation common id
    public function delete_variation_options_by_variation_common_id($variation_common_id)
    {
        $options = $this->get_variation_options_by_variation_common_id($variation_common_id);
        if (!empty($options)) {
            foreach ($options as $item):
                if (!empty($item->id)) {
                    $this->db->where('id', $item->id);
                    $this->db->delete('product_variations_options');
                }
            endforeach;
        }
    }

    //delete variation option
    public function delete_variation_option($option_common_id)
    {
        $options = $this->get_variation_options_by_option_common_id($option_common_id);
        if (!empty($options)) {
            foreach ($options as $item):
                if (!empty($item->id)) {
                    $this->db->where('id', $item->id);
                    $this->db->delete('product_variations_options');
                }
            endforeach;
        }
    }

}