<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promote_model extends CI_Model
{
    //execute promote payment
    public function execute_promote_payment($data_transaction)
    {
		$promoted_plan = $this->session->userdata('modesy_selected_promoted_plan');
        $data = array(
            'payment_method' => $data_transaction["payment_method"],
            'payment_id' => $data_transaction["payment_id"],
            'user_id' => user()->id,
            'product_id' => $promoted_plan->product_id,
            'currency' => $data_transaction["currency"],
            'payment_amount' =>$data_transaction["payment_amount"],
            'payment_status' => $data_transaction["payment_status"],
            'purchased_plan' => $promoted_plan->purchased_plan,
            'day_count' => $promoted_plan->day_count,
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }
        $this->db->insert('promoted_transactions', $data);
    }

    //execute promote payment bank
    public function execute_promote_payment_bank($promoted_plan)
    {
        $data = array(
            'payment_method' => "Bank Transfer",
            'payment_id' => $this->input->post('payment_id', true),
            'user_id' => user()->id,
            'product_id' => $promoted_plan->product_id,
            'currency' => $this->payment_settings->promoted_products_payment_currency,
            'payment_amount' => price_format_decimal($promoted_plan->total_amount),
            'payment_status' => "awaiting_payment",
            'purchased_plan' => $promoted_plan->purchased_plan,
            'day_count' => $promoted_plan->day_count,
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }
        $this->db->insert('promoted_transactions', $data);
    }

    //add to promoted products
    public function add_to_promoted_products($promoted_plan)
    {
        $product = $this->product_model->get_product_by_id($promoted_plan->product_id);
        if (!empty($product)) {
            //set dates
            $date = date('Y-m-d H:i:s');
            $end_date = date('Y-m-d H:i:s', strtotime($date . ' + ' . $promoted_plan->day_count . ' days'));
            $data = array(
                'promote_plan' => $promoted_plan->purchased_plan,
                'promote_day' => $promoted_plan->day_count,
                'is_promoted' => 1,
                'promote_start_date' => $date,
                'promote_end_date' => $end_date
            );
            $this->db->where('id', $promoted_plan->product_id);
            return $this->db->update('products', $data);
        }
        return false;
    }

}
