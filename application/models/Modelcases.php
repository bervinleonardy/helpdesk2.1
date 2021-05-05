<?php
class Modelcases extends CI_Model
{
    var $table          = 'cases a'; //nama tabel dari database
    var $joinTable1     = 'subcategory b'; // tabel 1 join
    var $joinTable2     = 'category c'; // tabel 2 join
    var $joinTable3     = 'department d'; // tabel 2 join
    var $column_order   = array(null, 'a.name', ' b.name', 'c.name', 'd.name', 'a.created_date', 'a.created_by', 'a.is_active', null); //Sesuaikan dengan field
    var $column_search  = array('a.name', 'b.name', 'c.name', 'd.name', 'a.created_date', 'a.created_by'); //field yang diizin untuk pencarian 
    var $order          = array('a.created_date' => 'desc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->select(
            '
                a.id, 
                a.name,
                b.name subcategory,
                c.name category,
                a.created_by, 
                a.created_date,
                d.name department,
                a.is_active
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.subcategory_id = b.id');
        $this->db->join($this->joinTable2, 'a.category_id = c.id');
        $this->db->join($this->joinTable3, 'c.department_id = d.id');

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

    private function filter()
    {
        $group = $this->employee->checkGroup($this->session->userdata('role_id'));

        if ($group->role_id == 1) {
            return '1=1';
        } else {
            return 'd.id= ' . $group->department_id . '';
        }
    }

    function get_datatables()
    {
        $where = $this->filter();
        $this->db->where($where);
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
		// die($this->db->last_query($query->result()));
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

    public function getSelectCases($subcategory_id)
    {
        $this->db->select(
            '
                d.name department, 
                a.id, 
                a.name, 
                b.id subcategoryId, 
                b.name subcategory, 
                c.id categoryId, 
                c.name category 
            '
        );
        $this->db->join($this->joinTable1, 'a.subcategory_id = b.id', 'inner');
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'inner');
        $this->db->join($this->joinTable3, 'c.department_id = d.id', 'left');
        $this->db->order_by('c.department_id ', 'ASC');
        return $this->db->get_where($this->table, ['a.is_active' => '1', 'a.subcategory_id' => $subcategory_id]);
    }

    public function getData()
    {
        $where = $this->filter();
        $this->db->where($where);
        $this->db->select(
            '
                d.name department,
                a.id, 
                a.name
            '
        );
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'inner');
        $this->db->join($this->joinTable3, 'c.department_id = d.id', 'left');
        $this->db->order_by('c.department_id ', 'ASC');
        return $this->db->get_where($this->table, ['a.is_active' => '1']);
    }

    public function simpan($nama, $subCategoryId, $categoryId, $createdBy)
    {
        $simpan = [
            'name'          => $nama,
            'subcategory_id' => $subCategoryId,
            'category_id'   => $categoryId,
            'created_by'    => $createdBy
        ];
        $this->db->insert('cases', $simpan);
    }

    public function ambildata($id)
    {
        return $this->db->get_where('cases', ['id' => $id]);
    }

    public function update($id, $nama, $subCategoryId, $categoryId, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'           => $nama,
            'subcategory_id'    => $subCategoryId,
            'category_id'    => $categoryId,
            'is_active'      => $is_active,
            'modified_by'    => $modifiedBy,
            'modified_date'  => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('cases', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('cases', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('cases', ['id' => $id[$i]]);
        }

        return TRUE;
    }
}
