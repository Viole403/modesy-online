<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model
{
    //update profile
    public function update_profile($data, $user_id)
    {
        $user_id = clean_number($user_id);
        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        if (!empty($temp_path)) {
            //delete old avatar
            delete_file_from_server(user()->avatar);
            $data["avatar"] = $this->upload_model->avatar_upload($temp_path);
            $this->upload_model->delete_temp_image($temp_path);
        }
        $this->session->set_userdata('modesy_user_old_email', user()->email);

        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    //update shop settings
    public function update_shop_settings()
    {
        $user_id = user()->id;
        $data = array(
            'shop_name' => remove_special_characters($this->input->post('shop_name', true)),
            'about_me' => $this->input->post('about_me', true),
            'show_rss_feeds' => $this->input->post('show_rss_feeds', true),
            'send_email_when_item_sold' => $this->input->post('send_email_when_item_sold', true)
        );

        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    //check email updated
    public function check_email_updated($user_id)
    {
        $user_id = clean_number($user_id);
        if ($this->general_settings->email_verification == 1) {
            $user = $this->auth_model->get_user($user_id);
            if (!empty($user)) {
                if (!empty($this->session->userdata('modesy_user_old_email')) && $this->session->userdata('modesy_user_old_email') != $user->email) {
                    //send confirm email
                    $this->load->model("email_model");
                    $this->email_model->send_email_activation($user->id);
                    $data = array(
                        'email_status' => 0
                    );

                    $this->db->where('id', $user->id);
                    return $this->db->update('users', $data);
                }
            }
            if (!empty($this->session->userdata('modesy_user_old_email'))) {
                $this->session->unset_userdata('modesy_user_old_email');
            }
        }

        return false;
    }

    //update contact informations
    public function update_contact_informations()
    {
        $user_id = user()->id;
        $data = array(
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
            'city_id' => $this->input->post('city_id', true),
            'address' => $this->input->post('address', true),
            'zip_code' => $this->input->post('zip_code', true),
            'phone_number' => $this->input->post('phone_number', true),
            'show_email' => $this->input->post('show_email', true),
            'show_phone' => $this->input->post('show_phone', true),
            'show_location' => $this->input->post('show_location', true)
        );

        if (empty($data['show_email'])) {
            $data['show_email'] = 0;
        }
        if (empty($data['show_phone'])) {
            $data['show_phone'] = 0;
        }
        if (empty($data['show_location'])) {
            $data['show_location'] = 0;
        }

        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    //update shipping address
    public function update_shipping_address()
    {
        $user_id = user()->id;
        $data = array(
            'shipping_first_name' => $this->input->post('shipping_first_name', true),
            'shipping_last_name' => $this->input->post('shipping_last_name', true),
            'shipping_email' => $this->input->post('shipping_email', true),
            'shipping_phone_number' => $this->input->post('shipping_phone_number', true),
            'shipping_address_1' => $this->input->post('shipping_address_1', true),
            'shipping_address_2' => $this->input->post('shipping_address_2', true),
            'shipping_country_id' => $this->input->post('shipping_country_id', true),
            'shipping_state' => $this->input->post('shipping_state', true),
            'shipping_city' => $this->input->post('shipping_city', true),
            'shipping_zip_code' => $this->input->post('shipping_zip_code', true)
        );
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }


    //update update social media
    public function update_social_media()
    {
        $user_id = user()->id;
        $data = array(
            'facebook_url' => $this->input->post('facebook_url', true),
            'twitter_url' => $this->input->post('twitter_url', true),
            'instagram_url' => $this->input->post('instagram_url', true),
            'pinterest_url' => $this->input->post('pinterest_url', true),
            'linkedin_url' => $this->input->post('linkedin_url', true),
            'vk_url' => $this->input->post('vk_url', true),
            'youtube_url' => $this->input->post('youtube_url', true)
        );

        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    //change password input values
    public function change_password_input_values()
    {
        $data = array(
            'old_password' => $this->input->post('old_password', true),
            'password' => $this->input->post('password', true),
            'password_confirm' => $this->input->post('password_confirm', true)
        );
        return $data;
    }

    //change password
    public function change_password($old_password_exists)
    {
        $this->load->library('bcrypt');

        $user = user();
        if (!empty($user)) {
            $data = $this->change_password_input_values();
            if ($old_password_exists == 1) {
                //password does not match stored password.
                if (!$this->bcrypt->check_password($data['old_password'], $user->password)) {
                    $this->session->set_flashdata('error', trans("msg_wrong_old_password"));
                    $this->session->set_flashdata('form_data', $this->change_password_input_values());
                    redirect($this->agent->referrer());
                }
            }

            $data = array(
                'password' => $this->bcrypt->hash_password($data['password'])
            );

            $this->db->where('id', $user->id);
            return $this->db->update('users', $data);
        } else {
            return false;
        }
    }

    //follow user
    public function follow_unfollow_user()
    {
        $data = array(
            'following_id' => $this->input->post('following_id', true),
            'follower_id' => $this->input->post('follower_id', true)
        );

        $follow = $this->get_follow($data["following_id"], $data["follower_id"]);
        if (empty($follow)) {
            //add follower
            $this->db->insert('followers', $data);
        } else {
            $this->db->where('id', $follow->id);
            $this->db->delete('followers');
        }
    }

    //get user shipping address
    public function get_user_shipping_address($user_id)
    {
        $user_id = clean_number($user_id);
        $std = new stdClass();
        $std->shipping_first_name = "";
        $std->shipping_last_name = "";
        $std->shipping_email = "";
        $std->shipping_phone_number = "";
        $std->shipping_address_1 = "";
        $std->shipping_address_2 = "";
        $std->shipping_country_id = "";
        $std->shipping_state = "";
        $std->shipping_city = "";
        $std->shipping_zip_code = "";

        if (empty($user_id)) {
            return $std;
        }

        $row = get_user($user_id);
        if (!empty($row)) {
            return $row;
        } else {
            return $std;
        }
    }


    //follow
    public function get_follow($following_id, $follower_id)
    {
        $following_id = clean_number($following_id);
        $follower_id = clean_number($follower_id);
        $this->db->where('following_id', $following_id);
        $this->db->where('follower_id', $follower_id);
        $query = $this->db->get('followers');
        return $query->row();
    }

    //is user follows
    public function is_user_follows($following_id, $follower_id)
    {
        $following_id = clean_number($following_id);
        $follower_id = clean_number($follower_id);
        $follow = $this->get_follow($following_id, $follower_id);
        if (empty($follow)) {
            return false;
        } else {
            return true;
        }
    }

    //get followers
    public function get_followers($following_id)
    {
        $following_id = clean_number($following_id);
        $this->db->join('users', 'followers.follower_id = users.id');
        $this->db->select('users.*');
        $this->db->where('following_id', $following_id);
        $query = $this->db->get('followers');
        return $query->result();
    }

    //get followers count
    public function get_followers_count($following_id)
    {
        $following_id = clean_number($following_id);
        $this->db->join('users', 'followers.follower_id = users.id');
        $this->db->select('users.*');
        $this->db->where('following_id', $following_id);
        $query = $this->db->get('followers');
        return $query->num_rows();
    }

    //get following users
    public function get_following_users($follower_id)
    {
        $follower_id = clean_number($follower_id);
        $this->db->join('users', 'followers.following_id = users.id');
        $this->db->select('users.*');
        $this->db->where('follower_id', $follower_id);
        $query = $this->db->get('followers');
        return $query->result();
    }

    //get following users
    public function get_following_users_count($follower_id)
    {
        $follower_id = clean_number($follower_id);
        $this->db->join('users', 'followers.following_id = users.id');
        $this->db->select('users.*');
        $this->db->where('follower_id', $follower_id);
        $query = $this->db->get('followers');
        return $query->num_rows();
    }

    //search members
    public function search_members($search)
    {
        $search = remove_special_characters($search);
        $this->db->like('users.username', $search);
        $this->db->or_like('users.shop_name', $search);
        $query = $this->db->get('users');
        return $query->result();
    }

    //search members limited
    public function search_members_limited($search)
    {
        $search = remove_special_characters($search);
        $this->db->like('users.username', $search);
        $this->db->limit(8);
        $query = $this->db->get('users');
        return $query->result();
    }
}
