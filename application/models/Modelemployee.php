<?php
class Modelemployee extends CI_Model
{
    var $table      = 'user a'; //nama tabel dari database
    var $joinTable1 = 'department b'; //nama tabel dari database
    var $joinTable2 = 'manager c'; //nama tabel dari database
    var $joinTable3 = 'user_role d'; //nama tabel dari database
    var $joinTable4 = 'rank e'; //nama tabel dari database
    var $column_order = array(null, 'a.nik', 'a.name', 'e.name', 'a.position', 'b.name', 'd.role', 'a.created_date', 'a.is_active', null); //Sesuaikan dengan field
    var $column_search = array('a.nik', 'a.name', 'e.name', 'a.position', 'b.name', 'd.role', 'a.created_date',); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'asc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->select(
            '
                a.id, 
                a.nik, 
                a.name, 
                e.name level, 
                a.username,
                a.position,
                b.name department,
                d.role, 
                a.is_active,  
                a.created_date
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.department_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'b.id = c.id', 'left');
        $this->db->join($this->joinTable3, 'a.role_id = d.id', 'left');
        $this->db->join($this->joinTable4, 'a.rank_id = e.id', 'left');
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

        if ($this->session->userdata('session_id') == 0) {
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

    public function getData()
    {
        $where = $this->filter();
        $this->db->select(
            '
                a.id, 
                a.nik, 
                a.name, 
                a.position, 
                b.name department,
            '
        );
        $this->db->join($this->joinTable1, 'a.department_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'b.id = c.id', 'left');
        $this->db->where($where);
        $this->db->where('a.is_active', 1);
        $query = $this->db->get($this->table);
        return $query;
    }

    public function ambildata($id)
    {
        $this->db->select(
            '
                a.id, 
                a.nik, 
                a.name, 
                a.username,
                a.email,
                a.mobile,
                a.rank_id,
                a.position,
                a.department_id,
                b.name department,
                a.role_id, 
                d.role, 
                a.is_active,  
                a.created_date
            '
        );
        $this->db->from($this->table);
        $this->db->join($this->joinTable1, 'a.department_id = b.id', 'left');
        $this->db->join($this->joinTable2, 'b.id = c.id', 'left');
        $this->db->join($this->joinTable3, 'a.role_id = d.id', 'left');
        $this->db->where('a.id', $id);
        return $this->db->get();
    }

    public function update($id, $nama, $department_id, $level, $position, $mobile, $role_id, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'           => $nama,
            'department_id'  => $department_id,
            'rank_id'        => $level,
            'position'       => $position,
            'mobile'         => $mobile,
            'role_id'        => $role_id,
            'is_active'      => $is_active,
            'modified_by'    => $modifiedBy,
            'modified_date'  => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('user', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('user', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('user', ['id' => $id[$i]]);
        }

        return TRUE;
    }

    public function memberICT()
    {
        $this->db->select('COUNT(a.nik) nik');
        return $this->db->get_where('user a', ['a.is_active' => 1, 'a.department_id' => 1]); //ICT
    }

    public function checkGroup($role_id)
    {
        $this->db->select(
            '
                a.id, 
                a.nik, 
                a.name, 
                a.username,
                a.email,
                a.mobile,
                a.position,
                a.department_id,
                b.name department,
                a.role_id, 
                d.role
            '
        );
        $this->db->join($this->joinTable1, 'a.department_id = b.id', 'left');
        $this->db->join($this->joinTable3, 'a.role_id = d.id', 'left');
        $query = $this->db->get_where($this->table, ['a.role_id' => $role_id]);
        // die($this->db->last_query($query));  
        return $query->row();
    }
}
