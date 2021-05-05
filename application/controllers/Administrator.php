<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Administrator extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
        $this->load->model('Modelcategory', 'category');
        $this->load->model('Modelsubcategory', 'subcategory');
        $this->load->model('Modelcases', 'cases');
        $this->load->model('Modelemployee', 'employee');
        $this->load->model('Modeldepartment', 'department');
    }

    // Begin Modul Category //

    public function category()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Category';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('category', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('category/content', $data, TRUE),
                'modals'    => $this->load->view('category/modals', $data, TRUE),
                'script'    => $this->load->view('category/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('category/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Category Added !</div>');
            redirect('menu');
        }
    }

    public function getDataCategory()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->category->get_datatables();
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
                <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->name . "')\">
                    <i class=\"far fa-trash-alt\"></i>
                </button>";

                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $no;
                $row[] = $field->name;
                $row[] = $field->department;
                $row[] = date('d F Y', strtotime($field->created_date));
                $row[] = $field->created_by;
                $row[] = $status;
                $row[] = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->category->count_all(),
                "recordsFiltered" => $this->category->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function getSelectCategory()
    {
        if ($this->input->is_ajax_request() == true) {
            $category_id    = $this->input->post('id', TRUE);
            $data           = $this->category->getSelectCategory($category_id)->result();
            echo json_encode($data);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahcategory()
    {

        $data['department']   = $this->department->getData()->result();
        if ($this->input->is_ajax_request() == TRUE) {
            $msg = [
                'sukses' => $this->load->view('category/modaltambah', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatacategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $department = ucwords($this->input->post('department', TRUE));
            $nama       = ucwords($this->input->post('nama', TRUE));
            $createdBy  = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'department',
                'Department',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'nama',
                'Category',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->category->simpan($department, $nama, $createdBy);

                $msg = [
                    'sukses' => 'data category berhasil disimpan'
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

    public function formeditcategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->category->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'            => $id,
                    'department_id' => $row['department_id'],
                    'department'    => $this->department->getData()->result(),
                    'nama'          => $row['name'],
                    'is_active'     => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('category/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatacategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $department     = $this->input->post('department', TRUE);
            $nama           = $this->input->post('nama', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->category->update($id, $department, $nama, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'data category berhasil di-Update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapuscategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->category->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Category deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiplecategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->category->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data category deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Category //

    // Begin Modul Subcategory //

    public function subcategory()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Subcategory';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('subcategory/content', $data, TRUE),
            'modals'    => $this->load->view('subcategory/modals', $data, TRUE),
            'script'    => $this->load->view('subcategory/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('subcategory/index', $template);
    }

    public function getDataSubcategory()
    {
        if ($this->input->is_ajax_request() == true) {
            $list   = $this->subcategory->get_datatables();
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
                $row[]  = $field->category;
                $row[]  = $field->department;
                $row[]  = date('d F Y', strtotime($field->created_date));
                $row[]  = $field->created_by;
                $row[]  = $status;
                $row[]  = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw"              => $_POST['draw'],
                "recordsTotal"      => $this->subcategory->count_all(),
                "recordsFiltered"   => $this->subcategory->count_filtered(),
                "data"              => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function getSelectSubcategory()
    {
        if ($this->input->is_ajax_request() == true) {
            $category_id    = $this->input->post('id', TRUE);
            $data           = $this->subcategory->getSelectSubcategory($category_id)->result();
            echo json_encode($data);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahsubcategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {

            $group = $this->employee->checkGroup($this->session->userdata('role_id'));

            if ($group->role_id == 1) {
                $where =  '1=1 AND a.is_active = 1';
            } else {
                $where =  'b.id= ' . $group->department_id . ' AND a.is_active =1';
            }

            $this->db->select(
                '
                    a.id, 
                    a.name, 
                    b.name department, 
                    a.created_by, 
                    a.created_date,
                    a.is_active
                '
            );
            $this->db->join('department b', 'a.department_id = b.id', 'left');
            $this->db->where($where);
            $query = $this->db->get('category a');
            $data['category'] = $query->result();

            $msg = [
                'sukses' => $this->load->view('subcategory/modaltambah', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatasubcategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama           = ucwords($this->input->post('nama', TRUE));
            $categoryId     = $this->input->post('category', TRUE);
            $createdBy      = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'nama',
                'Subcategory',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'category',
                'Category',
                'required|trim',
                [
                    'required'  => '%s belum dipilih'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->subcategory->simpan($nama, $categoryId, $createdBy);

                $msg = [
                    'sukses' => 'data subcategory berhasil disimpan'
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

    public function formeditsubcategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->subcategory->ambildata($id);

            if ($ambildata) {
                $row    = $ambildata->row_array();
                $data   = [
                    'id'            => $id,
                    'nama'          => $row['name'],
                    'categoryId'    => $row['category_id'],
                    'category'      => $this->category->getData()->result(),
                    'is_active'     => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('subcategory/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatasubcategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $nama           = $this->input->post('nama', TRUE);
            $categoryId     = $this->input->post('category', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->subcategory->update($id, $nama, $categoryId, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'data subcategory berhasil di-Update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapussubcategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id     = $this->input->post('id', TRUE);
            $hapus  = $this->subcategory->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Subcategory deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiplesubcategory()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->subcategory->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data subcategory deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Subategory //

    // Begin Modul Case //

    public function cases()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Cases';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('case/content', $data, TRUE),
            'modals'    => $this->load->view('case/modals', $data, TRUE),
            'script'    => $this->load->view('case/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('case/index', $template);
    }

    public function getSelectCases()
    {
        if ($this->input->is_ajax_request() == true) {
            $subcategory_id = $this->input->post('id', TRUE);
            $data           = $this->cases->getSelectCases($subcategory_id)->result();
            echo json_encode($data);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function getDataCases()
    {
        if ($this->input->is_ajax_request() == true) {
            $list   = $this->cases->get_datatables();
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
                $row[]  = $field->subcategory;
                $row[]  = $field->category;
                $row[]  = $field->department;
                $row[]  = date('d F Y', strtotime($field->created_date));
                $row[]  = $field->created_by;
                $row[]  = $status;
                $row[]  = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw"              => $_POST['draw'],
                "recordsTotal"      => $this->cases->count_all(),
                "recordsFiltered"   => $this->cases->count_filtered(),
                "data"              => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambahcases()
    {
        if ($this->input->is_ajax_request() == TRUE) {

            $this->db->select(
                '
                    a.id, 
                    a.name, 
                    b.name department, 
                    a.created_by, 
                    a.created_date,
                    a.is_active
                '
            );
            $this->db->join('department b', 'a.department_id = b.id', 'left');
            $query = $this->db->get_where('category a', ['a.is_active' => 1]);
            $data['category'] = $query->result();

            $msg = [
                'sukses' => $this->load->view('case/modaltambah', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandatacases()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama           = ucwords($this->input->post('nama', TRUE));
            $categoryId     = $this->input->post('category', TRUE);
            $subCategoryId  = $this->input->post('subcategory', TRUE);
            $createdBy      = $this->session->userdata('username');

            $this->form_validation->set_rules(
                'nama',
                'Case',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'subcategory',
                'Subcategory',
                'required|trim',
                [
                    'required'  => '%s belum dipilih'
                ]
            );

            $this->form_validation->set_rules(
                'category',
                'Category',
                'required|trim',
                [
                    'required'  => '%s belum dipilih'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->cases->simpan($nama, $subCategoryId, $categoryId, $createdBy);

                $msg = [
                    'sukses' => 'data case berhasil disimpan'
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

    public function formeditcases()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->cases->ambildata($id);

            if ($ambildata) {
                $row    = $ambildata->row_array();
                $data   = [
                    'id'            => $id,
                    'nama'          => $row['name'],
                    'subCategoryId' => $row['subcategory_id'],
                    'categoryId'    => $row['category_id'],
                    'subcategory'   => $this->subcategory->getData()->result(),
                    'category'      => $this->category->getData()->result(),
                    'is_active'     => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('case/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedatacases()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $nama           = $this->input->post('nama', TRUE);
            $subCategoryId  = $this->input->post('subcategory', TRUE);
            $categoryId     = $this->input->post('category', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->cases->update($id, $nama, $subCategoryId, $categoryId, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'data cases berhasil di-Update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapuscases()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id     = $this->input->post('id', TRUE);
            $hapus  = $this->cases->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Case deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultiplecases()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->cases->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data cases deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Case //

    // Begin Modul Employee //

    public function employee()
    {
		$data	= [
			'user'	=> $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
			'title' => 'Employee',
			'menu' 	=> $this->db->get('user_menu')->result_array()
		];

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('employee/content', $data, TRUE),
            'modals'    => $this->load->view('employee/modals', $data, TRUE),
            'script'    => $this->load->view('employee/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('employee/index', $template);
    }

    public function getDataEmployee()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->employee->get_datatables();
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
                <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->name . "')\">
                    <i class=\"far fa-trash-alt\"></i>
                </button>";


                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $no;
                $row[] = $field->nik;
                $row[] = $field->name;
                $row[] = $field->level;
                $row[] = $field->position;
                $row[] = $field->department;
                $row[] = $field->role;
                $row[] = date('d F Y', strtotime($field->created_date));
                $row[] = $status;
                $row[] = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->employee->count_all(),
                "recordsFiltered" => $this->employee->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formeditemployee()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata    = $this->employee->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'            => $id,
                    'nik'           => $row['nik'],
                    'nama'          => $row['name'],
                    'department_id' => $row['department_id'],
                    'department'    => $this->db->get('department')->result(),
                    'level_id'      => $row['rank_id'],
                    'level'      	=> $this->db->get('rank')->result(),
                    'position'      => $row['position'],
                    'email'         => $row['email'],
                    'mobile'        => $row['mobile'],
                    'role_id'       => $row['role_id'],
                    'role'          => $this->db->get_where('user_role', ['is_active' => 1, 'id !=' => 1])->result(),
                    'is_active'     => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('employee/modaledit', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function updatedataemployee()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id             = $this->input->post('id', TRUE);
            $nama           = $this->input->post('nama', TRUE);
            $department_id  = $this->input->post('department', TRUE);
            $level       	= $this->input->post('level', TRUE);
            $position       = $this->input->post('position', TRUE);
            $mobile         = $this->input->post('mobile', TRUE);
            $role_id        = $this->input->post('role', TRUE);
            $is_active      = $this->input->post('is_active', TRUE);
            $modifiedBy     = $this->session->userdata('username');
            $modifiedDate   = date("Y-m-d H:i:s");

            $this->employee->update($id, $nama, $department_id, $level, $position, $mobile, $role_id, $is_active, $modifiedBy, $modifiedDate);

            $msg = [
                'sukses' => 'Data Employe has been saved successfully !'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusemployee()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->employee->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Employee deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function deletemultipleemployee()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $hapusdata  = $this->employee->hapusbanyak($id, $jmldata);

            if ($hapusdata == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data employee deleted successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Employee //
}
