<?php
class Modelmenu extends CI_Model
{
    var $table = 'user_menu a'; //nama tabel dari database
    var $joinTable1 = 'user_sub_menu b'; //nama tabel dari join table
    var $column_order = array(null, 'a.menu', 'a.urutan', 'a.created_date', 'a.is_active', null); //Sesuaikan dengan field
    var $column_search = array('a.menu', 'a.urutan', 'a.created_date'); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'desc'); // default order 

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

    public function simpan($menu, $urutan, $createdBy)
    {
        $simpan = [
            'menu'          => $menu,
            'urutan'        => $urutan,
            'created_by'    => $createdBy
        ];
        $this->db->insert('user_menu', $simpan);
    }

    public function getData()
    {
        $this->db->select(
            '
                id, 
                menu, 
                urutan 
            '
        );
        return $this->db->get_where('user_menu', ['a.is_active' => '1']);
    }

    function getSubMenu()
    {
        $this->db->select('a.*,b.menu');
        $this->db->join('user_menu b', 'a.menu_id=b.id', 'LEFT');
        $query = $this->db->get('user_sub_menu a');
        return $query->result_array();
    }

    public function ambildata($id)
    {
        return $this->db->get_where('user_menu', ['id' => $id]);
    }

    public function update($id, $menu, $urutan, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'menu'           => $menu,
            'urutan'         => $urutan,
            'is_active'      => $is_active,
            'modified_by'    => $modifiedBy,
            'modified_date'  => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('user_menu', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('user_menu', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('user_menu', ['id' => $id[$i]]);
        }

        return TRUE;
    }

    public function getMenu()
    {
        $this->db->where('id !=', 1);
        return $this->db->get('user_menu')->result_array();
    }
}
