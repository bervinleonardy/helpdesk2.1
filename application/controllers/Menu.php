<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
        $this->load->model('Modelmenu', 'menu');
        $this->load->model('Modelsubmenu', 'submenu');
    }

    // Begin Modul Menu //

    public function index()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Menu Management';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('menu/content', $data, TRUE),
                'modals'    => $this->load->view('menu/modals', $data, TRUE),
                'script'    => $this->load->view('menu/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('menu/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Menu Added !</div>');
            redirect('menu');
        }
    }

    public function getData()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->menu->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = array();

                if ($field->is_active == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                } else {
                    $status = '<span class="badge badge-danger">Inactive</span>';
                }

                // Membuat tombol
                $tomboledit = "
                <button type=\"button\" class=\"btn btn-outline-info\" title=\"Edit Data\" onclick=\"edit('" . $field->id . "')\">
                    <i class=\"far fa-edit\"></i>
                </button>";
                $tombolhapus = "
                <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->menu . "')\">
                    <i class=\"far fa-trash-alt\"></i>
                </button>";

                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $no;
                $row[] = $field->menu;
                $row[] = $field->urutan;
                $row[] = $status;
                $row[] = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->menu->count_all(),
                "recordsFiltered" => $this->menu->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambah()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $msg = [
                'sukses' => $this->load->view('menu/modaltambah', '', TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $menu       = ucwords($this->input->post('menu', TRUE));
            $urutan     = $this->input->post('urutan', TRUE);
            $createdBy  = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'menu',
                'Menu',
                'required|trim|is_unique[user_menu.menu]',
                [
                    'required'  => '%s tidak boleh kosong',
                    'is_unique' => '%s sudah ada'
                ]
            );

            $this->form_validation->set_rules(
                'urutan',
                'Urutan',
                'required|trim|is_unique[user_menu.urutan]',
                [
                    'required'  => '%s tidak boleh kosong',
                    'is_unique' => '%s sudah ada'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->menu->simpan($menu, $urutan, $createdBy);

                $msg = [
                    'sukses' => 'Menu has been saved successfully !'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>' . validation_errors() . '</strong> 
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function formedit()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->menu->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'        => $id,
                    'menu'      => $row['menu'],
                    'urutan'    => $row['urutan'],
                    'is_active' => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('menu/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $menu           = $this->input->post('menu', TRUE);
            $urutan           = $this->input->post('urutan', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->menu->update($id, $menu, $urutan, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'Menu has been updated successfully !'
            ];

            echo json_encode($msg);
        }
    }

    public function hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->menu->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Menu deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiple()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->menu->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data menu deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Menu //

    // public function submenu()
    // {
    //     $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
    //     $data['title']   = 'Submenu Management';
    //     $this->load->model('Modelmenu', 'a');

    //     $data['subMenu'] = $this->a->getSubMenu();
    //     $data['menu']    = $this->db->get('user_menu')->result_array();

    //     $this->form_validation->set_rules('title', 'Title', 'required');
    //     $this->form_validation->set_rules('menu_id', 'Menu', 'required');
    //     $this->form_validation->set_rules('url', 'URL', 'required');
    //     $this->form_validation->set_rules('icon', 'Icon', 'required');

    //     if ($this->form_validation->run() == FALSE) {
    //         $template        = array(
    //             'header'    => $this->load->view('templates/header', $data, TRUE),
    //             'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
    //             'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
    //             'content'   => $this->load->view('submenu/content', $data, TRUE),
    //             'modals'    => $this->load->view('submenu/modals', $data, TRUE),
    //             'script'    => $this->load->view('submenu/script', '', TRUE),
    //             'footer'    => $this->load->view('templates/footer', '', TRUE),
    //         );
    //         $this->parser->parse('submenu/index', $template);
    //     } else {
    //         $data = [
    //             'title'     => $this->input->post('title'),
    //             'menu_id'   => $this->input->post('menu_id'),
    //             'url'       => $this->input->post('url'),
    //             'icon'      => $this->input->post('icon'),
    //             'is_active' => $this->input->post('is_active')
    //         ];
    //         $this->db->insert('user_sub_menu', $data);
    //         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Submenu Added !</div>');
    //         redirect('menu/submenu ');
    //     }
    // }

    // Modul Submenu 

    public function submenu()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Submenu Management';

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('submenu/content', $data, TRUE),
                'modals'    => $this->load->view('submenu/modals', $data, TRUE),
                'script'    => $this->load->view('submenu/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('submenu/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Menu Added !</div>');
            redirect('user_sub_menu');
        }
    }

    public function getDataSubmenu()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->submenu->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = array();

                if ($field->is_active == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                } else {
                    $status = '<span class="badge badge-danger">Inactive</span>';
                }

                // Membuat tombol
                $tomboledit = "
                <button type=\"button\" class=\"btn btn-outline-info\" title=\"Edit Data\" onclick=\"edit('" . $field->id . "')\">
                    <i class=\"far fa-edit\"></i>
                </button>";
                $tombolhapus = "
                <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->title . "')\">
                    <i class=\"far fa-trash-alt\"></i>
                </button>";

                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $no;
                $row[] = $field->title;
                $row[] = $field->menu;
                $row[] = $field->urutan;
                $row[] = $status;
                $row[] = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->submenu->count_all(),
                "recordsFiltered" => $this->submenu->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahsubmenu()
    {
        if ($this->input->is_ajax_request() == TRUE) {

            $data['menu']    = $this->db->get_where('user_menu', ['is_active' => 1])->result_array();
            $msg = [
                'sukses' => $this->load->view('submenu/modaltambah', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatasubmenu()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $title      = $this->input->post('title');
            $menu       = $this->input->post('menu');
            $url        = $this->input->post('url');
            $icon       = $this->input->post('icon');
            $is_active  = $this->input->post('is_active');
            $urutan     = $this->input->post('urutan', TRUE);
            $createdBy  = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'title',
                'Submenu',
                'required|trim|is_unique[user_menu.menu]',
                [
                    'required'  => '%s tidak boleh kosong',
                    'is_unique' => '%s sudah ada'
                ]
            );

            $this->form_validation->set_rules(
                'menu',
                'Menu',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'url',
                'URL',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'urutan',
                'Urutan',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->submenu->simpan($title, $menu, $url, $icon, $is_active, $urutan, $createdBy);

                $msg = [
                    'sukses' => 'Submenu has been saved successfully !'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>' . validation_errors() . '</strong> 
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function formeditsubmenu()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->submenu->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'        => $id,
                    'title'     => $row['title'],
                    'menu_id'   => $row['menu_id'],
                    'menu'      => $this->db->get_where('user_menu', ['is_active' => 1])->result_array(),
                    'url'       => $row['url'],
                    'icon'      => $row['icon'],
                    'urutan'    => $row['urutan'],
                    'is_active' => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('submenu/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatasubmenu()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $title          = $this->input->post('title', TRUE);
            $menu           = $this->input->post('menu', TRUE);
            $url            = $this->input->post('url', TRUE);
            $icon           = $this->input->post('icon', TRUE);
            $urutan         = $this->input->post('urutan', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->submenu->update($id, $title, $menu, $url, $icon, $urutan, $is_active, $modifiedBy, $modifiedDate);
            $msg = [
                'sukses' => 'Menu has been updated successfully !'
            ];

            echo json_encode($msg);
        }
    }

    public function hapussubmenu()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->submenu->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Submenu deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiplesubmenu()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->submenu->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data submenu deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End of Module submenu 
}
