<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        isLogIn();
        $this->load->model('Modelemployee', 'employee');
        $this->load->model('Modeltiket', 'tiket');
    }

    public function ict()
    {
        $data = [
            'user'      =>  $this->db->get_where('user', ['nik' => $this->session->userdata('nik')])->row_array(),
            'title'     => 'ICT',
            'total'     => $this->tiket->totalTiket()->row_array(),
            'closed'    => $this->tiket->closed()->row_array(),
            'waiting'   => $this->tiket->waiting()->row_array(),
            'member'    => $this->employee->memberICT()->row_array(),
            'hasil'     => $this->tiket->doughnutChart()->result(),
            'bar'       => $this->tiket->barChart()->result(),
            'area'      => $this->tiket->areaChart()->result()
        ];

        $template        = array(
            'header'    => $this->load->view('templates/header', $data, TRUE),
            'sidebar'   => $this->load->view('templates/sidebar', $data, TRUE),
            'topbar'    => $this->load->view('templates/topbar', $data, TRUE),
            'content'   => $this->load->view('dashboard/content', $data, TRUE),
            'modals'    => $this->load->view('templates/modals', $data, TRUE),
            'script'    => $this->load->view('dashboard/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('dashboard/index', $template);
    }

    public function excel()
    {
        // Load plugin PHPExcel nya
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();

        // Settingan awal fil excel
        $excel->getProperties()->setCreator('My Notes Code')
            ->setLastModifiedBy('My Notes Code')
            ->setTitle("Data Helpdesk")
            ->setSubject("Helpdesk")
            ->setDescription("Laporan Semua Tiket Helpdesk ICT Medika Plaza")
            ->setKeywords("Data Tiket");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA TIKET HELPDESK"); // Set kolom A1 dengan tulisan "DATA TIKET HELPDESK"
        $excel->getActiveSheet()->mergeCells('A1:O1'); // Set Merge Cell pada kolom A1 sampai o1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 2
        $excel->setActiveSheetIndex(0)->setCellValue('A2', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B2', "TIKET");
        $excel->setActiveSheetIndex(0)->setCellValue('C2', "NIK CREATOR");
        $excel->setActiveSheetIndex(0)->setCellValue('D2', "CREATOR");
        $excel->setActiveSheetIndex(0)->setCellValue('E2', "KATEGORI");
        $excel->setActiveSheetIndex(0)->setCellValue('F2', "SUB KATEGORI");
        $excel->setActiveSheetIndex(0)->setCellValue('G2', "SEVERITY");
        $excel->setActiveSheetIndex(0)->setCellValue('H2', "SUBJEK");
        $excel->setActiveSheetIndex(0)->setCellValue('I2', "STATUS");
        $excel->setActiveSheetIndex(0)->setCellValue('J2', "NIK KARYAWAN");
        $excel->setActiveSheetIndex(0)->setCellValue('K2', "KARYAWAN");
        $excel->setActiveSheetIndex(0)->setCellValue('L2', "TANGGAL CREATE");
        $excel->setActiveSheetIndex(0)->setCellValue('M2', "WAKTU CREATE");
        $excel->setActiveSheetIndex(0)->setCellValue('N2', "TANGGAL ASSIGN");
        $excel->setActiveSheetIndex(0)->setCellValue('O2', "WAKTU ASSIGN");
        $excel->setActiveSheetIndex(0)->setCellValue('P2', "TANGGAL JADWAL MULAI");
        $excel->setActiveSheetIndex(0)->setCellValue('Q2', "TANGGAL JADWAL SELESAI");
        $excel->setActiveSheetIndex(0)->setCellValue('R2', "TANGGAL RESPON");
        $excel->setActiveSheetIndex(0)->setCellValue('S2', "WAKTU RESPON");
        $excel->setActiveSheetIndex(0)->setCellValue('T2', "TANGGAL CLOSED");
        $excel->setActiveSheetIndex(0)->setCellValue('U2', "Waktu CLOSED");
        $excel->setActiveSheetIndex(0)->setCellValue('V2', "TENGGAT RESPON");
        $excel->setActiveSheetIndex(0)->setCellValue('W2', "WAKTU RESPON");
        $excel->setActiveSheetIndex(0)->setCellValue('X2', "STATUS RESPON");
        $excel->setActiveSheetIndex(0)->setCellValue('Y2', "STATUS RESOLVE");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('P2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('Q2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('R2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('S2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('T2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('U2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('V2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('W2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('X2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('Y2')->applyFromArray($style_col);

        // Panggil function view yang ada di Model untuk menampilkan semua data

        $start      = $this->uri->segment(3);
        $end        = $this->uri->segment(4);

        $startDate  = date("Y-m-d", strtotime($start));
        $endDate    = date("Y-m-d", strtotime($end));

        $tiket      = $this->tiket->export($startDate, $endDate);

        $no         = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow     = 3; // Set baris pertama untuk isi tabel adalah baris ke 3
        foreach ($tiket as $data) { // Lakukan looping pada variabel 

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->no);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->creator_id);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data->nama_creator);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data->category);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->subcategory);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data->severity);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data->subject);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $data->status);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $data->nik_employee);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $data->nama_employee);
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, $data->tanggal_buat);
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, $data->waktu_buat);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $numrow, $data->tanggal_assign);
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, $data->waktu_assign);
            $excel->setActiveSheetIndex(0)->setCellValue('P' . $numrow, $data->start_date);
            $excel->setActiveSheetIndex(0)->setCellValue('Q' . $numrow, $data->end_date);
            $excel->setActiveSheetIndex(0)->setCellValue('R' . $numrow, $data->tanggal_respon);
            $excel->setActiveSheetIndex(0)->setCellValue('S' . $numrow, $data->waktu_respon);
            $excel->setActiveSheetIndex(0)->setCellValue('T' . $numrow, $data->tanggal_closed);
            $excel->setActiveSheetIndex(0)->setCellValue('U' . $numrow, $data->waktu_closed);
            $excel->setActiveSheetIndex(0)->setCellValue('V' . $numrow, $data->response_schedule);
            $excel->setActiveSheetIndex(0)->setCellValue('W' . $numrow, $data->response);
            $excel->setActiveSheetIndex(0)->setCellValue('X' . $numrow, $data->status_response);
            $excel->setActiveSheetIndex(0)->setCellValue('Y' . $numrow, $data->status_resolve);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('N' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('O' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('P' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('Q' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('R' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('S' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('T' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('U' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('V' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('W' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('X' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('Y' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(4); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(12); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // Set width kolom F
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(12); // Set width kolom G
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(40); // Set width kolom H
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(12); // Set width kolom I
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); // Set width kolom J
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(30); // Set width kolom K
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(16); // Set width kolom L
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15); // Set width kolom M
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(17); // Set width kolom N
        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(15); // Set width kolom O
        $excel->getActiveSheet()->getColumnDimension('P')->setWidth(30); // Set width kolom P
        $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(30); // Set width kolom Q
        $excel->getActiveSheet()->getColumnDimension('R')->setWidth(15); // Set width kolom R
        $excel->getActiveSheet()->getColumnDimension('S')->setWidth(15); // Set width kolom S
        $excel->getActiveSheet()->getColumnDimension('T')->setWidth(15); // Set width kolom T
        $excel->getActiveSheet()->getColumnDimension('U')->setWidth(15); // Set width kolom U
        $excel->getActiveSheet()->getColumnDimension('V')->setWidth(25); // Set width kolom V
        $excel->getActiveSheet()->getColumnDimension('W')->setWidth(25); // Set width kolom W
        $excel->getActiveSheet()->getColumnDimension('X')->setWidth(15); // Set width kolom X
        $excel->getActiveSheet()->getColumnDimension('Y')->setWidth(15); // Set width kolom Y
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Ticket Helpdesk Report");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Tiket Helpdesk.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
