<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklist extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
        $this->load->model('Modelapps', 'apps');
	}

    // Begin Modul Checklist //

    public function index()
    {
		$data = [
			'user'	=> $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
			'title' => 'checklist',
			'menu' 	=> $this->db->get('user_menu')->result_array()
		];

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('checklist/content', $data, TRUE),
            'modals'    => $this->load->view('checklist/modals', $data, TRUE),
            'script'    => $this->load->view('checklist/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('checklist/index', $template);
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

    public function formeditchecklist()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->category->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'       	=> $id,
                    'appsId' 	=> $row['apps_id'],
                    'aplikasi'  => $this->apps->getData()->result(),
                    'name'      => $row['name'],
                    'is_active' => $row['is_active']
                ];
            }

            $msg = [
                'sukses' => $this->load->view('checklist/modaledit', $data, TRUE)
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
    // End Modul Checklist //
}
