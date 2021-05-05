<?php
class Modelmanager extends CI_Model
{
    var $table          = 'manager a'; //nama tabel dari database
    var $joinTable      = 'department b'; // tabel join
    var $column_order   = array(null, 'name', 'department_id', 'created_date', 'created_by', 'is_active', null); //Sesuaikan dengan field
    var $column_search  = array('a.name', 'a.created_date', 'a.created_by', 'b.name',); //field yang diizin untuk pencarian 
    var $order          = array('a.created_date' => 'asc'); //default order 

    private function _get_datatables_query()
    {

        $this->db->select(
            '
                a.id, 
                a.name, 
                b.name department, 
                a.created_by, 
                a.created_date,
                a.is_active
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable, 'a.department_id = b.id');

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

    public function getSelectManager($category_id)
    {
        return $this->db->get_where('manager', ['is_active' => '1', 'category_id' => $category_id]);
    }

    public function simpan($nama, $departmentId, $email, $mobile, $createdBy)
    {
        $simpan = [
            'name'          => $nama,
            'department_id' => $departmentId,
            'email'         => $email,
            'mobile'        => $mobile,
            'created_by'    => $createdBy
        ];
        $this->db->insert('manager', $simpan);
    }

    public function getData()
    {
        return $this->db->get_where('manager', ['is_active' => '1']);
    }

    public function ambildata($id)
    {
        return $this->db->get_where('manager', ['id' => $id]);
    }

    public function update($id, $nama, $departmentId, $email, $mobile, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'           => $nama,
            'department_id'  => $departmentId,
            'email'          => $email,
            'mobile'         => $mobile,
            'is_active'      => $is_active,
            'modified_by'    => $modifiedBy,
            'modified_date'  => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('manager', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('manager', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('manager', ['id' => $id[$i]]);
        }

        return TRUE;
    }
}
