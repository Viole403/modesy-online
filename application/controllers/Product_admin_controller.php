<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_admin_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check user
        if (!is_admin()) {
            redirect(admin_url() . 'login');
        }
    }

    /**
     * Products
     */
    public function products()
    {
        $data['title'] = trans("products");
        $data['form_action'] = admin_url() . "products";
        $data['list_type'] = "products";
        //get paginated products
        $pagination = $this->paginate(admin_url() . 'products', $this->product_admin_model->get_paginated_products_count('products'));
        $data['products'] = $this->product_admin_model->get_paginated_products($pagination['per_page'], $pagination['offset'], 'products');
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/products', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Pending Products
     */
    public function pending_products()
    {
        $data['title'] = trans("pending_products");
        $data['form_action'] = admin_url() . "pending-products";
        $data['list_type'] = "pending_products";
        //get paginated pending products
        $pagination = $this->paginate(admin_url() . 'pending-products', $this->product_admin_model->get_paginated_pending_products_count('pending_products'));
        $data['products'] = $this->product_admin_model->get_paginated_pending_products($pagination['per_page'], $pagination['offset'], 'pending_products');
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/pending_products', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Hidden Products
     */
    public function hidden_products()
    {
        $data['title'] = trans("hidden_products");
        $data['form_action'] = admin_url() . "hidden-products";
        $data['list_type'] = "hidden_products";
        //get paginated products
        $pagination = $this->paginate(admin_url() . 'hidden-products', $this->product_admin_model->get_paginated_hidden_products_count('hidden_products'));
        $data['products'] = $this->product_admin_model->get_paginated_hidden_products($pagination['per_page'], $pagination['offset'], 'hidden_products');
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/products', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Sold Products
     */
    public function sold_products()
    {
        $data['title'] = trans("sold_products");
        $data['form_action'] = admin_url() . "sold-products";
        $data['list_type'] = "sold_products";
        //get paginated products
        $pagination = $this->paginate(admin_url() . 'sold-products', $this->product_admin_model->get_sold_products_count('sold_products'));
        $data['products'] = $this->product_admin_model->get_paginated_sold_products($pagination['per_page'], $pagination['offset'], 'sold_products');
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/products', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Draft
     */
    public function drafts()
    {
        $data['title'] = trans("drafts");
        $data['form_action'] = admin_url() . "drafts";
        $data['list_type'] = "drafts";
        //get paginated drafts
        $pagination = $this->paginate(admin_url() . 'drafts', $this->product_admin_model->get_paginated_drafts_count('drafts'));
        $data['products'] = $this->product_admin_model->get_paginated_drafts($pagination['per_page'], $pagination['offset'], 'drafts');
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/drafts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Deleted Products
     */
    public function deleted_products()
    {
        $data['title'] = trans("deleted_products");
        $data['form_action'] = admin_url() . "deleted-products";
        $data['list_type'] = "deleted_products";
        //get paginated products
        $pagination = $this->paginate(admin_url() . 'deleted-products', $this->product_admin_model->get_paginated_deleted_products_count('deleted_products'));
        $data['products'] = $this->product_admin_model->get_paginated_deleted_products($pagination['per_page'], $pagination['offset'], 'deleted_products');
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/deleted_products', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Promoted Products
     */
    public function promoted_products()
    {
        $data['title'] = trans("promoted_products");
        $data['form_action'] = admin_url() . "promoted-products";
        $data['list_type'] = "promoted_products";
        //get paginated promoted products
        $pagination = $this->paginate(admin_url() . 'promoted-products', $this->product_admin_model->get_paginated_promoted_products_count('promoted_products'));
        $data['products'] = $this->product_admin_model->get_paginated_promoted_products($pagination['per_page'], $pagination['offset'], 'promoted_products');
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/promoted/promoted_products', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Promoted Products Pricing
     */
    public function promoted_products_pricing()
    {
        $data['title'] = trans("pricing");
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/promoted/pricing', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Promoted Products Pricing Post
     */
    public function promoted_products_pricing_post()
    {
        if ($this->settings_model->update_pricing_settings()) {
            $this->session->set_flashdata('success', trans("msg_updated"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Promoted Products Transactions
     */
    public function promoted_products_transactions()
    {
        $data['title'] = trans("promoted_products_transactions");
        $data['form_action'] = admin_url() . "promoted-products-transactions";

        $pagination = $this->paginate(admin_url() . 'promoted-products-transactions', $this->transaction_model->get_promoted_transactions_count());
        $data['transactions'] = $this->transaction_model->get_paginated_promoted_transactions($pagination['per_page'], $pagination['offset']);
        

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/promoted/transactions', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Delete Promoted Transaction Post
     */
    public function delete_promoted_transaction_post()
    {
        $id = $this->input->post('id', true);
        if ($this->transaction_model->delete_promoted_transaction($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Product Details
     */
    public function product_details($id)
    {
        $data['title'] = trans("product_details");
        $data['product'] = $this->product_admin_model->get_product($id);
        if (empty($data['product'])) {
            redirect($this->agent->referrer());
        }
        

        $data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/product_details', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Approve Product
     */
    public function approve_product()
    {
        $id = $this->input->post('id', true);
        if ($this->product_admin_model->approve_product($id)) {
            $this->session->set_flashdata('success', trans("msg_product_approved"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }

        //reset cache
        reset_cache_data_on_change();

        $redirect_url = $this->input->post('redirect_url', true);
        if (!empty($redirect_url)) {
            redirect($redirect_url);
        }
    }

    /**
     * Restore Product
     */
    public function restore_product()
    {
        $id = $this->input->post('id', true);
        if ($this->product_admin_model->restore_product($id)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        //reset cache
        reset_cache_data_on_change();
    }

    /**
     * Delete Product
     */
    public function delete_product()
    {
        $id = $this->input->post('id', true);
        if ($this->product_admin_model->delete_product($id)) {
            $this->session->set_flashdata('success', trans("msg_product_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }

        //reset cache
        reset_cache_data_on_change();
    }

    /**
     * Delete Product Permanently
     */
    public function delete_product_permanently()
    {
        $id = $this->input->post('id', true);
        if ($this->product_admin_model->delete_product_permanently($id)) {
            $this->session->set_flashdata('success', trans("msg_product_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }

        //reset cache
        reset_cache_data_on_change();
    }

    /**
     * Delete Selected Products
     */
    public function delete_selected_products()
    {
        $product_ids = $this->input->post('product_ids', true);
        $this->product_admin_model->delete_multi_products($product_ids);

        //reset cache
        reset_cache_data_on_change();
    }

    /**
     * Delete Selected Products Permanently
     */
    public function delete_selected_products_permanently()
    {
        $product_ids = $this->input->post('product_ids', true);
        $this->product_admin_model->delete_multi_products_permanently($product_ids);

        //reset cache
        reset_cache_data_on_change();
    }

    /**
     * Add Remove Promoted Products
     */
    public function add_remove_promoted_products()
    {
        $product_id = $this->input->post('product_id', true);
        $day_count = $this->input->post('day_count', true);
        $is_ajax = $this->input->post('is_ajax', true);
        if ($this->product_admin_model->add_remove_promoted_products($product_id, $day_count)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }

        //reset cache
        reset_cache_data_on_change();

        if ($is_ajax == 0) {
            redirect($this->agent->referrer());
        }
    }

    /**
     * Comments
     */
    public function comments()
    {
        $data['title'] = trans("comments");
        $data['comments'] = $this->comment_model->get_all_comments();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/product/comments', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Delete Comment
     */
    public function delete_comment()
    {
        $id = $this->input->post('id', true);
        if ($this->comment_model->delete_comment($id)) {
            $this->session->set_flashdata('success', trans("msg_comment_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Delete Selected Comments
     */
    public function delete_selected_comments()
    {
        $comment_ids = $this->input->post('comment_ids', true);
        $this->comment_model->delete_multi_comments($comment_ids);
    }

    /**
     * Reviews
     */
    public function product_reviews()
    {
        $data['title'] = trans("reviews");
        $data['reviews'] = $this->review_model->get_all_reviews();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/review/product_reviews', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Delete Review
     */
    public function delete_review()
    {
        $id = $this->input->post('id', true);
        if ($this->review_model->delete_review($id)) {
            $this->session->set_flashdata('success', trans("msg_review_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Delete Selected Reviews
     */
    public function delete_selected_reviews()
    {
        $review_ids = $this->input->post('review_ids', true);
        $this->review_model->delete_multi_reviews($review_ids);
    }

}