<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ict extends CI_Controller
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

    // Begin Modul Form Request //

    public function form_request()
    {
        $data['user']    = $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['title']   = 'Form Request';
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('ict/form_request/content', $data, TRUE),
            'modals'    => $this->load->view('ict/form_request/modals', $data, TRUE),
            'script'    => $this->load->view('ict/form_request/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('manager/index', $template);
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
                $status = "
                 <span class=\"badge badge-pill badge-" . $field->badge . "\">" . $field->status . "</span>
                ";

                $formRespon = "
                <button type=\"button\" class=\"btn btn-outline-warning\" title=\"Edit Data\" onclick=\"formrespon('" . $field->id . "')\">
                    <i class=\"far fa-edit\"></i>
                </button>";

                $tombollihat = "
                <button type=\"button\" class=\"btn btn-outline-info\" title=\"Lihat\" onclick=\"lihat('" . $field->id . "')\">
                    <i class=\"far fa-eye\"></i>
                </button>";

                if ($field->status_id == 1) {
                    $check = "";
                    $tomboledit = $tombollihat;
                } else if ($field->status_id == 1 || $field->status_id == 8) {
                    $check = "";
                    $tomboledit = $formRespon;
                } else if ($field->status_id == 10) {
                    $check = "";
                    $tomboledit = "";
                } else {
                    $check = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                    $tomboledit = '';
                }

                $row[]  = $check;
                $row[]  = $no;
                $row[]  = $field->nama;
                $row[]  = $field->departemen;
                $row[]  = $status;
                $row[]  = date('d F Y', strtotime($field->created_date));
                $row[]  = $tomboledit;
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

    // End Module Form Request //

	// Begin Modul Checklist //

	public function checklist()
	{
		$data = [
			'user'	=> $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
			'title' 	=> 'Checklists Site',
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
  
	public function getDataCheckList()
	{
		if ($this->input->is_ajax_request() == true) {
			$list = $this->checklist->get_datatables();
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
  
				// Shorten String

				if (strlen($field->name) >= 30) {
					$description = substr($field->name, 0, 30). "... ";
				}
				else {
					$description = $field->name;
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
				$row[] = $field->nama_site;
				$row[] = $field->nama_category;
				$row[] = $description;
				$row[] = date('d F Y', strtotime($field->created_date));
				$row[] = $field->created_by;
				$row[] = $status;
				$row[] = $tomboledit . ' ' . $tombolhapus;
				$data[] = $row;
			}
  
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->checklist->count_all(),
				"recordsFiltered" => $this->checklist->count_filtered(),
				"data" => $data,
			);
			//output dalam format JSON
			echo json_encode($output);
		} else {
			exit('Maaf data tidak bisa ditampilkan');
		}
	}
  
	public function getSelectApps()
	{
		if ($this->input->is_ajax_request() == true) {
			$checklist_id	= $this->input->post('id', TRUE);
			$data         = $this->checklist->getSelectCategory($checklist_id)->result();
			echo json_encode($data);
		} else {
			exit('Maaf data tidak bisa ditampilkan');
		}
	}
  
	public function formtambahchecklist()
	{
  
		$name = array('Software', 'Hardware', 'Application');
		$this->db->where_in('name', $name);
		$categoryLists = $this->db->get('category')->result();

		$data = [
			'site' 		=> $this->site->getData()->result(),
			'category' 	=> $categoryLists
		];

		if ($this->input->is_ajax_request() == TRUE) {
			$msg = [
				'sukses' => $this->load->view('checklist/modaltambah', $data, TRUE)
		];
			echo json_encode($msg);
		}
	}
  
	public function simpandatachecklist()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$siteId 	= $this->input->post('site', TRUE);
			$categoryId = $this->input->post('category', TRUE);
			$name       = ucwords($this->input->post('name', TRUE));
			$createdBy  = $this->session->userdata('username');
  
			$this->form_validation->set_rules(
				'site',
				'Site',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
				  	]
			);
			
			$this->form_validation->set_rules(
				'category',
				'Category',
				'required|trim',
					[
						'required'  => '%s harus dipilih'
				  	]
			);
  
			$this->form_validation->set_rules(
				'name',
				'Checklist',
				'required|trim',
					[
						'required'  => '%s tidak boleh kosong'
				  	]
			);
  
			if ($this->form_validation->run() == TRUE) {
				$this->checklist->simpan($siteId, $categoryId, $name, $createdBy);
  
				$msg = [
					'sukses' => 'item checklist has been saved successfully'
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
  
	public function updatedatachecklist()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id             = $this->input->post('id', TRUE);
			$siteId     	= $this->input->post('site', TRUE);
			$categoryId    	= $this->input->post('category', TRUE);
			$name           = $this->input->post('name', TRUE);
			$is_active      = $this->input->post('is_active', TRUE);
			$modifiedBy     = $this->session->userdata('username');
			$modifiedDate   = date("Y-m-d H:i:s");
  
			$this->checklist->update($id, $siteId, $categoryId, $name, $is_active, $modifiedBy, $modifiedDate);
  
			$msg = [
				'sukses' => 'item checklist has been updated successfully !'
			];
  
			echo json_encode($msg);
		}
	}
  
	public function hapuschecklist()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id = $this->input->post('id', TRUE);
			$hapus = $this->checklist->hapus($id);
  
			if ($hapus) {
				$msg = [
					'sukses' => 'item check deleted successfully'
				];
			}
			echo json_encode($msg);
		}
	}
  
	public function deletemultiplechecklist()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id         = $this->input->post('id', TRUE);
			$jmldata    = count($id);
  
			$hapusdata  = $this->checklist->hapusbanyak($id, $jmldata);
  
			if ($hapusdata == TRUE) {
				$msg = [
					'sukses' => "$jmldata data item checklist deleted successfully"
				];
			}
  
			echo json_encode($msg);
		} else {
			isLogIn();
		}
	}
	// End Modul Checklist //
}
