<?php
class Modelapps extends CI_Model
{
    var $table = 'aplikasi a'; //nama tabel dari database
    var $joinTable1 = 'answer b'; //nama join tabel 1 dari database
    var $column_order = array(null, 'a.name', 'a.is_active','a.created_date', 'a.created_by', null); //Sesuaikan dengan field
    var $column_search = array('a.name', 'a.created_date', 'a.created_by'); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'asc'); // default order 

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

    public function getData()
    {
        return $this->db->get_where($this->table, ['is_active' => 1]);
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

    public function simpan($nama, $madeBy, $bgColor, $hBgColor, $madeOn, $createdBy)
    {
        $simpan = [
            'name'          => $nama,
            'made_by'       => $madeBy,
            'made_on'       => $madeOn,
            'bg_color'      => $bgColor,
            'hbg_color'     => $hBgColor,
            'created_by'    => $createdBy
        ];
        $this->db->insert('aplikasi', $simpan);
    }

    public function ambildata($id)
    {
        return $this->db->get_where($this->table, ['a.id' => $id]);
    }

    public function update($id, $nama, $madeBy, $bgColor, $hBgColor, $madeOn, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'          => $nama,
            'made_by'	    => $madeBy,
            'made_on'      	=> $madeOn,
            'bg_color'      => $bgColor,
            'hbg_color'     => $hBgColor,
            'is_active'     => $is_active,
            'modified_by'   => $modifiedBy,
            'modified_date' => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('aplikasi', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('aplikasi', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('aplikasi', ['id' => $id[$i]]);
        }

        return TRUE;
    }

	public function totalApps()
    {
        $this->db->select('COUNT(id) apps');
        return $this->db->get_where('aplikasi', ['is_active' => 1]);
    }

	public function usePieChart($start, $end)
    {
        $this->db->select(
            '
                a.id, 
                a.name, 
                a.bg_color,
                a.hbg_color,
				COUNT(b.id) qty
            '
        );
        $this->db->join($this->joinTable1, 'a.id = b.apps_id', 'inner');
		$this->db->where("b.created_date BETWEEN '$start' AND '$end'");
        $this->db->group_by(array("a.id", "a.name", "a.bg_color", "a.hbg_color"));
		$query = $this->db->get_where($this->table, ['a.responds' => 'Use']);
		// die($this->db->last_query($query));
		// var_dump($query->result());
        return $query->result();
    }
}
