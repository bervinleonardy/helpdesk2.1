<?php
class Modelcategory extends CI_Model
{
    var $table = 'category a'; //nama tabel dari database
    var $joinTable = 'department b'; //nama tabel dari database
    var $joinTable2 = 'subcategory c'; //nama tabel dari database
    var $joinTable3 = 'cases d'; //nama tabel dari database
    var $column_order = array(null, 'a.name', 'b.name', 'a.created_date', 'a.created_by', 'a.is_active', null); //Sesuaikan dengan field
    var $column_search = array('a.name', 'b.name', 'a.created_by', 'a.created_date'); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'desc'); // default order 

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
        $this->db->join($this->joinTable, 'a.department_id = b.id', 'left');

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
        if ($this->session->userdata('session_id') == 0) {
            $group = $this->employee->checkGroup($this->session->userdata('role_id'));
            if ($group->role_id == 1) {
                return '1=1';
            } else {
                return 'b.id= ' . $group->department_id . '';
            }
        } else {
            return '1=1';
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

    public function simpan($department, $nama, $createdBy)
    {
        $simpan = [
            'department_id' => $department,
            'name'          => $nama,
            'created_by'    => $createdBy
        ];
        $this->db->insert('category', $simpan);
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
                a.name, 
                a.department_id,
                b.name department, 
                a.created_by, 
                a.created_date,
                a.is_active
            '
        );
        $this->db->join($this->joinTable, 'a.department_id = b.id', 'left');
        return $this->db->get_where($this->table, ['a.id' => $id]);
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
