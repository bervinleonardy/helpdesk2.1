<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('Modelapps', 'apps');
        $this->load->model('Modelanswer', 'answer');
        $this->load->model('Modelresponden', 'responden');
        $this->load->library('upload');
    }

    // Begin Modul  //

    public function index()
    {
        $data = [
            'id'        => $this->session->userdata('nik'),
            'username'  => $this->session->userdata('username'),
            'nama'      => $this->session->userdata('name'),
            'user'      => $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
            'title'     => 'Survey Apps ICT MP',
			'apps'		=> $this->db->get_where('aplikasi', ['is_active' => 1])->result_array(),
        ];

        $template       = array(
            'header'    => $this->load->view('templates/survey/header', $data, TRUE),
            'topbar'    => $this->load->view('templates/survey/topbar', $data, TRUE),
            'menu'      => $this->load->view('templates/survey/menu', '', TRUE),
            'content'   => $this->load->view('survey/content', '', TRUE),
            'footer'    => $this->load->view('templates/survey/footer', '', TRUE),
            'script'    => $this->load->view('survey/script', '', TRUE),
        );
        $this->parser->parse('templates/survey/index', $template);
    }

	public function responden()
    {
		$data			= [
			'user' 		=> $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
			'title'		=> 'Responder Survey Apps',
			'menu'		=> $this->db->get('user_menu')->result_array()
		];

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('survey_apps/content', $data, TRUE),
            'modals'    => $this->load->view('survey_apps/modals', $data, TRUE),
            'script'    => $this->load->view('survey_apps/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('survey_apps/index', $template);
    }
	
	public function dashboard()
    {
		$startDate      = $this->uri->segment(3) ? $this->uri->segment(3) : NULL;
        $endDate        = $this->uri->segment(4) ? $this->uri->segment(4) : NULL;

		if (($startDate != NULL) && ($endDate != NULL)) {
			$start  		= date("Y-m-d", strtotime($startDate));
			$end    		= date("Y-m-d", strtotime($endDate));
		} else {
			$end   = date('Y-m-d');
			$dateTime= new Datetime($end);
			$dateTime->modify('-1 month');
			$start = $dateTime->format('Y-m-d');
		}

		// var_dump($start, $end);

		$data = [
            'user'      =>  $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
            'title'     => 'Survey Apps Dashboard',
            'total'     => $this->responden->totalResponden($start,$end)->row_array(),
            'totalApps' => $this->apps->totalApps()->row_array(),
            'usePie'    => $this->apps->usePieChart($start, $end),
            'start' 	=> date('d-m-Y', strtotime($start)),
            'end' 		=> date('d-m-Y', strtotime($end)),
            'totalUse'  => $this->answer->totalUse()->row_array(),
            // 'closed'    => $this->tiket->closed()->row_array(),
            // 'waiting'   => $this->tiket->waiting()->row_array(),
            // 'member'    => $this->employee->memberICT()->row_array(),
            // 'bar'       => $this->tiket->barChart()->result(),
            // 'area'      => $this->tiket->areaChart()->result()
        ];

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('survey/dashboard/content', $data, TRUE),
            'modals'    => $this->load->view('templates/modals', $data, TRUE),
            'script'    => $this->load->view('survey/dashboard/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('dashboard/index', $template);
    }

	public function AppsIct()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Apps';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('aplikasi/content', $data, TRUE),
            'modals'    => $this->load->view('aplikasi/modals', $data, TRUE),
            'script'    => $this->load->view('aplikasi/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('aplikasi/index', $template);
    }

	public function getData()
	{
		if ($this->input->is_ajax_request() == true) {
			$list 	= $this->responden->get_datatables();
			$data 	= array();
			$no 	= $_POST['start'];
			foreach ($list as $field) {
				$no++;
				$row = array();

				// Membuat tombol

				$tombolhapus = "
					<button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->nik . "', '" . $field->created_date . "', '" . $field->name . "')\">
						<i class=\"far fa-trash-alt\"></i>
					</button>";
				$row[] 	= $no;
				$row[] 	= $field->nik;
				$row[] 	= $field->name;
				$row[] 	= date('d F Y', strtotime($field->created_date));
				$row[] 	=  $tombolhapus;
				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->responden->count_all(),
				"recordsFiltered" => $this->responden->count_filtered(),
				"data" => $data,
			);
			//output dalam format JSON
			echo json_encode($output);
		} else {
			exit('Maaf data tidak bisa ditampilkan');
		}
	}

	public function formtambahapps()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$msg = [
				'sukses' => $this->load->view('survey/modaltambah', '', TRUE)
			];
			echo json_encode($msg);
		}
	}

    public function simpan()
    {
        if ($this->input->is_ajax_request() == TRUE) {

			$nik 	= $this->input->post('nik', TRUE);
			$name 	= $this->input->post('name', TRUE);

            $survey = array(
				'id' 		=> $this->input->post('id', TRUE),
				'responds' 	=> $this->input->post('responds', TRUE),
				'star' 		=> $this->input->post('star', TRUE),
				'commentary'=> $this->input->post('commentary', TRUE),
			);

			$jmldata    = count($survey['id']);

            $simpan			= $this->answer->simpan($nik, $survey['id'], $survey['responds'], $survey['star'], $survey['commentary'], $jmldata);
            $simpanOrang	= $this->responden->simpan($nik, $name);
			if ($simpan == true && $simpanOrang == true) {
				$msg = [
					'sukses' => 'Respond anda sangat berarti, kasih tau temen lainnya ya buat isi'
				];
			} else {
				$msg = [
					'gagal' => 'Survey failed !'
				];
			}
            
            echo json_encode($msg);
        }
    }

	public function hapus()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id 			= $this->input->post('id', TRUE);
			$nik 			= $this->input->post('nik', TRUE);
			$created_date 	= $this->input->post('created_date', TRUE);
			$hapusAnswer 	= $this->answer->hapus($nik, $created_date);
			$hapus = $this->responden->hapus($id);
			if ($hapus && $hapusAnswer) {
				$msg = [
					'sukses' => 'Responden deleted successfully'
				];
			}
			echo json_encode($msg);
		}
	}

	// End Modul //

}
