<?php
class Modellogtiket extends CI_Model
{
    public function ambildatatransfer($id)
    {
        $this->db->select(
            '
                a.id, 
                a.no tiket, 
                a.category_id,
                a.subcategory_id,
                a.case_id,
                a.subject, 
                a.nama_creator creator, 
                a.user_id,
                b.name karyawan, 
                a.created_by, 
                a.created_date,
                a.status_id statusId,
                a.progress
            '
        );
        $this->db->from('log_tiket a');
        $this->db->join('user b', 'a.user_id = b.id', 'left');
        $where = "a.id = " . $id . " AND status_id = '3'";
        $query = $this->db->where($where);
        $this->db->order_by('a.id_log', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query;
    }

    public function ambilidlog($no)
    {
        $this->db->select('id_log');
        $this->db->order_by('id_log', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get_where('log_tiket', array('no' => $no));
        return $query->row()->id_log;
    }

    public function updateKeterangan($id_log, $keterangan)
    {
        $field = array(
            'keterangan'    => $keterangan
        );
        $this->db->where('id_log', $id_log);
        return $this->db->update('log_tiket', $field);
    }

    public function updateKeteranganManager($id_log, $keteranganManager)
    {
        $field = array(
            'keterangan_manager'    => $keteranganManager
        );
        $this->db->where('id_log', $id_log);
        return $this->db->update('log_tiket', $field);
    }

    public function updateKeteranganEmployee($id_log, $keteranganEmployee)
    {
        $field = array(
            'keterangan_karyawan'   => $keteranganEmployee
        );
        $this->db->where('id_log', $id_log);
        return $this->db->update('log_tiket', $field);
    }

    public function getTimelineDate($no, $creator_id)
    {
        $this->db->select(
            "
            CASE 
                WHEN a.status_id IN (1, 5, 11) THEN a.created_by 
                WHEN a.status_id = 8 AND a.modified_by = a.created_by THEN a.created_by 
                WHEN a.status_id = 3 OR a.status_id = 9 OR a.status_id = 10 THEN a.modified_by 
                WHEN a.modified_by = c.username THEN a.modified_by 
                ELSE 'none' 
            END AS nama, 
            b.name status,
            CASE 
                WHEN a.modified_date IS NULL THEN a.created_date 
                WHEN a.modified_date IS NOT NULL THEN a.modified_date 
                ELSE ''
            END AS tanggal, 
            CASE 
                WHEN a.keterangan IS NOT NULL THEN a.keterangan 
                WHEN a.keterangan_manager IS NOT NULL THEN a.keterangan_manager 
                WHEN a.keterangan_karyawan IS NOT NULL THEN a.keterangan_karyawan 
                ELSE '' 
            END AS description 
        "
        );
        $this->db->from('log_tiket a');
        $this->db->join('status b', 'a.status_id = b.id', 'left');
        $this->db->join('user c', 'a.user_id = c.id', 'left');
        $where = "a.no = '$no' AND a.creator_id = '$creator_id'";
        $this->db->where($where);
        $this->db->order_by('a.created_date ', 'DESC');
        $query = $this->db->get();
        return $query;
    }
}
