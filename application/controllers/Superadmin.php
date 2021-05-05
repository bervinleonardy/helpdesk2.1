<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
        $this->load->model('Modelrole', 'role');
        $this->load->model('Modelmenu', 'menu');
        $this->load->model('Modelseverity', 'severity');
        $this->load->model('Modelmanager', 'manager');
        $this->load->model('Modeldepartment', 'department');
        $this->load->model('Modelsite', 'site');
    }

    // Begin Modul Role //

    public function role()
    {
        $data['title']   = 'Role';
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('role/content', $data, TRUE),
            'modals'    => $this->load->view('role/modals', $data, TRUE),
            'script'    => $this->load->view('role/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('role/index', $template);
    }

    public function getDataRole()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->role->get_datatables();
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
                $tombolaccess = "
                <button type=\"button\" class=\"btn btn-outline-warning\" title=\"Access\" onclick=\"access('" . $field->id . "')\">
                    <i class=\"fas fa-exclamation-triangle\"></i>
                </button>
                ";
                $tomboledit = "
                <button type=\"button\" class=\"btn btn-outline-info\" title=\"Edit Data\" onclick=\"edit('" . $field->id . "')\">
                    <i class=\"far fa-edit\"></i>
                </button>
                ";
                $tombolhapus = "
                <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->role . "')\">
                    <i class=\"far fa-trash-alt\"></i>
                </button>
                ";

                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $no;
                $row[] = $field->role;
                $row[] = date('d F Y', strtotime($field->created_date));
                $row[] = $status;
                $row[] = $tombolaccess . ' ' . $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->role->count_all(),
                "recordsFiltered" => $this->role->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahrole()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $msg = [
                'sukses' => $this->load->view('role/modaltambah', '', TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatarole()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $role       = ucwords($this->input->post('role', TRUE));
            $createdBy  = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'role',
                'Role',
                'required|trim|is_unique[user_role.role]',
                [
                    'required'  => '%s tidak boleh kosong',
                    'is_unique' => '%s sudah ada'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->role->simpan($role, $createdBy);

                $msg = [
                    'sukses' => 'data role berhasil disimpan'
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

    public function formeditrole()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->role->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'        => $id,
                    'role'      => $row['role'],
                    'is_active' => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('role/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function formaccess()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);
            $ambildata = $this->role->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();

                $data = [
                    'id'        => $id,
                    'role'      => $this->db->get_where('user_role', ['id' => $id])->row_array(),
                    'menu'      => $this->menu->getMenu(),
                    'is_active' => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('role/modalaccess', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatarole()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $role           = $this->input->post('role', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->role->update($id, $role, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'data role berhasil di-Update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusrole()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->role->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Role deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiplerole()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->role->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data role deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    public function roleAccess($role_id)
    {
        $this->db->where('id !=', 1);

		$data	= [
			'menu'	=> $this->db->get('user_menu')->result_array(),
			'title' => 'Role Access',
			'user' 	=> $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
			'role' 	=> $this->db->get_where('user_role', ['id' => $role_id])->row_array(),
		];

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('role/content-role-access', $data, TRUE),
            'modals'    => $this->load->view('role/modals', $data, TRUE),
            'script'    => $this->load->view('role/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('role/index', $template);
    }

    public function change_access()
    {
        $menuId = $this->input->post('menuId');
        $roleId = $this->input->post('roleId');

        $data = [
            'role_id' => $roleId,
            'menu_id' => $menuId
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }
    }

    // End Modul Role //

    // Begin Modul Severity //

    public function severity()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Severity';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('cases', 'Case', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('severity/content', $data, TRUE),
                'modals'    => $this->load->view('severity/modals', $data, TRUE),
                'script'    => $this->load->view('severity/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('severity/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Severity Added !</div>');
            redirect('menu');
        }
    }

    public function getDataSeverity()
    {
        if ($this->input->is_ajax_request() == true) {
            $list   = $this->severity->get_datatables();
            $data   = array();
            $no     = $_POST['start'];
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
                   <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->name . "')\">
                       <i class=\"far fa-trash-alt\"></i>
                   </button>";

                $row[]  = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[]  = $no;
                $row[]  = $field->name;
                $row[]  = $field->response_weekdays_o . ' minutes';
                $row[]  = $field->response_weekdays_ao . ' minutes';
                $row[]  = $field->response_weekends_o . ' minutes';
                $row[]  = $field->response_weekends_ao . ' minutes';
                $row[]  = $field->resolve_weekdays_o . ' hours';
                $row[]  = $field->resolve_weekdays_ao . ' hours';
                $row[]  = $field->resolve_weekends_o . ' hours';
                $row[]  = $field->resolve_weekends_ao . ' hours';
                $row[]  = $status;
                $row[]  = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw"              => $_POST['draw'],
                "recordsTotal"      => $this->severity->count_all(),
                "recordsFiltered"   => $this->severity->count_filtered(),
                "data"              => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahseverity()
    {
        if ($this->input->is_ajax_request() == TRUE) {

            $data['severity'] = $this->severity->getData()->result();

            $msg = [
                'sukses' => $this->load->view('severity/modaltambah', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandataseverity()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama                   = strtoupper($this->input->post('nama', TRUE));
            $response_weekday_o     = $this->input->post('response_weekday_o', TRUE);
            $response_weekday_ao    = $this->input->post('response_weekday_ao', TRUE);
            $resolve_weekday_o      = $this->input->post('resolve_weekday_o', TRUE);
            $resolve_weekday_ao     = $this->input->post('resolve_weekday_ao', TRUE);
            $response_weekend_o     = $this->input->post('response_weekend_o', TRUE);
            $response_weekend_ao    = $this->input->post('response_weekend_ao', TRUE);
            $resolve_weekend_o      = $this->input->post('resolve_weekend_o', TRUE);
            $resolve_weekend_ao     = $this->input->post('resolve_weekend_ao', TRUE);
            $createdBy              = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'nama',
                'Severity',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'response_weekday_o',
                'Response Weekday Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );

            $this->form_validation->set_rules(
                'response_weekday_ao',
                'Response Weekday After Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );

            $this->form_validation->set_rules(
                'resolve_weekday_o',
                'Resolve Weekday Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );

            $this->form_validation->set_rules(
                'resolve_weekday_ao',
                'Resolve Weekday After Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );

            $this->form_validation->set_rules(
                'response_weekend_o',
                'Response Weekend Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );

            $this->form_validation->set_rules(
                'response_weekend_ao',
                'Response Weekend After Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );

            $this->form_validation->set_rules(
                'resolve_weekend_o',
                'Resolve Weekend Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );

            $this->form_validation->set_rules(
                'resolve_weekend_ao',
                'Resolve Weekend After Office',
                'required|trim',
                [
                    'required'  => '%s belum diisi'
                ]
            );


            if ($this->form_validation->run() == TRUE) {
                $this->severity->simpan($nama, $response_weekday_o, $response_weekday_ao, $resolve_weekday_o, $resolve_weekday_ao, $response_weekend_o, $response_weekend_ao, $resolve_weekend_o, $resolve_weekend_ao, $createdBy);

                $msg = [
                    'sukses' => 'data severity berhasil disimpan'
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

    public function formeditseverity()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->severity->ambildata($id);

            if ($ambildata) {
                $row    = $ambildata->row_array();
                $data   = [
                    'id'                    => $id,
                    'nama'                  => $row['name'],
                    'response_weekday_o'    => $row['response_weekdays_o'],
                    'response_weekday_ao'   => $row['response_weekdays_ao'],
                    'resolve_weekday_o'     => $row['resolve_weekdays_o'],
                    'resolve_weekday_ao'    => $row['resolve_weekdays_ao'],
                    'response_weekend_o'    => $row['response_weekends_o'],
                    'response_weekend_ao'   => $row['response_weekends_o'],
                    'resolve_weekend_o'     => $row['resolve_weekends_o'],
                    'resolve_weekend_ao'    => $row['resolve_weekends_ao'],
                    'is_active'             => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('severity/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedataseverity()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                     = $this->input->post('id', TRUE);
            $nama                   = $this->input->post('nama', TRUE);
            $response_weekday_o     = $this->input->post('response_weekday_o', TRUE);
            $response_weekday_ao    = $this->input->post('response_weekday_ao', TRUE);
            $resolve_weekday_o      = $this->input->post('resolve_weekday_o', TRUE);
            $resolve_weekday_ao     = $this->input->post('resolve_weekday_ao', TRUE);
            $response_weekend_o     = $this->input->post('response_weekend_o', TRUE);
            $response_weekend_ao    = $this->input->post('response_weekend_ao', TRUE);
            $resolve_weekend_o      = $this->input->post('resolve_weekend_o', TRUE);
            $resolve_weekend_ao     = $this->input->post('resolve_weekend_ao', TRUE);
            $is_active              = $this->input->post('is_active', TRUE);
            $modifiedBy             = $this->session->userdata('username');
            $modifiedDate           = date("Y-m-d H:i:s");

            $this->severity->update($id, $nama, $response_weekday_o, $response_weekday_ao, $resolve_weekday_o, $resolve_weekday_ao, $response_weekend_o, $response_weekend_ao, $resolve_weekend_o, $resolve_weekend_ao, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'data severity berhasil di-Update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusseverity()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id     = $this->input->post('id', TRUE);
            $hapus  = $this->severity->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Case deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultipleseverity()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->severity->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data severity deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Severity //

	
    // Begin Modul Manager //

    public function manager()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Manager';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('manager', 'Manager', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('atasan/content', $data, TRUE),
                'modals'    => $this->load->view('atasan/modals', $data, TRUE),
                'script'    => $this->load->view('atasan/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('atasan/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Manager Added !</div>');
            redirect('menu');
        }
    }

    public function getDataManager()
    {
        if ($this->input->is_ajax_request() == true) {
            $list   = $this->manager->get_datatables();
            $data   = array();
            $no     = $_POST['start'];
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
                   <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->name . "')\">
                       <i class=\"far fa-trash-alt\"></i>
                   </button>";

                $row[]  = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[]  = $no;
                $row[]  = $field->name;
                $row[]  = $field->department;
                $row[] = date('d F Y', strtotime($field->created_date));
                $row[]  = $field->created_by;
                $row[]  = $status;
                $row[]  = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw"              => $_POST['draw'],
                "recordsTotal"      => $this->manager->count_all(),
                "recordsFiltered"   => $this->manager->count_filtered(),
                "data"              => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function getSelectManager()
    {
        if ($this->input->is_ajax_request() == true) {
            $department_id  = $this->input->post('id', TRUE);
            $data           = $this->manager->getSelectDepartment($department_id)->result();
            echo json_encode($data);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahmanager()
    {
        if ($this->input->is_ajax_request() == TRUE) {

            $data['department'] = $this->department->getData()->result();

            $msg = [
                'sukses' => $this->load->view('atasan/modaltambah', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatamanager()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama           = ucwords($this->input->post('nama', TRUE));
            $departmentId   = $this->input->post('department', TRUE);
            $email          = $this->input->post('email', TRUE);
            $mobile         = $this->input->post('mobile', TRUE);
            $createdBy      = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'nama',
                'Manager',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'department',
                'Departemen',
                'required|trim',
                [
                    'required'  => '%s belum dipilih'
                ]
            );

            $this->form_validation->set_rules(
                'email',
                'Email',
                'required|trim',
                [
                    'required'  => '%s belum dipilih'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->manager->simpan($nama, $departmentId, $email, $mobile, $createdBy);

                $msg = [
                    'sukses' => 'data Manager berhasil disimpan'
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

    public function formeditmanager()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->manager->ambildata($id);

            if ($ambildata) {
                $row    = $ambildata->row_array();
                $data   = [
                    'id'            => $id,
                    'nama'          => $row['name'],
                    'departmentId'  => $row['department_id'],
                    'department'    => $this->department->getData()->result(),
                    'email'         => $row['email'],
                    'mobile'        => $row['mobile'],
                    'is_active'     => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('atasan/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatamanager()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $nama           = $this->input->post('nama', TRUE);
            $departmentId   = $this->input->post('department', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $email          = $this->input->post('email', TRUE);
            $mobile         = $this->input->post('mobile', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->manager->update($id, $nama, $departmentId, $email, $mobile, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'data manager berhasil di-Update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusmanager()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id     = $this->input->post('id', TRUE);
            $hapus  = $this->manager->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Department deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiplemanager()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->manager->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data manager deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Manager //

	// Begin Modul Department //

    public function department()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Department';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('department', 'Department', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('department/content', $data, TRUE),
                'modals'    => $this->load->view('department/modals', $data, TRUE),
                'script'    => $this->load->view('department/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('department/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Department Added !</div>');
            redirect('menu');
        }
    }

    public function getDataDepartment()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->department->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = array();

                // Membuat tombol
                $tomboledit = "
                    <button type=\"button\" class=\"btn btn-outline-info\" title=\"Edit Data\" onclick=\"edit('" . $field->id . "')\">
                        <i class=\"far fa-edit\"></i>
                    </button>";
                $tombolhapus = "
                    <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->name . "')\">
                        <i class=\"far fa-trash-alt\"></i>
                    </button>";

                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $no;
                $row[] = $field->name;
                $row[] = date('d F Y', strtotime($field->created_date));
                $row[] = $field->created_by;
                $row[] = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->department->count_all(),
                "recordsFiltered" => $this->department->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahdepartment()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $msg = [
                'sukses' => $this->load->view('department/modaltambah', '', TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatadepartment()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama       = ucwords($this->input->post('nama', TRUE));
            $createdBy  = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'nama',
                'Department',
                'required|trim|is_unique[department.name]',
                [
                    'required'  => '%s tidak boleh kosong',
                    'is_unique' => '%s sudah ada'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->department->simpan($nama, $createdBy);

                $msg = [
                    'sukses' => 'data department berhasil disimpan'
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

    public function formeditdepartment()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->department->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'        => $id,
                    'nama'      => $row['name']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('department/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatadepartment()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $nama           = $this->input->post('nama', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->department->update($id, $nama, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'data department berhasil di-Update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusdepartment()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->department->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Department deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultipledepartment()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->department->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data department deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Department //

	// Begin Modul Site //

    public function site()
    {
		$data	= [
			'user'	=> $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
			'title'	=> 'Site',
			'menu'	=> $this->db->get('user_menu')->result_array(),
		];

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('site/content', $data, TRUE),
            'modals'    => $this->load->view('site/modals', $data, TRUE),
            'script'    => $this->load->view('site/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('site/index', $template);
    }

    public function getDataSite()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->site->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = array();

                // Membuat tombol
                $tomboledit = "
                    <button type=\"button\" class=\"btn btn-outline-info\" title=\"Edit Data\" onclick=\"edit('" . $field->id . "')\">
                        <i class=\"far fa-edit\"></i>
                    </button>";
                $tombolhapus = "
                    <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->name . "')\">
                        <i class=\"far fa-trash-alt\"></i>
                    </button>";

                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $no;
                $row[] = $field->name;
                $row[] = date('d F Y', strtotime($field->created_date));
                $row[] = $field->created_by;
                $row[] = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->site->count_all(),
                "recordsFiltered" => $this->site->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahsite()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $msg = [
                'sukses' => $this->load->view('site/modaltambah', '', TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatasite()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama       = ucwords($this->input->post('nama', TRUE));
            $createdBy  = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'nama',
                'Site',
                'required|trim|is_unique[site.name]',
                [
                    'required'  => '%s tidak boleh kosong',
                    'is_unique' => '%s sudah ada'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->site->simpan($nama, $createdBy);

                $msg = [
                    'sukses' => 'Site has been saved successfully'
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

    public function formeditsite()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->site->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'        => $id,
                    'nama'      => $row['name']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('site/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatasite()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $nama           = $this->input->post('nama', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->site->update($id, $nama, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'Site has been updated successfully'
            ];

            echo json_encode($msg);
        }
    }

    public function hapussite()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->site->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Site has been deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiplesite()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->site->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data Site deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Site //
}
