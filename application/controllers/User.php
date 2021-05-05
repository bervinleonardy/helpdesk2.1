<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
    }

    public function index()
    {
        $data['user']       = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']      = 'My Profile';
        $template           = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('user/content', $data, TRUE),
            'modals'    => $this->load->view('templates/modals', $data, TRUE),
            'script'    => $this->load->view('user/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('user/index', $template);
    }

    public function resizeImage($new_image)
    {
        $source_path = './assets/img/profile/' . $new_image;
        $target_path = './assets/img/profile/thumbnail/';

		// var_dump(explode('Array', explode('.jpg', $new_image)));die();

        $config_manip = array(
            'overwrite'         => TRUE,
            'source_image'      => $source_path,
            'new_image'         => $target_path,
            'maintain_ratio'    => TRUE,
            'create_thumb'      => TRUE,
            'thumb_marker'      => explode('Array', explode('.jpg', $new_image)),
            'width'             => 200,
            'height'            => 200
        );

        $this->load->library('image_lib', $config_manip);

        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        $this->image_lib->clear();
    }

    public function edit()
    {
        $data['user']       = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']      = 'Edit Profile';

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $template           = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('user/content-edit', $data, TRUE),
                'modals'    => $this->load->view('templates/modals', $data, TRUE),
                'script'    => $this->load->view('user/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('user/index', $template);
        } else {
            $name   = $this->input->post('name');
            $nik    = $this->input->post('nik');
            $email  = $this->input->post('email');

            // jika ada gambar yang diupload
            $uploadImage = $_FILES['image']['name'];
            $uploadSignature = $_FILES['signature']['name'];

            if ($uploadImage) {
                $config['allowed_types']    = 'gif|jpg|png';
                $config['file_name']        = $nik;
                $config['overwrite']        = TRUE;
                $config['max_size']         = 2048;
                $config['upload_path']      = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $new_image = $this->upload->data('file_name');
                    $this->resizeImage($new_image);
                    $this->db->set('image', $new_image);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            if ($uploadSignature) {
                $config2['allowed_types']    = 'gif|jpg|png';
                $config2['file_name']        = $nik;
                $config2['overwrite']        = TRUE;
                $config2['max_size']         = 2048;
                $config2['upload_path']      = './assets/img/signature/';

                $this->load->library('upload', $config2);

                if ($this->upload->do_upload('signature')) {
                    $new_image1 = $this->upload->data('file_name');
                    $this->db->set('signature', $new_image1);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your Profile has been updated !</div>');
            redirect('user');
        }
    }

    public function changepassword()
    {
        $data['user']       = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']      = 'Change Password';

        $this->form_validation->set_rules('currentPassword', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('newPassword1', 'New Password', 'required|trim|min_length[6]|matches[newPassword2]');
        $this->form_validation->set_rules('newPassword2', 'Confirm New Password', 'required|trim|min_length[6]|matches[newPassword1]');

        if ($this->form_validation->run() == FALSE) {
            $template           = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('user/content-change-password', $data, TRUE),
                'modals'    => $this->load->view('templates/modals', $data, TRUE),
                'script'    => $this->load->view('user/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('user/index', $template);
        } else {
            $currentPassword = $this->input->post('currentPassword');
            $newPassword = $this->input->post('newPassword1');
            if (!password_verify($currentPassword, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong Current Password !</div>');
                redirect('user/changepassword');
            } else {
                if ($currentPassword == $newPassword) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New Password cannot be the same as current password !</div>');
                    redirect('user/changepassword');
                } else {
                    //password ok
                    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

                    $this->db->set('password', $passwordHash);
                    $this->db->where('nik', $this->session->userdata('nik'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password Change Successfully !</div>');
                    redirect('user/changepassword');
                }
            }
        }
    }
}
