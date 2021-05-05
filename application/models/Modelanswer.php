<?php
class Modelanswer extends CI_Model
{
    var $table = 'answer'; //nama tabel dari database
    var $column_order = array(null, 'name', 'is_active','created_date', 'created_by', null); //Sesuaikan dengan field
    var $column_search = array('name', 'created_date'); //field yang diizin untuk pencarian 
    var $order = array('created_date' => 'asc'); // default order 

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
        return $this->db->get($this->table);
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

    public function simpan($nik, $id, $responds, $star, $commentary, $jmldata)
    {
		for ($i = 0; $i < $jmldata; $i++) {
			$simpan = [
				'nik' 			=> $nik,
				'apps_id' 		=> $id[$i],
				'responds'      => $responds[$i],
				'star'       	=> $star[$i],
				'commentary'    => $commentary[$i]
			];
			$this->db->insert($this->table, $simpan);
        }

        return TRUE;
    }

    public function ambildata($id)
    {
        return $this->db->get_where($this->table, ['id' => $id]);
    }

    public function update($id, $nama, $madeBy, $madeOn, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'          => $nama,
            'made_by'	    => $madeBy,
            'made_on'      	=> $madeOn,
            'is_active'     => $is_active,
            'modified_by'   => $modifiedBy,
            'modified_date' => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update($this->table, $simpan);
    }

    public function hapus($nik, $created_date)
    {
        return $this->db->delete($this->table, ['nik' => $nik, 'created_date' => $created_date]);
    }

	public function totalUse()
    {
        $this->db->select('COUNT(id) jumlah');
		// $query = $this->db->get_where($this->table);
		// die($this->db->last_query($query));
        return $this->db->get($this->table);
    }

	public function Use()
    {
        $this->db->select('COUNT(id) jumlah');
		// $query = $this->db->get_where($this->table);
		// die($this->db->last_query($query));
        return $this->db->get_where($this->table, ['responds' => 'Use']);
    }
}
