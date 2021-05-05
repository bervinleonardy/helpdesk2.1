<?php
class Modelformrequest extends CI_Model
{
    var $table = 'form_request a'; //nama tabel dari database
    var $joinTable1 = 'status b'; //nama tabel dari database
    var $joinTable2 = 'user c'; //nama tabel dari database
    var $joinTable3 = 'department d'; //nama tabel dari database
    var $column_order = array(null, 'a.nama', 'a.departemen', 'b.name', null); //Sesuaikan dengan field
    var $column_search = array('a.nama', 'a.created_date'); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'desc'); // default order 

    private function _get_datatables_query()
    {
        $this->db->select(
            '
                a.id, 
                a.nama, 
                a.departemen, 
                a.status_req, 
                a.status_id, 
                b.name status,
                b.badge,
                a.created_date
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.status_id = b.id', 'left');

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function simpan(
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
    ) {
        $simpan = [
            'tanggal'                   => $tanggal,
            'nama'                      => $nama,
            'nik'                       => $nik,
            'posisi'                    => $position,
            'ditujukan_ke'              => $ditujukanKe,
            'departemen'                => $department,
            'lokasi'                    => $lokasi,
            'employee_status'           => $statEmp,
            'no_aset'                   => $noAset,
            'tgl_dibutuhkan'            => $tglDibutuhkan,
            'status_req'                => $statReq,
            'akun_user'                 => $akunUser,
            'detail_aset'               => $detailAset,
            'lainnya_detail_aset'       => $lainnyaDetailAset,
            'detail_peralatan1'         => $detailPeralatan1,
            'detail_peralatan2'         => $detailPeralatan2,
            'detail_peralatan3'         => $detailPeralatan3,
            'detail_peralatan4'         => $detailPeralatan4,
            'detail_peralatan5'         => $detailPeralatan5,
            'detail_peralatan6'         => $detailPeralatan6,
            'lainnya_detail_peralatan'  => $lainnyaDetailPeralatan,
            'justifikasi_bisnis'        => $justifikasiBisnis,
            'software1'                 => $software1,
            'software2'                 => $software2,
            'software3'                 => $software3,
            'software4'                 => $software4,
            'software5'                 => $software5,
            'software6'                 => $software6,
            'software7'                 => $software7,
            'software8'                 => $software8,
            'software9'                 => $software9,
            'software10'                => $software10,
            'software11'                => $software11,
            'software12'                => $software12,
            'software13'                => $software13,
            'software14'                => $software14,
            'software15'                => $software15,
            'lainnya_software1'         => $lainnyaSoftwares1,
            'lainnya_software2'         => $lainnyaSoftwares2,
            'lainnya_software3'         => $lainnyaSoftwares3,
            'lainnya_software4'         => $lainnyaSoftwares4,
            'lainnya_software5'         => $lainnyaSoftwares5,
            'lainnya_software6'         => $lainnyaSoftwares6,
            'koneksi_jaringan1'         => $koneksiJaringan1,
            'koneksi_jaringan2'         => $koneksiJaringan2,
            'koneksi_jaringan3'         => $koneksiJaringan3,
            'folder_sharing'            => $folderSharing,
            'file_path'                 => $filePath,
            'tipe_akses1'               => $tipeAkses1,
            'tipe_akses2'               => $tipeAkses2,
            'akses_telp1'               => $aksesTelepon1,
            'akses_telp2'               => $aksesTelepon2,
            'akses_telp3'               => $aksesTelepon3,
            'akses_telp4'               => $aksesTelepon4,
            'lainnya_akses_telp'        => $lainnyaAksesTelepon,
            'informasi_lainnya'         => $informasiLainnya,
            'created_by'                => $this->session->userdata('name')
        ];
        $this->db->insert('form_request', $simpan);
    }

    public function getSelectCategory($category_id)
    {
        $this->db->select(
            '
                a.id, 
                a.name, 
                c.id subcategoryId, 
                c.name subcategory,
                b.name department 
            '
        );
        $this->db->join($this->joinTable2, 'a.id = c.category_id', 'inner');
        $this->db->join($this->joinTable, 'a.department_id = b.id', 'left');
        $this->db->order_by('a.department_id ', 'ASC');
        return $this->db->get_where($this->table, ['a.is_active' => '1', 'c.category_id' => $category_id]);
    }

    public function getData()
    {
        $where = $this->filter();
        $this->db->where($where);
        $this->db->select(
            '
                a.id, 
                a.name, 
                b.id departmentId,
                b.name department
            '
        );
        $this->db->join('department b', 'a.department_id = b.id', 'left');
        $this->db->order_by('a.department_id ', 'ASC');
        return $this->db->get('category a');
    }

    public function ambildata($id)
    {
        $this->db->select(
            '
                a.id, 
                a.tanggal, 
                a.nama,
                a.nik, 
                a.posisi, 
                a.ditujukan_ke,
                a.departemen,
                a.lokasi,
                a.employee_status,
                a.no_aset,
                a.tgl_dibutuhkan,
                a.status_req,
                a.akun_user,
                a.detail_aset,
                a.lainnya_detail_aset,
                a.detail_peralatan1,
                a.detail_peralatan2,
                a.detail_peralatan3,
                a.detail_peralatan4,
                a.detail_peralatan5,
                a.detail_peralatan6,
                a.lainnya_detail_peralatan,
                a.justifikasi_bisnis,
                a.software1,
                a.software2,
                a.software3,
                a.software4,
                a.software5,
                a.software6,
                a.software7,
                a.software8,
                a.software9,
                a.software10,
                a.software11,
                a.software12,
                a.software13,
                a.software14,
                a.software15,
                a.lainnya_software1,
                a.lainnya_software2,
                a.lainnya_software3,
                a.lainnya_software4,
                a.lainnya_software5,
                a.lainnya_software6,
                a.koneksi_jaringan1,
                a.koneksi_jaringan2,
                a.koneksi_jaringan3,
                a.folder_sharing,
                a.file_path,
                a.tipe_akses1,
                a.tipe_akses2,
                a.akses_telp1,
                a.akses_telp2,
                a.akses_telp3,
                a.akses_telp4,
                a.lainnya_akses_telp,
                a.informasi_lainnya,
                a.approval_id,
                a.approval_name,
                a.ict_id,
                a.ict_name,
                a.signature_user,
                a.signature_approval,
                a.signature_ict,
                a.created_by,
                a.created_date,
                a.status_id,
                b.name status,
                c.department_id,
                d.name department,
            '
        );
        $this->db->join($this->joinTable1, 'a.status_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'a.nik = c.nik', 'left');
        $this->db->join($this->joinTable3, 'c.department_id = d.id', 'left');
        $query = $this->db->get_where($this->table, ['a.id' => $id]);
        // die($this->db->last_query($query));
        return $query;
    }

    public function update($id, $department, $nama, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'           => $nama,
            'department_id'  => $department,
            'is_active'      => $is_active,
            'modified_by'    => $modifiedBy,
            'modified_date'  => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('category', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('category', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('category', ['id' => $id[$i]]);
        }

        return TRUE;
    }
}
