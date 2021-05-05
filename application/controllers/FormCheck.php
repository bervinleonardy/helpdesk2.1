<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FormCheck extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
        $this->load->model('Modelemployee', 'employee');
        $this->load->model('Modeltiket', 'tiket');
        $this->load->model('Modellogtiket', 'logtiket');
        $this->load->model('Modelformrequest', 'formrequest');
        $this->load->model('Modelchecklist', 'checklist');
        $this->load->model('Modelapps', 'apps');
        $this->load->model('Modelcategory', 'category');
        $this->load->model('Modelsite', 'site');
        $this->load->model('Modelformcheck', 'formcheck');
        $this->load->model('Modelformchecklist', 'formchecklist');
        $this->load->model('Modelauth', 'auth');
		$this->load->helper('url');
        $this->load->library('upload');
    }

	// Begin Modul Form Check //

	public function index()
	{
		$data = [
			'user'	=> $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
			'title' => 'Form Check',
			'menu' 	=> $this->db->get('user_menu')->result_array(),
			'role'  => $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row()->role_id
		];

		$template        = array(
			'header'    => $this->load->view('templates/header', $data, TRUE),
			'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
			'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
			'content'   => $this->load->view('form_check/content', $data, TRUE),
			'modals'    => $this->load->view('form_check/modals', $data, TRUE),
			'script'    => $this->load->view('form_check/script', '', TRUE),
			'footer'    => $this->load->view('templates/footer', '', TRUE),
		);
		$this->parser->parse('form_check/index', $template);
	}
	  
	public function getDataFormCheck()
	{	
		if ($this->input->is_ajax_request() == true) {
			$userId = $this->auth->checkUsername($this->session->userdata('username'))->row()->id;
			$list = $this->formcheck->get_datatables($this->session->userdata('username'), $userId);
			// die($this->db->last_query($list));
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {
	 
				$no++;
				$row = array();

				// Membuat tombol
				if ($this->session->userdata('level') == 1 && $field->status_id == 13) {
					$tombolrespon 	= "
					<button type=\"button\" class=\"btn btn-outline-warning\" title=\"Check Form\" onclick=\"check('" . $field->id . "', '" . $field->site_id . "')\">
						<i class=\"fas fa-tasks\"></i>
					</button>";
					$tombolhapus	= '';
				} else if($this->session->userdata('level') == 2 && $field->status_id == 14) {
					$tombolrespon 	= "
					<button type=\"button\" class=\"btn btn-outline-warning\" title=\"Validate Form\" onclick=\"validate('" . $field->id . "', '" . $field->site_id . "')\">
						<i class=\"fas fa-clipboard-check\"></i>
					</button>";
					$tombolhapus	= '';
				} else if($this->session->userdata('level') == 1 && $field->status_id == 17) {
					$tombolrespon 	= "
					<button type=\"button\" class=\"btn btn-outline-warning\" title=\"Recheck Form\" onclick=\"recheck('" . $field->id . "')\">
						<i class=\"fas fa-clipboard-check\"></i>
					</button>";
					$tombolhapus	= '';
				} else if($this->session->userdata('level') == 3 && $field->status_id == 15) {
					$tombolrespon 	= "
					<button type=\"button\" class=\"btn btn-outline-warning\" title=Done\" onclick=\"done('" . $field->id . "', '" . $field->no . "')\">
						<i class=\"fas fa-check-circle\"></i>
					</button>";
					$tombolhapus	=  "<button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->no . "')\">
											<i class=\"far fa-trash-alt\"></i>
										</button>";
				} else if($this->session->userdata('level') == 3 || $this->session->userdata('level') == 4 )  {
					$tombolrespon 	= "";
					$tombolhapus	= "
					  <button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onclick=\"hapus('" . $field->id . "', '" . $field->no . "')\">
						  <i class=\"far fa-trash-alt\"></i>
					  </button>";
				} else {
					$tombolrespon 	= "";
					$tombolhapus	= "";
				}
				
				//status
				$status = "
					<span class=\"badge badge-pill badge-" . $field->badge . "\">" . $field->status . "</span>
				";
			
				// Membuat tombol
				$url = '';
				$url = site_url("FormCheck/laporan_pdf/" . $field->id);
				$tombolpdf = "
					<button type=\"button\" class=\"btn btn-outline-danger\" title=\"Unduh PDF\" onclick=\"pdf('" . $url . "')\">
						<i class=\"far fa-file-pdf\"></i>
					</button>
				";
				$row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
				$row[] = $no;
				$row[] = $field->no;
				$row[] = $field->nama_site;
				$row[] = $status;
				$row[] = date('d F Y', strtotime($field->created_date));
				$row[] = $field->created_by;
				$row[] = $tombolrespon . ' ' . $tombolpdf . ' ' . $tombolhapus;
				$data[] = $row;
			}
	 
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->formcheck->count_all(),
				"recordsFiltered" => $this->formcheck->count_filtered($this->session->userdata('username'), $userId),
				"data" => $data,
			);
			//output dalam format JSON
			echo json_encode($output);
		} else {
			exit('Maaf data tidak bisa ditampilkan');
		}
	}

	public function getSelectEmployee()
	{
		if ($this->input->is_ajax_request() == true) {
			$checklist_id	= $this->input->post('id', TRUE);
			$data         = $this->checklist->getSelectCategory($checklist_id)->result();
			echo json_encode($data);
		} else {
			exit('Maaf data tidak bisa ditampilkan');
		}
	}
	  
	public function formtambahformcheck()
	{
		$data = [
			'site'		=> $this->db->get('site')->result(),
			'superior' 	=> $this->db->get_where('user', ['rank_id' => 2 ])->result(),
			'staff' 	=> $this->db->get_where('user', ['rank_id' => 1 ])->result(),
		];

		if ($this->input->is_ajax_request() == TRUE) {

			if($this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row()->role_id == 2) {
				$msg = [
					'sukses' => $this->load->view('form_check/modaltambah', $data, TRUE)
				];
			} else {
				$msg = [
					'gagal' => 'Just Manager allowed to click this button'
				];
			}
			echo json_encode($msg);
		}
	}
	  
	private function getNoForm($siteId)
    {
        $table = "form_check";
        $field = "no";
        $today = date('Ymd');

		$kodeSite = sprintf('%02s', $siteId);

        $kode       = $kodeSite . '-' . $today;
	
        $lastKode   = getMaxbyDate($kode, $table, $field);

        $noUrut = (int) substr($lastKode, -4, 4);
        $noUrut++;

        return $kode . sprintf('%03s', $noUrut);
    }

	public function simpandataformcheck()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$siteId 		= $this->input->post('site', TRUE);
			$no             = $this->getNoForm($siteId);
			$superiorId1 	= $this->input->post('superior1', TRUE);
			$superiorId2 	= $this->input->post('superior2', TRUE);
			$staffId1 		= $this->input->post('staff1', TRUE);
			$staffId2 		= $this->input->post('staff2', TRUE);
			$start 			= date('Y-m-d', strtotime($this->input->post('startDate', TRUE)));
			$end 			= date('Y-m-d', strtotime($this->input->post('endDate', TRUE)));
			$createdBy  	= $this->session->userdata('username');
	 
			$this->form_validation->set_rules(
				'site',
				'Site',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
					  ]
			);
				
			$this->form_validation->set_rules(
				'superior1',
				'Superior #1',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
					  ]
			);
	 
			$this->form_validation->set_rules(
				'superior2',
				'Superior #2',
				'required|trim',
					[
						'required'  => '%s tidak boleh kosong'
					  ]
			);

			$this->form_validation->set_rules(
				'staff1',
				'Staff #1',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
					  ]
			);

			$this->form_validation->set_rules(
				'staff2',
				'Staff #2',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
					  ]
			);

			$this->form_validation->set_rules(
				'startDate',
				'Date Set (Start Date)',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
					  ]
			);

			$this->form_validation->set_rules(
				'endDate',
				'Date Set (End Date)',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
					  ]
			);
	  
			if ($this->form_validation->run() == TRUE) {
				$this->formcheck->simpan($no, $siteId, $superiorId1, $superiorId2, $staffId1, $staffId2, $start, $end, $createdBy);
	 
				$msg = [
					'sukses' => 'Form check has been saved successfully'
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

		  
	public function hapusformcheck()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id 		 = $this->input->post('id', TRUE);
			$hapus 		 = $this->formcheck->hapus($id);
			$hapusAnswer = $this->db->delete('answer_check', ['form_id' => $id]);

			if ($hapus && $hapusAnswer) {
				$msg = [
					'sukses' => 'Form check has been deleted successfully'
				];
			}
			echo json_encode($msg);
		}
	}

	public function deletemultipleformcheck()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id         = $this->input->post('id', TRUE);
			$jmldata    = count($id);

			$hapusdata  = $this->formcheck->hapusbanyak($id, $jmldata);
			$hapusAnswer = $this->db->delete('answer_check', ['form_id' => $id[0]]);
			if ($hapusdata == TRUE && $hapusAnswer == TRUE) {
				$msg = [
					'sukses' => "$jmldata data item form check deleted successfully"
				];
			}
	  
			echo json_encode($msg);
		} else {
			isLogIn();
		}
	}
	
	  
	public function formchecklist()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id  	 = $this->input->post('id', TRUE);  
	 
			$ambildata = $this->formchecklist->ambildata($id);
	
			if ($ambildata) {
				$row = $ambildata->row_array();
				$data = [
					'id'        => $id,
					'category' 	=> $row['category'],
					'items'		=> $this->formchecklist->getDataItem($row['site_id'])->result_array()
				];
			}
	 
			$msg = [
				'sukses' => $this->load->view('checklist/modalstaff', $data, TRUE)
			];
			echo json_encode($msg);
		}
	}

	public function formrechecklist()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id  	 = $this->input->post('id', TRUE);  
	 
			$ambildata = $this->formchecklist->ambildata($id);
	
			if ($ambildata) {
				$row = $ambildata->row_array();
				$data = [
					'id'        => $id,
					'items'		=> $this->formchecklist->getDataItemValidate($row['site_id'])->result_array()
				];
			}
	 
			$msg = [
				'sukses' => $this->load->view('checklist/modalrecheck', $data, TRUE)
			];
			echo json_encode($msg);
		}
	}

	public function formcheckvalidate()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id  	 = $this->input->post('id', TRUE);  
	 
			$ambildata = $this->formchecklist->ambildata($id);
	
			if ($ambildata) {
				$row = $ambildata->row_array();
				$data = [
					'id'        => $id,
					'items'		=> $this->formchecklist->getDataItemValidate($row['site_id'])->result_array()
				];
			}
	 
			$msg = [
				'sukses' => $this->load->view('checklist/modalsuperior', $data, TRUE)
			];
			echo json_encode($msg);
		}
	}
	
	public function change_check()
    {
		$formId 	= $this->input->post('formId');
		$itemId 	= $this->input->post('itemId');
		$siteId 	= $this->input->post('siteId');
		$categoryId = $this->input->post('categoryId');
		$remark 	= $this->input->post('remark');

		$data = [
			'form_id' 		=> $formId,
			'item_id' 		=> $itemId,
			'site_id' 		=> $siteId,
			'category_id'	=> $categoryId,
			'remark' 		=> $remark
		];

		$result = $this->db->get_where('answer_check', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('answer_check', $data);
        } else {
            $this->db->delete('answer_check', $data);
        }
    }

	public function change_radio()
    {
		$formId		= $this->input->post('formId');
		$siteId		= $this->input->post('siteId');
		$categoryId	= $this->input->post('categoryId');
        $itemId 	= $this->input->post('itemId');
        $remark 	= $this->input->post('remark');
        $value 		= $this->input->post('value');
		
		$where = [
			'form_id' 		=> $formId,
			'site_id' 		=> $siteId,
			'category_id' 	=> $categoryId,
			'item_id' 		=> $itemId
		];

		$data = [
			'remark'	=> $remark,
			'validate'	=> $value,
		];

		$this->db->where($where);
		$query = $this->db->update('answer_check', $data);
		die($this->db->last_query($query));
    }

	public function formeditformcheck()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id  = $this->input->post('id', TRUE);
	 
			$ambildata = $this->checklist->ambildata($id);
	
			$name = array('Software', 'Hardware', 'Application');
			$this->db->where_in('name', $name);
			$categoryLists = $this->db->get('category')->result();
			
			if ($ambildata) {
				$row = $ambildata->row_array();
				$data = [
					'id'            => $id,
					'siteId' 		=> $row['site_id'],
					'categoryId' 	=> $row['category_id'],
					'category' 		=> $categoryLists,
					'site'    		=> $this->site->getData()->result(),
					'name'          => $row['name'],
					'is_active'     => $row['is_active']
				];
			}
	 
			$msg = [
				'sukses' => $this->load->view('checklist/modaledit', $data, TRUE)
			];
			echo json_encode($msg);
		}
	}
	
	public function updateFormCheckList()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id             = $this->input->post('id', TRUE);
			$status     	= $this->input->post('status', TRUE);
			$modifiedBy     = $this->session->userdata('username');
			$modifiedDate   = date("Y-m-d H:i:s");
			
			$this->formchecklist->update($id, $status, $modifiedBy, $modifiedDate);
	  
			$msg = [
				'sukses' => 'Form checklist has been updated successfully !'
			];
	 
			echo json_encode($msg);
		}
	}

	public function doneformcheck()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id = $this->input->post('id', TRUE);
			$done = $this->formcheck->done($id);
	 
			if ($done == TRUE) {
				$msg = [
					'sukses' => 'Form check has been done successfully'
				];
			}
			echo json_encode($msg);
		}
	}

	public function laporan_pdf($id)
	{
		$ambildata = $this->formchecklist->ambildata($id);
	
		if ($ambildata) {
			$row = $ambildata->row_array();
			// var_dump($row);die();
			$data = [
				"title" 	=> 'Laporan Form Checklist',
				"site"  	=> $row['nama_site'],
				"alamat"	=> $row['alamat'],
				"phone"		=> $row['telepon'],
				"logo"		=> $row['logo'],
				"staff1"	=> $this->db->get_where('user', ['id' => $row['staff_id_1']])->row()->name,
				"staff2"	=> $this->db->get_where('user', ['id' => $row['staff_id_2']])->row()->name,
				"superior1"	=> $this->db->get_where('user', ['id' => $row['superior_id_1']])->row()->name,
				"superior2"	=> $this->db->get_where('user', ['id' => $row['superior_id_2']])->row()->name,
				"item"		=> $this->checklist->itemCheckPdf($row['id']),
			];
		}
	
		$this->load->library('pdf');
	
		$this->pdf->setFileName('Nama_file.pdf');
		$this->pdf->setPaper('A4', 'Potrait');
		$this->pdf->loadView('form_check/laporan_pdf', $data);
	
	
	}
	// End Modul Form Check //

}
