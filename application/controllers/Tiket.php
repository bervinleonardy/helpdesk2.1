<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tiket extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isSignIn();
        $this->load->model('Modelcategory', 'category');
        $this->load->model('Modelsubcategory', 'subcategory');
        $this->load->model('Modelcases', 'cases');
        $this->load->model('Modelseverity', 'severity');
        $this->load->model('Modelemployee', 'employee');
        $this->load->model('Modeltiket', 'tiket');
        $this->load->model('Modellogtiket', 'logtiket');
        $this->load->library('upload');
    }

    // Begin Modul Tiket //

    public function index()
    {
        $data = [
            'id'        => $this->session->userdata('nik'),
            'username'  => $this->session->userdata('username'),
            'nama'      => $this->session->userdata('name'),
            'user'      => $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
            'title'     => 'Helpdesk 2.1',
        ];

        $template       = array(
            'header'    => $this->load->view('templates/tiket/header', $data, TRUE),
            'topbar'    => $this->load->view('templates/tiket/topbar', $data, TRUE),
            'menu'      => $this->load->view('templates/tiket/menu', '', TRUE),
            'content'   => $this->load->view('tiket/content', '', TRUE),
            'footer'    => $this->load->view('templates/tiket/footer', '', TRUE),
            'script'    => $this->load->view('tiket/script', '', TRUE),
        );
        $this->parser->parse('templates/tiket/index', $template);
    }

    public function getData()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->tiket->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = array();

                //status
                $status = "
                    <span class=\"badge badge-pill badge-" . $field->badge . "\">" . $field->status . "</span>
                ";

                // progress
                $progress = "
                <div class=\"progress\" style=\"height:20px;\">
                    <div class=\"progress-bar progress-bar-striped bg-success progress-bar-animated\" role=\"progressbar\" aria-valuenow=\"'" . $field->progress . "'\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: " . $field->progress . "%;\">" . $field->progress . "%</div>
                </div>
                ";

                // Membuat tombol
                $tombollihat = "
                <button type=\"button\" class=\"btn btn-outline-info\" title=\"Edit Data\" onclick=\"lihat('" . $field->id . "')\">
                    <i class=\"fa fa-eye\"></i>
                </button>";

                $tombolrespon = "
                <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"formrespon('" . $field->id . "')\">
                    <i class=\"fa fa-commenting-o\"></i>
                </button>";

                if ($field->status_id == 4 || $field->status_id == 7) {
                    $tombolupdate = $tombolrespon;
                } else {
                    $tombolupdate = '';
                }

                $row[]  = $no;
                $row[]  = $field->tiket;
                $row[]  = $field->category;
                $row[]  = $field->subcategory;
                $row[]  = $field->case;
                $row[]  = $field->subject;
                $row[]  = $field->karyawan;
                $row[]  = date("j F Y", strtotime($field->created_date));
                $row[]  = $status;
                $row[]  = $progress;
                $row[]  = $tombollihat . ' ' . $tombolupdate;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->tiket->count_all(),
                "recordsFiltered" => $this->tiket->count_filtered_karyawan(),
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

    public function getSelectSubcategory()
    {
        if ($this->input->is_ajax_request() == true) {
            $subcategory_id = $this->input->post('id', TRUE);
            $data           = $this->cases->getSelectCases($subcategory_id)->result();
            echo json_encode($data);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambah()
    {
        if ($this->input->is_ajax_request() == TRUE) {

            $data = [
                'category' => $this->category->getData()->result(),
                'severity' => $this->severity->getData()->result(),
            ];

            $msg = [
                'sukses' => $this->load->view('tiket/modaltambah', $data, TRUE)
            ];
            echo json_encode($msg);
        } else {
            var_dump('gagal ajax request');
            die();
        }
    }

    function upload_image()
    {
        if (isset($_FILES["image"]["name"])) {
            $date       = new DateTime();
            $result     = $date->format('Y-m-d_H-i-s');
            $krr        = explode('-', $result);
            $result     = implode("", $krr);

            $tanggal    = date("Ymd");
            $folder     = 'assets/uploads/images/' . $tanggal;
            $namaFile   = $result . '.jpg';
            if (!is_dir($folder)) {
                mkdir('./' . $folder, 0777, true);
            }
            $config['upload_path']      = './' . $folder . '/';
            $config['allowed_types']    = 'jpg|png|gif';
            $config['file_name']        = $namaFile;
            // var_dump($config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('image')) {
                $this->upload->display_errors();
                return FALSE;
            } else {
                $data = $this->upload->data();
                $config['image_library'] = 'gd2';
                $config['source_image'] = $folder . '/' . $data['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['quality'] = '100%';
                $config['width'] = 1280;
                $config['height'] = 720;
                $config['new_image'] = $folder . '/' . $data['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                echo base_url() . $folder . '/' . $data['file_name'];
            }
        }
    }

    function delete_image()
    {
        $src = $this->input->post('src');
        $file_name = str_replace(base_url(), '', $src);
        if (unlink($file_name)) {
            echo 'File Delete Successfully';
        }
    }

    private function getNoTiket($group)
    {
        $table = "tiket";
        $field = "no";
        $today = date('Y');

        $kode       = $today . $group;
        $lastKode   = getMaxbyDate($kode, $table, $field);

        $noUrut = (int) substr($lastKode, -4, 4);
        $noUrut++;

        return $kode . sprintf('%06s', $noUrut);
    }

    private function email($id, $keterangan)
    {
        $ambildata = $this->tiket->ambildata($id);

        if ($ambildata) {
            $row = $ambildata->row_array();
            //email
            $no             = $row['tiket'];
            $subject        = $row['subject'];
            $creatorEmail   = $row['creator_email'];
            $nama_creator   = $row['nama_creator'];
            $user_id        = $row['karyawan_id'];
            $status_id      = $row['status_id'];
            $group          = $row['group'];
            $groupEmail     = $row['group_email'];
            $addressEmail   = getAddressEmail($user_id, $group);

            if ($status_id == 1) {
                $to = $creatorEmail;
                $this->db->select(
                    '
                        a.name category,
                        b.name department,
                        c.email emailDept,
                    '
                );
                $this->db->join('department b', 'a.department_id = b.id', 'left');
                $this->db->join('manager c', 'b.manager_id = c.id', 'left');
                $adminGroup = $this->db->get_where('category a', ['a.id' => $row['category_id']])->row_array();
                $cc = $adminGroup['emailDept'];
            } else if ($status_id == 5) {
                $to = $addressEmail['emailDept'] . ', ' . $creatorEmail;
                $cc = '';
            } else if ($status_id == 8) { //Clarified
                $to = $addressEmail['emailDept'] . ', ' . $creatorEmail;
                $cc = $groupEmail;
            } else { // None
                $to = $creatorEmail;
                $cc = '';
            }
        } else {
            echo 'Data tidak ditemukan';
            die();
        }
		
        $mail = sendEmail($no, $to, $cc, $subject, $nama_creator, $keterangan);

        if ($mail == TRUE) {
            $msg = [
                'sukses' => 'Your Ticket has been ' . $row['status'] . ' ! Success to send mail'
            ];
        } else {
            $msg = [
                'sukses' => 'Your Ticket has been ' . $row['status'] . ' ! Failed to send mail'
            ];
        }

        return $msg;
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $group          = $this->input->post('group', TRUE);
            $no             = $this->getNoTiket($group);
            $category       = $this->input->post('category', TRUE);
            $subcategory    = $this->input->post('subcategory', TRUE);
            $case           = $this->input->post('case', TRUE);
            $severity       = $this->input->post('severity', TRUE);
            $user_id        = $this->input->post('karyawan', TRUE);
            $keterangan     = $this->input->post('keterangan', FALSE);
            $creator_id     = $this->session->userdata('nik');
            $created_by     = $this->session->userdata('username');
            $nama_creator   = $this->session->userdata('name');
            $subject        = $this->input->post('subject', TRUE);
            $creatorEmail   = $this->session->userdata('email');


            $this->form_validation->set_rules(
                'category',
                'Category',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'subcategory',
                'Subcategory',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'case',
                'Case',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'subject',
                'Subject',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'keterangan',
                'Keterangan',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            if ($this->form_validation->run() == TRUE) {

                $this->tiket->simpan($no, $category, $subcategory, $case, $severity, $subject, $user_id, $keterangan, $creator_id, $created_by, $nama_creator, $creatorEmail);

                $id_log = $this->logtiket->ambilidlog($no);
                $this->logtiket->updateKeterangan($id_log, $keterangan);
                // $mail = sendEmail($no, $to, $cc, $subject, $nama_creator, $keterangan);

                $this->db->select('id');
                $this->db->where('no', $no);
                $query  = $this->db->get('tiket');
                $id     = $query->row()->id;
                $msg = $this->email($id, $keterangan);
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

    public function lihat()
    {
        $id = $this->uri->segment(3);
        $queryambildata = $this->tiket->ambildata($id);
        if ($queryambildata->num_rows() > 0) {
            $row = $queryambildata->row_array();
            $data = [
                'title'                 => 'Detail Ticket',
                'id'                    => $this->session->userdata('nik'),
                'username'              => $this->session->userdata('username'),
                'nama'                  => $this->session->userdata('name'),
                'user'                  => $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
                'tiket'                 => $row['tiket'],
                'status'                => $row['status'],
                'category'              => $row['category'],
                'subcategory'           => $row['subcategory'],
                'case'                  => $row['case'],
                'severity'              => $row['severity'],
                'karyawan'              => $row['karyawan'],
                'creator_id'            => $row['creator_id'],
                'creator'               => $row['nama_creator'],
                'status'                => $row['status'],
                'subject'               => $row['subject'],
                'keterangan'            => $row['keterangan'],
                'keteranganEmployee'    => $row['keterangan_karyawan'],
                'keteranganManager'     => $row['keterangan_manager'],
                'progress'              => $row['progress'],
                'created_date'          => $row['created_date'],
                'startDate'             => $row['startDate'],
                'endDate'               => $row['endDate'],
            ];


            $template       = array(
                'header'    => $this->load->view('templates/tiket/header', $data, TRUE),
                'topbar'    => $this->load->view('templates/tiket/topbar', $data, TRUE),
                'menu'      => $this->load->view('templates/tiket/menu', '', TRUE),
                'content'   => $this->load->view('tiket/content-lihat', $data, TRUE),
                'footer'    => $this->load->view('templates/tiket/footer', '', TRUE),
                'script'    => $this->load->view('tiket/script-lihat', '', TRUE),
            );
            $this->parser->parse('templates/tiket/index', $template);
        } else {
            exit('Maaf Data tidak ditemukan');
        }
    }

    public function formrespon()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->tiket->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'                    => $id,
                    'tiket'                 => $row['tiket'],
                    'severityId'            => $row['severity_id'],
                    'severity'              => $row['severity'],
                    'progress'              => $row['progress'],
                    'category'              => $row['category'],
                    'categoryId'            => $row['category_id'],
                    'subcategoryId'         => $row['subcategory_id'],
                    'subcategory'           => $row['subcategory'],
                    'caseId'                => $row['case_id'],
                    'case'                  => $row['case'],
                    'subject'               => $row['subject'],
                    'karyawanId'            => $row['karyawan_id'],
                    'karyawan'              => $row['karyawan'],
                    'keterangan'            => $row['keterangan'],
                    'keteranganEmployee'    => $row['keterangan_karyawan'],
                    'keteranganManager'     => $row['keterangan_manager'],
                    'status_id'             => $row['status_id'],
                    'created_date'          => $row['created_date'],
                ];
            }

            $msg = [
                'sukses' => $this->load->view('tiket/modalrespon', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function update()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $keterangan         = $this->input->post('keterangan', FALSE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->update($id, $keterangan, $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeterangan($id_log, $keterangan);
            $msg = $this->email($id, $keterangan);

            echo json_encode($msg);
        }
    }

    public function hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', TRUE);
            $hapus = $this->tiket->hapus($id);

            if ($hapus) {
                $msg = [
                    'sukses' => 'Tiket deleted successfully'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function timeline($no, $creator_id)
    {
        $query =  $this->logtiket->getTimelineDate($no, $creator_id);
        return $query;
    }

    // End Modul Tiket //

    // tampilan body email //
    public function message()
    {
        $this->load->view('tiket/message');
    }
}
