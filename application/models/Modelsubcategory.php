<?php
class Modelsubcategory extends CI_Model
{
    var $table          = 'subcategory a'; //nama tabel dari database
    var $joinTable1     = 'category b'; // tabel join 1
    var $joinTable2     = 'cases c'; // tabel join 2
    var $joinTable3     = 'department d'; // tabel join 2
    var $column_order   = array(null, 'name', 'category_id', 'created_date', 'created_by', 'is_active', null); //Sesuaikan dengan field
    var $column_search  = array('a.name', 'a.created_date', 'a.created_by', 'b.name', 'd.name'); //field yang diizin untuk pencarian 
    var $order          = array('a.created_date' => 'desc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->select(
            '
                a.id, 
                a.name, 
                b.name category, 
                a.created_by, 
                a.created_date,
                a.is_active,
                d.name department
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.category_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'left');
        $this->db->join($this->joinTable3, 'b.department_id = d.id', 'left');

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

    public function getSelectSubcategory($category_id)
    {
        $this->db->select(
            '
                a.id, 
                a.name,
                c.id caseId, 
                c.name cases
            '
        );
        $this->db->join($this->joinTable2, 'a.id = c.subcategory_id', 'inner');
        return $this->db->get_where($this->table, ['a.is_active' => 1, 'a.category_id' => $category_id]);
    }

    public function getData()
    {
        $this->db->select(
            '
                a.id, 
                a.name, 
                b.name category, 
                a.created_by, 
                a.created_date,
                a.is_active,
                d.name department
            '
        );
        $this->db->join($this->joinTable1, 'a.category_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'a.category_id = c.id', 'left');
        $this->db->join($this->joinTable3, 'b.department_id = d.id', 'left');
        $this->db->order_by('b.department_id ', 'ASC');
        return $this->db->get($this->table);
    }

    public function simpan($nama, $categoryId, $createdBy)
    {
        $simpan = [
            'name'          => $nama,
            'category_id'   => $categoryId,
            'created_by'    => $createdBy
        ];
        $this->db->insert('subcategory', $simpan);
    }

    public function ambildata($id)
    {
        return $this->db->get_where('subcategory', ['id' => $id]);
    }

    public function update($id, $nama, $categoryId, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'           => $nama,
            'category_id'    => $categoryId,
            'is_active'      => $is_active,
            'modified_by'    => $modifiedBy,
            'modified_date'  => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('subcategory', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('subcategory', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('subcategory', ['id' => $id[$i]]);
        }

        return TRUE;
    }
}
