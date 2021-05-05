<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apps extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('Modelapps', 'apps');
        $this->load->library('upload');
    }

	// Begin Modul Aplikasi //

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

	public function getDataApps()
	{
		if ($this->input->is_ajax_request() == true) {
			$list = $this->apps->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $field) {

				$no++;
				$row = array();

				// Status
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
				$row[] = date('d F Y', strtotime($field->made_on));
				$row[] = $field->made_by;
				$row[] = $status;
				$row[] = $tomboledit . ' ' . $tombolhapus;
				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->apps->count_all(),
				"recordsFiltered" => $this->apps->count_filtered(),
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
				'sukses' => $this->load->view('aplikasi/modaltambah', '', TRUE)
			];
			echo json_encode($msg);
		}
	}

	public function simpandataapps()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$nama       = ucwords($this->input->post('nama', TRUE));
			$madeBy     = ucwords($this->input->post('madeBy', TRUE));
			$madeOn  	= date("Y-m-d", strtotime($this->input->post('madeOn', TRUE)));
			$bgColor    = $this->input->post('bgColor', TRUE);
			$hBgColor   = $this->input->post('hBgColor', TRUE);
			$createdBy  = $this->session->userdata('username');

			$this->form_validation->set_rules(
				'nama',
				'Application',
				'required|trim|is_unique[aplikasi.name]',
				[
					'required'  => '%s tidak boleh kosong',
					'is_unique' => '%s sudah ada'
				]
			);

			$this->form_validation->set_rules(
				'bgColor',
				'Background Color',
				'required|trim|is_unique[aplikasi.bg_color]',
				[
					'required'  => '%s tidak boleh kosong, buat dashboard survey',
					'is_unique' => '%s sudah ada'
				]
			);

			$this->form_validation->set_rules(
				'hBgColor',
				'Hover Background Color',
				'required|trim|is_unique[aplikasi.hbg_color]',
				[
					'required'  => '%s tidak boleh kosong, buat dashboard survey',
					'is_unique' => '%s sudah ada'
				]
			);

			if ($this->form_validation->run() == TRUE) {
				
				// jika ada gambar yang diupload
				$uploadImage1 = isset($_FILES['image1']) ? $_FILES['image1'] : NULL;
				$uploadImage2 = isset($_FILES['image2']) ? $_FILES['image2'] : NULL;
				$uploadImage3 = isset($_FILES['image3']) ? $_FILES['image3'] : NULL;
				
				if ($uploadImage1 != NULL) {
					$config['allowed_types']    = 'gif|jpg|png';
					$config['file_name']        = $nama . '(1)';
					$config['overwrite']        = TRUE;
					$config['max_size']         = 2048;
					$config['upload_path']      = './assets/img/apps/';
	 
					// var_dump($config['upload_path']);die();

					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if ($this->upload->do_upload('image1')) {
						$new_image1 = $this->upload->data('file_name');
						$this->db->set('image1', $new_image1);
					} else {
						echo $this->upload->display_errors();
					}
				}
				
				if ($uploadImage2 != NULL) {
					$config['allowed_types']    = 'gif|jpg|png';
					$config['file_name']        = $nama . '(2)';
					$config['overwrite']        = TRUE;
					$config['max_size']         = 2048;
					$config['upload_path']      = './assets/img/apps/';
	 
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if ($this->upload->do_upload('image2')) {
						$new_image2 = $this->upload->data('file_name');
						$this->db->set('image2', $new_image2);
					} else {
						echo $this->upload->display_errors();
					}
				}

				if ($uploadImage3 != NULL) {
					$config['allowed_types']    = 'gif|jpg|png';
					$config['file_name']        = $nama . '(3)';
					$config['overwrite']        = TRUE;
					$config['max_size']         = 2048;
					$config['upload_path']      = './assets/img/apps/';
	 
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if ($this->upload->do_upload('image3')) {
						$new_image3 = $this->upload->data('file_name');
						$this->db->set('image3', $new_image3);
					} else {
						echo $this->upload->display_errors();
					}
				}

				$this->apps->simpan($nama, $madeBy, $bgColor, $hBgColor, $madeOn, $createdBy);

				$msg = [
					'sukses' => 'Application has been saved successfully !'
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

	public function formeditapps()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id  = $this->input->post('id', TRUE);

			$ambildata = $this->apps->ambildata($id);

			if ($ambildata) {
				$row = $ambildata->row_array();
				$data = [
					'id'        => $id,
					'nama'      => $row['name'],
					'madeBy'    => $row['made_by'],
					'madeOn'    => $row['made_on'],
					'bgColor'   => $row['bg_color'],
					'hBgColor'  => $row['hbg_color'],
					'is_active' => $row['is_active'],
					'image1' 	=> $row['image1'],
					'image2' 	=> $row['image2'],
					'image3' 	=> $row['image3'],
				];
			}

			$msg = [
				'sukses' => $this->load->view('aplikasi/modaledit', $data, TRUE)
			];
			echo json_encode($msg);
		}
	}

	public function updatedataapps()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id             = $this->input->post('id', TRUE);
			$nama       	= ucwords($this->input->post('nama', TRUE));
			$madeBy     	= ucwords($this->input->post('madeBy', TRUE));
			$madeOn  		= date("Y-m-d", strtotime($this->input->post('madeOn', TRUE)));
			$bgColor    	= $this->input->post('bgColor', TRUE);
			$hBgColor   	= $this->input->post('hBgColor', TRUE);
			$is_active      = $this->input->post('is_active', TRUE);
			$modifiedBy     = $this->session->userdata('username');
			$modifiedDate	= date("Y-m-d H:i:s");

			// jika ada gambar yang diupload
			$uploadImage1	= isset($_FILES['image1']) ? $_FILES['image1'] : NULL;
			$uploadImage2 	= isset($_FILES['image2']) ? $_FILES['image2'] : NULL;
			$uploadImage3 	= isset($_FILES['image3']) ? $_FILES['image3'] : NULL;

			$file_name 		= strtr($nama, array(' ' => '_', '.' => '', '(' => '', ')' => '', '[0-9]' => ''));

			// var_dump($file_name);die();

			if ($uploadImage1 != NULL) {
				$config['allowed_types']    = 'gif|jpg|png';
				$config['file_name']        = $file_name . '(1)';
				$config['overwrite']        = TRUE;
				$config['max_size']         = 2048;
				$config['upload_path']      = './assets/img/apps/';
				
				// var_dump(strtr($config['file_name'], array(' ' => '_')));die();
				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('image1')) {
					$new_image1 = $this->upload->data('file_name');
					$this->db->set('image1', $new_image1);
				} else {
					echo $this->upload->display_errors();
				}
			}
			
			if ($uploadImage2 != NULL) {
				$config['allowed_types']    = 'gif|jpg|png';
				$config['file_name']        = $file_name . '(2)';
				$config['overwrite']        = TRUE;
				$config['max_size']         = 2048;
				$config['upload_path']      = './assets/img/apps/';
 
				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('image2')) {
					$new_image2 = $this->upload->data('file_name');
					$this->db->set('image2', $new_image2);
				} else {
					echo $this->upload->display_errors();
				}
			}

			if ($uploadImage3 != NULL) {
				$config['allowed_types']    = 'gif|jpg|png';
				$config['file_name']        = $file_name . '(3)';
				$config['overwrite']        = TRUE;
				$config['max_size']         = 2048;
				$config['upload_path']      = './assets/img/apps/';
 
				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('image3')) {
					$new_image3 = $this->upload->data('file_name');
					$this->db->set('image3', $new_image3);
				} else {
					echo $this->upload->display_errors();
				}
			}

			$this->apps->update($id, $nama, $madeBy, $bgColor, $hBgColor, $madeOn, $is_active, $modifiedBy, $modifiedDate);

			$msg = [
				'sukses' => 'Application has been updated successfully !'
			];

			echo json_encode($msg);
		}
	}

	public function hapusapps()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id = $this->input->post('id', TRUE);
			$hapus = $this->apps->hapus($id);

			if ($hapus) {
				$msg = [
					'sukses' => 'Application has been deleted successfully'
				];
			}
			echo json_encode($msg);
		}
	}

	public function deletemultipleapps()
	{
		if ($this->input->is_ajax_request() == TRUE) {
			$id         = $this->input->post('id', TRUE);
			$jmldata    = count($id);

			$hapusdata  = $this->apps->hapusbanyak($id, $jmldata);

			if ($hapusdata == TRUE) {
				$msg = [
					'sukses' => "$jmldata Apps has been deleted successfully"
				];
			}

			echo json_encode($msg);
		} else {
			isLogIn();
		}
	}

	// End Modul Aplikasi //

}
