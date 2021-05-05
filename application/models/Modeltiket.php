<?php
class Modeltiket extends CI_Model
{
    var $table          = 'tiket a'; //nama tabel dari database
    var $joinTable1     = 'user b'; //tabel join 1
    var $joinTable2     = 'category c'; //tabel join 2
    var $joinTable3     = 'subcategory d'; //tabel join 3
    var $joinTable4     = 'cases e'; //tabel join 4
    var $joinTable5     = 'status f'; //tabel join 5
    var $joinTable6     = 'severity g'; //tabel join 6
    var $joinTable7     = 'department h'; //tabel join 7
    var $joinTable8     = 'manager i'; //tabel join 8
    var $column_order   = array(null, 'tiket', 'category', 'subcategory', 'case', 'a.subject', 'a.created_by', 'karyawan', 'a.created_date', 'status', 'a.progress', null); //Sesuaikan dengan field
    var $column_search  = array('a.subject', 'a.created_date', 'b.name', 'c.name', 'd.name', 'e.name', 'a.created_by', 'f.name'); //field yang diizin untuk pencarian 
    var $order          = array('a.created_date' => 'DESC'); // default order 

    private function _get_datatables_query()
    {
        $this->db->select(
            '
                a.id, 
                a.no tiket, 
                c.name category,
                d.name subcategory,
                e.name case,
                a.subject, 
                b.name karyawan, 
                a.created_by, 
                a.created_date,
                a.status_id,
                f.name status,
                f.badge,
                a.progress
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.user_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'left');
        $this->db->join($this->joinTable3, 'a.subcategory_id = d.id', 'left');
        $this->db->join($this->joinTable4, 'a.case_id = e.id', 'left');
        $this->db->join($this->joinTable5, 'a.status_id = f.id', 'left');
        $this->db->where('a.creator_id', $this->session->userdata('nik'));

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

    private function _get_datatables_query_manager()
    {
        $this->db->select(
            '
                a.id, 
                a.no tiket, 
                c.name category,
                d.name subcategory,
                e.name case,
                h.name department,
                a.subject, 
                a.nama_creator creator, 
                b.name karyawan, 
                a.created_by, 
                a.created_date,
                f.name status,
                f.badge,
                a.status_id statusId,
                a.progress
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.user_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'left');
        $this->db->join($this->joinTable3, 'a.subcategory_id = d.id', 'left');
        $this->db->join($this->joinTable4, 'a.case_id = e.id', 'left');
        $this->db->join($this->joinTable5, 'a.status_id = f.id', 'left');
        $this->db->join($this->joinTable7, 'c.department_id = h.id', 'left');
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

    private function _get_datatables_query_karyawan()
    {
        $this->db->select(
            '
                a.id, 
                a.no tiket, 
                c.name category,
                d.name subcategory,
                e.name case,
                a.subject, 
                a.nama_creator creator, 
                b.name karyawan, 
                a.created_by, 
                a.created_date,
                f.name status,
                f.badge,
                a.status_id statusId,
                a.progress
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.user_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'left');
        $this->db->join($this->joinTable3, 'a.subcategory_id = d.id', 'left');
        $this->db->join($this->joinTable4, 'a.case_id = e.id', 'left');
        $this->db->join($this->joinTable5, 'a.status_id = f.id', 'left');
        $where = "a.status_id != 1 AND b.nik = " .  $this->session->userdata('nik') . "";
        $this->db->where($where);

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

    private function filterTicket()
    {
        $group = $this->employee->checkGroup($this->session->userdata('role_id'));

        if ($group->role_id == 1) {
            return '1=1';
        } else {
            return 'c.department_id= ' . $group->department_id . '';
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

    function get_datatables_manager()
    {
        $where = $this->filterTicket();
        $this->db->where($where);
        $this->_get_datatables_query_manager();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
		// die($this->db->last_query($query->result()));
        return $query->result();
    }

    function get_datatables_karyawan()
    {
		// $where = $this->filterTicket();
        // $this->db->where($where);
        $this->_get_datatables_query_karyawan();
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

    function count_filtered_manager()
    {
        $this->_get_datatables_query_manager();
        $query = $this->db->get();
		// $query = $query->num_rows();
		// die($this->db->last_query($query));
        return $query->num_rows();
    }

    function count_filtered_karyawan()
    {
        $this->_get_datatables_query_karyawan();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function simpan($no, $category, $subcategory, $case, $severity, $subject, $user_id, $keterangan, $creator_id, $created_by, $nama_creator, $creatorEmail)
    {
        $simpan = [
            'no'                => $no,
            'category_id'       => $category,
            'subcategory_id'    => $subcategory,
            'case_id'           => $case,
            'severity_id'       => $severity,
            'subject'           => $subject,
            'user_id'           => $user_id,
            'keterangan'        => $keterangan,
            'creator_id'        => $creator_id,
            'created_by'        => $created_by,
            'nama_creator'      => $nama_creator,
            'creator_email'     => $creatorEmail
        ];
        $this->db->insert('tiket', $simpan);
    }

    public function ambildata($id)
    {
        $this->db->select(
            '
                a.id, 
                a.no tiket, 
                a.keterangan,
                a.keterangan_karyawan,
                a.keterangan_manager,
                a.category_id,
                c.name category,
                a.subcategory_id,
                d.name subcategory,
                a.case_id,
                e.name case,
                a.subject, 
                a.user_id karyawan_id, 
                b.name karyawan, 
                b.email karyawan_email, 
                h.name group, 
                i.email group_email, 
                a.creator_id, 
                a.nama_creator, 
                a.creator_email, 
                a.created_by, 
                a.created_date,
                a.status_id,
                f.name status,
                f.badge,
                a.progress,
                a.severity_id,
                g.name severity,
                a.start_date startDate,
                a.end_date endDate,
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.user_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'left');
        $this->db->join($this->joinTable3, 'a.subcategory_id = d.id', 'left');
        $this->db->join($this->joinTable4, 'a.case_id = e.id', 'left');
        $this->db->join($this->joinTable5, 'a.status_id = f.id', 'left');
        $this->db->join($this->joinTable6, 'a.severity_id = g.id', 'left');
        $this->db->join($this->joinTable7, 'b.department_id = h.id', 'left');
        $this->db->join($this->joinTable8, 'h.id = i.department_id', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return $query;
    }

    public function doughnutChart()
    {
        $this->db->select(
            '
                "f"."id", 
                "f"."name" "status", 
                "f"."color",
                "f"."hover_color",
                COUNT(a.status_id) qty
            '
        );
        $this->db->join($this->joinTable5, 'a.status_id = f.id', 'left');
        $this->db->group_by(array("f.id", "f.name", "f.color", "f.hover_color"));
        return $this->db->get_where($this->table, ['f.is_active' => 1]);
    }

    public function barChart()
    {
        $this->db->select(
            '
                b.name,
                COUNT(a.no) AS total_tiket
            '
        );
        $this->db->join($this->joinTable1, 'a.user_id = b.id', 'left');
        $this->db->group_by('b.name');
        $where = "a.is_active = 1 AND a.status_id <> 9 AND a.status_id <> 1";
        $this->db->where($where);
        return $this->db->get($this->table);
    }

    public function areaChart()
    {
        $this->db->select(
            "
                FORMAT(a.created_date, 'MMM') as bulan,
                COUNT(a.no) AS total
            "
        );
        $this->db->group_by("FORMAT(a.created_date, 'MMM')");
        $where = "
            a.is_active = 1 
            AND a.status_id <> 9 
            AND a.status_id <> 1 
            AND a.created_date BETWEEN '2021-01-01' AND '2021-12-31'
        ";
        $this->db->where($where);
        return $this->db->get($this->table);
    }

    public function update($id, $keterangan, $status, $modifiedBy, $modifiedDate)
    {
        $update = [
            'keterangan'    => $keterangan,
            'status_id'     => $status,
            'modified_by'   => $modifiedBy,
            'modified_date' => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('tiket', $update);
    }

    public function updateEmployee($id, $keteranganEmployee, $status, $modifiedBy, $modifiedDate)
    {
        $update = [
            'keterangan_karyawan'   => $keteranganEmployee,
            'status_id'             => $status,
            'modified_by'           => $modifiedBy,
            'modified_date'         => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('tiket', $update);
    }

    public function transfer($id, $karyawan, $keteranganEmployee, $status, $modifiedBy, $modifiedDate)
    {
        $update = [
            'user_id'               => $karyawan,
            'keterangan_karyawan'   => $keteranganEmployee,
            'status_id'             => $status,
            'modified_by'           => $modifiedBy,
            'modified_date'         => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('tiket', $update);
    }

    public function responTransfer($id, $karyawan, $keteranganManager, $status, $modifiedBy, $modifiedDate)
    {
        $update = [
            'user_id'               => $karyawan,
            'keterangan_manager'    => $keteranganManager,
            'status_id'             => $status,
            'modified_by'           => $modifiedBy,
            'modified_date'         => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('tiket', $update);
    }

    public function updateProgress($id, $keteranganEmployee, $status, $progress, $modifiedBy, $modifiedDate)
    {
        $update = [
            'keterangan_karyawan'   => $keteranganEmployee,
            'progress'              => $progress,
            'status_id'             => $status,
            'modified_by'           => $modifiedBy,
            'modified_date'         => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('tiket', $update);
    }

    public function reject($id, $keteranganManager, $status, $modifiedBy, $modifiedDate)
    {
        $update = [
            'keterangan_manager'    => $keteranganManager,
            'status_id'             => $status,
            'modified_by'           => $modifiedBy,
            'modified_date'         => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('tiket', $update);
    }

    public function assign($id, $severity, $startDate, $endDate, $category, $subcategory, $case, $karyawan, $keteranganManager, $status, $modifiedBy, $modifiedDate, $responseTime)
    {
        $update = [
            'severity_id'           => $severity,
            'start_date'            => $startDate,
            'end_date'              => $endDate,
            'category_id'           => $category,
            'subcategory_id'        => $subcategory,
            'case_id'               => $case,
            'user_id'               => $karyawan,
            'keterangan_manager'    => $keteranganManager,
            'status_id'             => $status,
            'modified_by'           => $modifiedBy,
            'modified_date'         => $modifiedDate,
            'response_time'         => $responseTime
        ];
        $this->db->where('id', $id);
        $this->db->update('tiket', $update);
    }

    public function closebanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $data = ['status_id' => 8];
            $this->db->update('tiket', $data, array('id' => $id[$i]));
        }
        return TRUE;
    }

    public function totalTiket()
    {
        $this->db->select('COUNT(a.no) tiket');
        $this->db->where('a.status_id <> 10');
        return $this->db->get_where($this->table, ['a.is_active' => 1]);
    }

    public function waiting()
    {
        $this->db->select('COUNT(a.no) tiket');
        return $this->db->get_where($this->table, ['a.is_active' => 1, 'a.status_id' => 1]);
    }

    public function closed()
    {
        $this->db->select('COUNT(a.no) tiket');
        return $this->db->get_where($this->table, ['a.is_active' => 1, 'a.status_id' => 8]);
    }

    public function export($startDate, $endDate)
    {
        $query      = $this->db->query("EXEC sp_reportHelpdesk @start = '$startDate', @end = '$endDate'");
        return $query->result();
    }
}
