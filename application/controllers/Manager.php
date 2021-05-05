<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manager extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
        $this->load->model('Modelcategory', 'category');
        $this->load->model('Modelsubcategory', 'subcategory');
        $this->load->model('Modelcases', 'cases');
        $this->load->model('Modelseverity', 'severity');
        $this->load->model('Modelemployee', 'employee');
        $this->load->model('Modeltiket', 'tiket');
        $this->load->model('Modellogtiket', 'logtiket');
        $this->load->model('Modelseverity', 'severity');
        $this->load->model('Modelformrequest', 'formrequest');
        $this->load->library('upload');
    }

    // Begin Modul Tiket Manager //

    public function index()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Ticket Manager';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('category', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('manager/content', $data, TRUE),
                'modals'    => $this->load->view('manager/modals', $data, TRUE),
                'script'    => $this->load->view('manager/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('manager/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Category Added !</div>');
            redirect('menu');
        }
    }

    public function getData()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->tiket->get_datatables_manager();
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

                $formRespon = "
                <button type=\"button\" class=\"btn btn-outline-warning\" title=\"Edit Data\" onclick=\"formrespon('" . $field->id . "')\">
                    <i class=\"far fa-edit\"></i>
                </button>";

                $formTransfer = "
                <button type=\"button\" class=\"btn btn-outline-warning\" title=\"Edit Data\" onclick=\"formtransfer('" . $field->id . "')\">
                    <i class=\"far fa-edit\"></i>
                </button>";

                $tombollihat = "
                <button type=\"button\" class=\"btn btn-outline-info\" title=\"Lihat\" onclick=\"lihat('" . $field->id . "')\">
                    <i class=\"far fa-eye\"></i>
                </button>";

                if ($field->statusId == 8 || $field->statusId == 9) {
                    $check = "";
                    $tomboledit = "";
                } else if ($field->statusId == 1 || $field->statusId == 8) {
                    $check = "";
                    $tomboledit = $formRespon;
                } else if ($field->statusId == 10) {
                    $check = "";
                    $tomboledit = $formTransfer;
                } else {
                    $check = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                    $tomboledit = '';
                }

                $row[]  = $check;
                $row[]  = $no;
                $row[]  = $field->tiket;
                $row[]  = $field->department;
                $row[]  = $field->case;
                $row[]  = $field->creator;
                $row[]  = $field->karyawan;
                $row[]  = date("j F Y", strtotime($field->created_date));
                $row[]  = $status;
                $row[]  = $progress;
                $row[]  = $tombollihat . ' ' . $tomboledit;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->tiket->count_all(),
                "recordsFiltered" => $this->tiket->count_filtered_manager(),
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

    public function lihat()
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
                    'creator'               => $row['nama_creator'],
                    'keterangan'            => $row['keterangan'],
                    'keteranganEmployee'    => $row['keterangan_karyawan'],
                    'keteranganManager'     => $row['keterangan_manager'],
                    'created_date'          => $row['created_date'],
                    'startDate'             => $row['startDate'],
                    'endDate'               => $row['endDate'],
                ];
            }

            $msg = [
                'sukses' => $this->load->view('manager/modallihat', $data, TRUE)
            ];
            echo json_encode($msg);
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

    public function formtambah()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $msg = [
                'sukses' => $this->load->view('manager/modaltambah', '', TRUE)
            ];
            echo json_encode($msg);
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
                    'severityList'          => $this->severity->getData()->result(),
                    'progress'              => $row['progress'],
                    'category'              => $row['category'],
                    'categoryId'            => $row['category_id'],
                    'categoryList'          => $this->category->getData()->result(),
                    'subcategoryId'         => $row['subcategory_id'],
                    'subcategory'           => $row['subcategory'],
                    'caseId'                => $row['case_id'],
                    'case'                  => $row['case'],
                    'subject'               => $row['subject'],
                    'karyawanId'            => $row['karyawan_id'],
                    'karyawan'              => $row['karyawan'],
                    'karyawanList'          => $this->employee->getData()->result(),
                    'keterangan'            => $row['keterangan'],
                    'keterangan_karyawan'   => $row['keterangan_karyawan'],
                    'keterangan_manager'    => $row['keterangan_manager'],
                    'created_date'          => $row['created_date'],
                ];
            }

            $msg = [
                'sukses' => $this->load->view('manager/modalrespon', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function formtransfer()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->tiket->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $ambillogtransfer = $this->logtiket->ambildatatransfer($id);
                $row_log = $ambillogtransfer->row_array();
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
                    'karyawanRequestedId'   => $row_log['user_id'],
                    'karyawanRequested'     => $row_log['karyawan'],
                    'karyawanId'            => $row['karyawan_id'],
                    'karyawan'              => $row['karyawan'],
                    'creator'               => $row['nama_creator'],
                    'keterangan'            => $row['keterangan'],
                    'keteranganEmployee'    => $row['keterangan_karyawan'],
                    'keteranganManager'     => $row['keterangan_manager'],
                    'created_date'          => $row['created_date'],
                    'startDate'             => $row['startDate'],
                    'endDate'               => $row['endDate'],
                ];
            }

            // var_dump($data);
            // die();

            $msg = [
                'sukses' => $this->load->view('manager/modaltransfer', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    private function email($id, $keterangan)
    {
        $ambildata = $this->tiket->ambildata($id);

        if ($ambildata) {
            $row = $ambildata->row_array();

            //email
            $no             = $row['tiket'];
            $subject        = $row['subject'];
            $user_id        = $row['karyawan_id'];
            $status_id      = $row['status_id'];
            $group          = $row['group'];
            $groupEmail     = $row['group_email'];
            $nama_creator   = $row['nama_creator'];
            $creatorEmail   = $row['creator_email'];
            $addressEmail   = getAddressEmail($user_id, $group);

            if ($status_id == 3) { //Assign
                $to = $addressEmail['email'] . ', ' . $creatorEmail;
                $cc = '';
            } else if ($status_id == 9) { //Reject
                $to = $creatorEmail;
                $cc = $groupEmail;
            } else {
                $to = $addressEmail['email'];
                $cc = $creatorEmail;
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

    public function reject()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $keteranganManager  = $this->input->post('keteranganManager', FALSE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->reject($id, $keteranganManager, $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganManager($id_log, $keteranganManager);
            $msg = $this->email($id, $keteranganManager);
            echo json_encode($msg);
        }
    }

    private function waktu_response($waktuEstimasi, $createdDate)
    {
        //perhitungan waktu estimasi  
        $response_weekdays_o    = $waktuEstimasi['response_weekdays_o'];
        $response_weekdays_ao   = $waktuEstimasi['response_weekdays_ao'];
        $response_weekends_o    = $waktuEstimasi['response_weekends_o'];
        $response_weekends_ao   = $waktuEstimasi['response_weekends_ao'];

        //waktu kerja office (response)
        $waktu_date  = date('H:i:s', strtotime($createdDate));
        $hariCreated = date('D', strtotime($createdDate));

        if ($hariCreated == "Sat" || $hariCreated == "Sun") {
            $mulaiKerja     = date('H:i:s', strtotime('06:00:00')); // mulai weekend 
            $pulangKerja    = date('H:i:s', strtotime('18:00:00')); // akhir weekend

            if (($waktu_date > $mulaiKerja) && ($waktu_date < $pulangKerja)) {
                $responseTime    = $response_weekends_o;
            } else {
                $responseTime    = $response_weekends_ao;
            }
        } else {
            $mulaiKerja     = date('H:i:s', strtotime('08:00:00')); // mulai weekdays 
            $pulangKerja    = date('H:i:s', strtotime('17:00:00')); // akhir weekdays

            if (($waktu_date > $mulaiKerja) && ($waktu_date < $pulangKerja)) {
                $responseTime    = $response_weekdays_o;
            } else {
                $responseTime    = $response_weekdays_ao;
            }
        }
        return $responseTime;
    }

    public function assign()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $severity           = $this->input->post('severity', TRUE);
            $startDate          = date("Y-m-d H:i:s", strtotime($this->input->post('startDate', TRUE) . ' 00:00:00'));
            $endDate            = date("Y-m-d H:i:s", strtotime($this->input->post('endDate', TRUE) . ' 23:59:59'));
            $category           = $this->input->post('category', TRUE);
            $subcategory        = $this->input->post('subcategory', TRUE);
            $case               = $this->input->post('case', TRUE);
            $karyawan           = $this->input->post('karyawan', TRUE);
            $keteranganManager  = $this->input->post('keteranganManager', TRUE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $waktuEstimasi      = $this->severity->ambilestimasi_respon($severity);
            $responseTime       = $this->waktu_response($waktuEstimasi, $startDate);

            $this->form_validation->set_rules(
                'startDate',
                'Start Date',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'endDate',
                'End Date',
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
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'subcategory',
                'Subategory',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'case',
                'Case',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'karyawan',
                'Karyawan',
                'required|trim',
                [
                    'required'  => '%s harus dipilih'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->tiket->assign($id, $severity, $startDate, $endDate, $category, $subcategory, $case, $karyawan, $keteranganManager, $status, $modifiedBy, $modifiedDate, $responseTime);
                $id_log = $this->logtiket->ambilidlog($no);
                $this->logtiket->updateKeteranganManager($id_log, $keteranganManager);
                $msg = $this->email($id, $keteranganManager);
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

    public function declineTransfer()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $karyawan           = $this->input->post('karyawan', TRUE);
            $karyawanNama       = $this->input->post('karyawanNama', TRUE);
            $keteranganManager  = $this->input->post('keteranganManager', TRUE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->responTransfer($id, $karyawan, $keteranganManager,  $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganManager($id_log, $keteranganManager);
            $msg = $this->email($id, $keteranganManager);

            echo json_encode($msg);
        }
    }

    public function approveTransfer()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $karyawan           = $this->input->post('karyawan', TRUE);
            $karyawanNama       = $this->input->post('karyawanNama', TRUE);
            $keteranganManager  = $this->input->post('keteranganManager', TRUE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->responTransfer($id, $karyawan, $keteranganManager,  $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganManager($id_log, $keteranganManager);
            $msg = $this->email($id, $keteranganManager);

            echo json_encode($msg);
        }
    }

    public function closemultipleticket()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $closedTicket  = $this->tiket->closebanyak($id, $jmldata);

            if ($closedTicket == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data closed successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Modul Tiket Manager //

    // Modul Form Request //

    public function formRequest()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Ticket Manager';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('category', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {
            $template        = array(
                'header'    => $this->load->view('templates/header', $data, TRUE),
                'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
                'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
                'content'   => $this->load->view('manager/content', $data, TRUE),
                'modals'    => $this->load->view('manager/modals', $data, TRUE),
                'script'    => $this->load->view('manager/script', '', TRUE),
                'footer'    => $this->load->view('templates/footer', '', TRUE),
            );
            $this->parser->parse('manager/index', $template);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Category Added !</div>');
            redirect('menu');
        }
    }

    public function getDataFormRequest()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->tiket->get_datatables_manager();
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

                $formRespon = "
                 <button type=\"button\" class=\"btn btn-outline-warning\" title=\"Edit Data\" onclick=\"formrespon('" . $field->id . "')\">
                     <i class=\"far fa-edit\"></i>
                 </button>";

                $formTransfer = "
                 <button type=\"button\" class=\"btn btn-outline-warning\" title=\"Edit Data\" onclick=\"formtransfer('" . $field->id . "')\">
                     <i class=\"far fa-edit\"></i>
                 </button>";

                $tombollihat = "
                 <button type=\"button\" class=\"btn btn-outline-info\" title=\"Lihat\" onclick=\"lihat('" . $field->id . "')\">
                     <i class=\"far fa-eye\"></i>
                 </button>";

                if ($field->statusId == 8 || $field->statusId == 9) {
                    $check = "";
                    $tomboledit = "";
                } else if ($field->statusId == 1 || $field->statusId == 8) {
                    $check = "";
                    $tomboledit = $formRespon;
                } else if ($field->statusId == 10) {
                    $check = "";
                    $tomboledit = $formTransfer;
                } else {
                    $check = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                    $tomboledit = '';
                }

                $row[]  = $check;
                $row[]  = $no;
                $row[]  = $field->tiket;
                $row[]  = $field->department;
                $row[]  = $field->case;
                $row[]  = $field->creator;
                $row[]  = $field->karyawan;
                $row[]  = date("j F Y", strtotime($field->created_date));
                $row[]  = $status;
                $row[]  = $progress;
                $row[]  = $tombollihat . ' ' . $tomboledit;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->tiket->count_all(),
                "recordsFiltered" => $this->tiket->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function lihatFormRequest()
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
                    'creator'               => $row['nama_creator'],
                    'keterangan'            => $row['keterangan'],
                    'keteranganEmployee'    => $row['keterangan_karyawan'],
                    'keteranganManager'     => $row['keterangan_manager'],
                    'created_date'          => $row['created_date'],
                    'startDate'             => $row['startDate'],
                    'endDate'               => $row['endDate'],
                ];
            }

            $msg = [
                'sukses' => $this->load->view('manager/modallihat', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function formRequestRespon()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->tiket->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $ambillogtransfer = $this->logtiket->ambildatatransfer($id);
                $row_log = $ambillogtransfer->row_array();
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
                    'karyawanRequestedId'   => $row_log['user_id'],
                    'karyawanRequested'     => $row_log['karyawan'],
                    'karyawanId'            => $row['karyawan_id'],
                    'karyawan'              => $row['karyawan'],
                    'creator'               => $row['nama_creator'],
                    'keterangan'            => $row['keterangan'],
                    'keteranganEmployee'    => $row['keterangan_karyawan'],
                    'keteranganManager'     => $row['keterangan_manager'],
                    'created_date'          => $row['created_date'],
                    'startDate'             => $row['startDate'],
                    'endDate'               => $row['endDate'],
                ];
            }

            // var_dump($data);
            // die();

            $msg = [
                'sukses' => $this->load->view('manager/modaltransfer', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function declineRequest()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $karyawan           = $this->input->post('karyawan', TRUE);
            $karyawanNama       = $this->input->post('karyawanNama', TRUE);
            $keteranganManager  = $this->input->post('keteranganManager', TRUE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->responTransfer($id, $karyawan, $keteranganManager,  $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganManager($id_log, $keteranganManager);
            $msg = $this->email($id, $keteranganManager);

            echo json_encode($msg);
        }
    }

    public function approveRequest()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $karyawan           = $this->input->post('karyawan', TRUE);
            $karyawanNama       = $this->input->post('karyawanNama', TRUE);
            $keteranganManager  = $this->input->post('keteranganManager', TRUE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->responTransfer($id, $karyawan, $keteranganManager,  $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganManager($id_log, $keteranganManager);
            $msg = $this->email($id, $keteranganManager);

            echo json_encode($msg);
        }
    }

    public function approveMultipleRequest()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id         = $this->input->post('id', TRUE);
            $jmldata    = count($id);

            $closedTicket  = $this->tiket->closebanyak($id, $jmldata);

            if ($closedTicket == TRUE) {
                $msg = [
                    'sukses' => "$jmldata data closed successfully"
                ];
            }

            echo json_encode($msg);
        } else {
            isLogIn();
        }
    }

    // End Module Form Request //
}
