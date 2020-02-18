<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency_model extends CI_Model
{
    //add currency
    public function add_currency()
    {
        $data = array(
            'code' => $this->input->post('code', true),
            'name' => $this->input->post('name', true),
            'symbol' => $this->input->post('symbol', true),
            'hex' => $this->input->post('hex', true),
        );

        return $this->db->insert('currencies', $data);
    }

    //update currency
    public function update_currency($id)
    {
        $id = clean_number($id);
        $data = array(
            'code' => $this->input->post('code', true),
            'name' => $this->input->post('name', true),
            'symbol' => $this->input->post('symbol', true),
            'hex' => $this->input->post('hex', true),
        );

        $this->db->where('id', $id);
        return $this->db->update('currencies', $data);
    }

    //get currencies
    public function get_currencies()
    {
        $query = $this->db->get('currencies');
        return $query->result();
    }

    //get currency
    public function get_currency($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('currencies');
        return $query->row();
    }

    //get currency by code
    public function get_currency_by_code($code)
    {
        $this->db->where('code', $code);
        $query = $this->db->get('currencies');
        return $query->row();
    }

    //update currency settings
    public function update_currency_settings()
    {
        $data = array(
            'default_product_currency' => $this->input->post('default_product_currency', true),
            'allow_all_currencies_for_classied' => $this->input->post('allow_all_currencies_for_classied', true),
            'promoted_products_payment_currency' => $this->input->post('promoted_products_payment_currency', true),
            'currency_format' => $this->input->post('currency_format', true),
            'currency_symbol_format' => $this->input->post('currency_symbol_format', true)
        );

        $this->db->where('id', 1);
        return $this->db->update('payment_settings', $data);
    }

    //delete currency
    public function delete_currency($id)
    {
        $id = clean_number($id);
        $currency = $this->get_currency($id);
        if (!empty($currency)) {
            $this->db->where('id', $id);
            return $this->db->delete('currencies');
        }
        return false;
    }

}