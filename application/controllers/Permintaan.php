<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isSignIn();
        $this->load->model('Modelformrequest', 'formrequest');
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
            'title'     => 'Formulir Permintaan ICT',
        ];

        $template       = array(
            'header'    => $this->load->view('templates/tiket/header', $data, TRUE),
            'topbar'    => $this->load->view('templates/tiket/topbar', $data, TRUE),
            'menu'      => $this->load->view('templates/tiket/menu', '', TRUE),
            'content'   => $this->load->view('form_request/content', '', TRUE),
            'footer'    => $this->load->view('templates/tiket/footer', '', TRUE),
            'script'    => $this->load->view('form_request/script', '', TRUE),
        );
        $this->parser->parse('templates/tiket/index', $template);
    }


    public function simpandata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nik                    = $this->input->post('nik', TRUE);
            $tanggal                = date('Y-m-d', strtotime($this->input->post('tanggal', TRUE)));
            $department             = $this->input->post('lokasi', TRUE);
            $nama                   = $this->input->post('nama', TRUE);
            $lokasi                 = $this->input->post('lokasi', TRUE);
            $statEmp                = $this->input->post('statEmp', TRUE);
            $position               = $this->input->post('position', TRUE);
            $noAset                 = $this->input->post('noAset', TRUE);
            $ditujukanKe            = $this->input->post('ditujukanKe', TRUE);
            $tglDibutuhkan          = date('Y-m-d', strtotime($this->input->post('tglDibutuhkan', TRUE)));
            $statReq                = $this->input->post('statReq', TRUE);
            $akunUser               = $this->input->post('akunUser', TRUE);
            $detailAset             = $this->input->post('detailAset', TRUE);
            $lainnyaDetailAset      = $this->input->post('lainnyaDetailAset', TRUE);
            $detailPeralatan1       = $this->input->post('detailPeralatan', TRUE);
            $detailPeralatan1       = $this->input->post('detailPeralatan[0]', TRUE);
            $detailPeralatan2       = $this->input->post('detailPeralatan[1]', TRUE);
            $detailPeralatan3       = $this->input->post('detailPeralatan[2]', TRUE);
            $detailPeralatan4       = $this->input->post('detailPeralatan[3]', TRUE);
            $detailPeralatan5       = $this->input->post('detailPeralatan[4]', TRUE);
            $detailPeralatan6       = $this->input->post('detailPeralatan[5]', TRUE);
            $lainnyaDetailPeralatan = $this->input->post('lainnyaDetailPeralatan', TRUE);
            $justifikasiBisnis      = $this->input->post('justifikasiBisnis', TRUE);
            $software1              = $this->input->post('softwares[0]', TRUE);
            $software2              = $this->input->post('softwares[1]', TRUE);
            $software3              = $this->input->post('softwares[2]', TRUE);
            $software4              = $this->input->post('softwares[3]', TRUE);
            $software5              = $this->input->post('softwares[4]', TRUE);
            $software6              = $this->input->post('softwares[5]', TRUE);
            $software7              = $this->input->post('softwares[6]', TRUE);
            $software8              = $this->input->post('softwares[7]', TRUE);
            $software9              = $this->input->post('softwares[8]', TRUE);
            $software10             = $this->input->post('softwares[9]', TRUE);
            $software11             = $this->input->post('softwares[10]', TRUE);
            $software12             = $this->input->post('softwares[11]', TRUE);
            $software13             = $this->input->post('softwares[12]', TRUE);
            $software14             = $this->input->post('softwares[13]', TRUE);
            $software15             = $this->input->post('softwares[14]', TRUE);
            $lainnyaSoftwares1      = $this->input->post('lainnyaSoftwares1', TRUE);
            $lainnyaSoftwares2      = $this->input->post('lainnyaSoftwares2', TRUE);
            $lainnyaSoftwares3      = $this->input->post('lainnyaSoftwares3', TRUE);
            $lainnyaSoftwares4      = $this->input->post('lainnyaSoftwares4', TRUE);
            $lainnyaSoftwares5      = $this->input->post('lainnyaSoftwares5', TRUE);
            $lainnyaSoftwares6      = $this->input->post('lainnyaSoftwares6', TRUE);
            $koneksiJaringan1       = $this->input->post('koneksiJaringan[0]', TRUE);
            $koneksiJaringan2       = $this->input->post('koneksiJaringan[1]', TRUE);
            $koneksiJaringan3       = $this->input->post('koneksiJaringan[2]', TRUE);
            $folderSharing          = $this->input->post('folderSharing', TRUE);
            $filePath               = $this->input->post('filePath', TRUE);
            $tipeAkses1             = $this->input->post('tipeAkses[0]', TRUE);
            $tipeAkses2             = $this->input->post('tipeAkses[1]', TRUE);
            $aksesTelepon1          = $this->input->post('aksesTelepon[0]', TRUE);
            $aksesTelepon2          = $this->input->post('aksesTelepon[1]', TRUE);
            $aksesTelepon3          = $this->input->post('aksesTelepon[2]', TRUE);
            $aksesTelepon4          = $this->input->post('aksesTelepon[3]', TRUE);
            $lainnyaAksesTelepon   = $this->input->post('lainnyaAksesJarTelp', TRUE);
            $informasiLainnya       = $this->input->post('informasiLainnya', TRUE);

            $this->form_validation->set_rules(
                'department',
                'No. Proyek/Departemen',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'lokasi',
                'Lokasi',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'statEmp',
                'Employee Status ',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'position',
                'Posisi',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'ditujukanKe',
                'Ditujukan Ke',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'tglDibutuhkan',
                'Tanggal Dibutuhkan',
                'required',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            if ($this->form_validation->run() == TRUE) {
                $this->formrequest->simpan(
                    $nik,
                    $tanggal,
                    $department,
                    $nama,
                    $lokasi,
                    $statEmp,
                    $position,
                    $noAset,
                    $ditujukanKe,
                    $tglDibutuhkan,
                    $statReq,
                    $akunUser,
                    $detailAset,
                    $lainnyaDetailAset,
                    $detailPeralatan1,
                    $detailPeralatan2,
                    $detailPeralatan3,
                    $detailPeralatan4,
                    $detailPeralatan5,
                    $detailPeralatan6,
                    $lainnyaDetailPeralatan,
                    $justifikasiBisnis,
                    $software1,
                    $software2,
                    $software3,
                    $software4,
                    $software5,
                    $software6,
                    $software7,
                    $software8,
                    $software9,
                    $software10,
                    $software11,
                    $software12,
                    $software13,
                    $software14,
                    $software15,
                    $lainnyaSoftwares1,
                    $lainnyaSoftwares2,
                    $lainnyaSoftwares3,
                    $lainnyaSoftwares4,
                    $lainnyaSoftwares5,
                    $lainnyaSoftwares6,
                    $koneksiJaringan1,
                    $koneksiJaringan2,
                    $koneksiJaringan3,
                    $folderSharing,
                    $filePath,
                    $tipeAkses1,
                    $tipeAkses2,
                    $aksesTelepon1,
                    $aksesTelepon2,
                    $aksesTelepon3,
                    $aksesTelepon4,
                    $lainnyaAksesTelepon,
                    $informasiLainnya
                );

                $msg = [
                    'sukses' => 'Your request has been saved ! Please wait superior to responds.'
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

    // End Module Formulir Permintaan ICT //
}
