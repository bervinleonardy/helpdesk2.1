<?php
class Modelrole extends CI_Model
{
    var $table = 'user_role'; //nama tabel dari database
    var $column_order = array(null, 'role', 'created_date', 'is_active', null); //Sesuaikan dengan field
    var $column_search = array('role', 'created_date'); //field yang diizin untuk pencarian 
    var $order = array('created_date' => 'DESC'); // default order 

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

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

    public function simpan($role, $createdBy)
    {
        $simpan = [
            'role'          => $role,
            'created_by'    => $createdBy
        ];
        $this->db->insert('user_role', $simpan);
    }

    public function ambildata($id)
    {
        return $this->db->get_where('user_role', ['id' => $id]);
    }

    public function update($id, $role, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'role'           => $role,
            'is_active'      => $is_active,
            'modified_by'    => $modifiedBy,
            'modified_date'  => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('user_role', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('user_role', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('user_role', ['id' => $id[$i]]);
        }

        return TRUE;
    }

    public function getRole()
    {
        $this->db->where('id !=', 1);
        return $this->db->get('user_role')->row_array();
    }
}
