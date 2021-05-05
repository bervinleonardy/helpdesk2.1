<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan extends CI_Controller
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
        $this->load->model('Modelformrequest', 'formrequest');
        $this->load->library('upload');
    }

    // Begin Modul Tiket Karyawan //

    public function index()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Ticket Employee';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('karyawan/content', $data, TRUE),
            'modals'    => $this->load->view('karyawan/modals', $data, TRUE),
            'script'    => $this->load->view('karyawan/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('karyawan/index', $template);
    }

    public function getData()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->tiket->get_datatables_karyawan();
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

                $formDevelop = "
                <button type=\"button\" class=\"btn btn-outline-warning\" title=\"Edit Data\" onclick=\"formdevelop('" . $field->id . "')\">
                    <i class=\"far fa-edit\"></i>
                </button>";

                $tombollihat = "
                <button type=\"button\" class=\"btn btn-outline-info\" title=\"Lihat\" onclick=\"lihat('" . $field->id . "')\">
                    <i class=\"far fa-eye\"></i>
                </button>";

                if ($field->statusId == 8 || $field->statusId == 9) {
                    $tomboledit = '';
                } else if ($field->statusId == 6 || $field->statusId == 7 || $field->statusId == 11) {
                    $tomboledit = $formDevelop;
                } else if ($field->statusId == 10 || $field->statusId == 4) {
                    $tomboledit = '';
                } else {
                    $tomboledit = $formRespon;
                }

                $row[]  = $no;
                $row[]  = $field->tiket;
                $row[]  = $field->case;
                $row[]  = $field->subject;
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
                "recordsFiltered" => $this->tiket->count_filtered_karyawan(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
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
                'sukses' => $this->load->view('karyawan/modallihat', $data, TRUE)
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
                    'keteranganManager'     => $row['keterangan_manager'],
                    'created_date'          => $row['created_date'],
                    'status_id'             => $row['status_id'],
                ];
            }

            $msg = [
                'sukses' => $this->load->view('karyawan/modalrespon', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function formdevelop()
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
                    'status_id'             => $row['status_id'],
                ];
            }

            $msg = [
                'sukses' => $this->load->view('karyawan/modaldevelop', $data, TRUE)
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
            $to             = $addressEmail['email'];


            if ($status_id == 10) { //Transfer
                $to             = $groupEmail;
                $nama_creator   = 'Administrator';
                $cc             = '';
            } else if ($status_id == 4 || $status_id == 6) { //Clarify or On Progress
                $to             = $addressEmail['email'] . ', ' . $creatorEmail;
                $cc             = '';
            } else  if ($status_id == 8) {
                $to             = $addressEmail['email'] . ', ' . $creatorEmail;
                $cc             = $groupEmail;
            } else { //Waiting
                $to             = $addressEmail['email'];
                $cc             = $creatorEmail;
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

    public function update()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $keteranganEmployee = $this->input->post('keteranganEmployee', FALSE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->updateEmployee($id, $keteranganEmployee, $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganEmployee($id_log, $keteranganEmployee);
            $msg = $this->email($id, $keteranganEmployee);

            echo json_encode($msg);
        }
    }

    public function progress()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $keteranganEmployee = $this->input->post('keteranganEmployee', FALSE);
            $status             = $this->input->post('status', TRUE);
            $progress           = $this->input->post('progress', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->updateProgress($id, $keteranganEmployee, $status, $progress, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganEmployee($id_log, $keteranganEmployee);
            $msg = $this->email($id, $keteranganEmployee);

            echo json_encode($msg);
        }
    }

    public function transfer()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id                 = $this->input->post('id', TRUE);
            $no                 = $this->input->post('ticket', TRUE);
            $karyawan           = $this->input->post('karyawan', FALSE);
            $karyawanName       = $this->input->post('karyawanName', FALSE);
            $keteranganEmployee = $this->input->post('keteranganEmployee', FALSE);
            $status             = $this->input->post('status', TRUE);
            $modifiedBy         = $this->session->userdata('username');
            $modifiedDate       = date("Y-m-d H:i:s");

            $this->tiket->transfer($id, $karyawan, $keteranganEmployee, $status, $modifiedBy, $modifiedDate);
            $id_log = $this->logtiket->ambilidlog($no);
            $this->logtiket->updateKeteranganEmployee($id_log, $keteranganEmployee);
            $msg = $this->email($id, $keteranganEmployee);
            echo json_encode($msg);
        }
    }

    // End Modul Tiket Karyawan //

    // Begin Modul Form Request //

    public function form_request()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Form Request Employee';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('karyawan/form_request/content', $data, TRUE),
            'modals'    => $this->load->view('karyawan/form_request/modals', $data, TRUE),
            'script'    => $this->load->view('karyawan/form_request/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('karyawan/index', $template);
    }

    public function getDataFormRequest()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->formrequest->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = array();

                //status
                $status = "<span class=\"badge badge-pill badge-" . $field->badge . "\">" . $field->status . "</span>";

                $tombollihat = "
                    <button type=\"button\" class=\"btn btn-outline-info\" title=\"Lihat\" onclick=\"lihat('" . $field->id . "')\">
                        <i class=\"far fa-eye\"></i>
                    </button>";

                $row[]  = $no;
                $row[]  = $field->nama;
                $row[]  = $field->departemen;
                $row[]  = $status;
                $row[]  = date('d F Y', strtotime($field->created_date));
                $row[]  =  $tombollihat;
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

    public function lihatformrequest()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id  = $this->input->post('id', TRUE);

            $ambildata = $this->formrequest->ambildata($id);

            if ($ambildata) {
                $row = $ambildata->row_array();
                $data = [
                    'id'                        => $id,
                    'tanggal'                   => $row['tanggal'],
                    'nama'                      => $row['nama'],
                    'nik'                       => $row['nik'],
                    'posisi'                    => $row['posisi'],
                    'ditujukan_ke'              => $row['ditujukan_ke'],
                    'departemen'                => $row['departemen'],
                    'lokasi'                    => $row['lokasi'],
                    'employee_status'           => $row['employee_status'],
                    'no_aset'                   => $row['no_aset'],
                    'tgl_dibutuhkan'            => $row['tgl_dibutuhkan'],
                    'status_req'                => $row['status_req'],
                    'akun_user'                 => $row['akun_user'],
                    'detail_aset'               => $row['detail_aset'],
                    'lainnya_detail_aset'       => $row['lainnya_detail_aset'],
                    'detail_peralatan1'         => $row['detail_peralatan1'],
                    'detail_peralatan2'         => $row['detail_peralatan2'],
                    'detail_peralatan3'         => $row['detail_peralatan3'],
                    'detail_peralatan4'         => $row['detail_peralatan4'],
                    'detail_peralatan5'         => $row['detail_peralatan5'],
                    'detail_peralatan6'         => $row['detail_peralatan6'],
                    'lainnya_detail_peralatan'  => $row['lainnya_detail_peralatan'],
                    'justifikasi_bisnis'        => $row['justifikasi_bisnis'],
                    'software1'                 => $row['software1'],
                    'software2'                 => $row['software2'],
                    'software3'                 => $row['software3'],
                    'software4'                 => $row['software4'],
                    'software5'                 => $row['software5'],
                    'software6'                 => $row['software6'],
                    'software7'                 => $row['software7'],
                    'software8'                 => $row['software8'],
                    'software9'                 => $row['software9'],
                    'software10'                => $row['software10'],
                    'software11'                => $row['software11'],
                    'software12'                => $row['software12'],
                    'software13'                => $row['software13'],
                    'software14'                => $row['software14'],
                    'software15'                => $row['software15'],
                    'lainnya_software1'         => $row['lainnya_software1'],
                    'lainnya_software2'         => $row['lainnya_software2'],
                    'lainnya_software3'         => $row['lainnya_software3'],
                    'lainnya_software4'         => $row['lainnya_software4'],
                    'lainnya_software5'         => $row['lainnya_software5'],
                    'lainnya_software6'         => $row['lainnya_software6'],
                    'koneksi_jaringan1'         => $row['koneksi_jaringan1'],
                    'koneksi_jaringan2'         => $row['koneksi_jaringan2'],
                    'koneksi_jaringan3'         => $row['koneksi_jaringan3'],
                    'folder_sharing'            => $row['folder_sharing'],
                    'file_path'                 => $row['file_path'],
                    'tipe_akses1'               => $row['tipe_akses1'],
                    'tipe_akses2'               => $row['tipe_akses2'],
                    'akses_telp1'               => $row['akses_telp1'],
                    'akses_telp2'               => $row['akses_telp2'],
                    'akses_telp3'               => $row['akses_telp3'],
                    'akses_telp4'               => $row['akses_telp4'],
                    'lainnya_akses_telp'        => $row['lainnya_akses_telp'],
                    'informasi_lainnya'         => $row['informasi_lainnya'],
                    'approval_id'               => $row['approval_id'],
                    'approval_name'             => $row['approval_name'],
                    'ict_id'                    => $row['ict_id'],
                    'ict_name'                  => $row['ict_name'],
                    'signature_user'            => $row['signature_user'],
                    'signature_approval'        => $row['signature_approval'],
                    'signature_ict'             => $row['signature_ict'],
                    'created_by'                => $row['created_date'],
                    'created_date'              => $row['created_date'],
                    'status_id'                 => $row['status_id'],
                    'status'                    => $row['status'],
                    'department_id'             => $row['department_id'],
                    'department'                => $row['department'],
                ];
            }

            $msg = [
                'sukses' => $this->load->view('karyawan/form_request/modallihat', '', TRUE)
            ];
            echo json_encode($msg);
        }
    }

    public function formedit()
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

            $msg = [
                'sukses' => $this->load->view('manager/modaltransfer', $data, TRUE)
            ];
            echo json_encode($msg);
        }
    }

    // End Module Form Request //
}
