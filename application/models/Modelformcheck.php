<?php
class Modelformcheck extends CI_Model
{
    var $table = 'form_check a'; //nama tabel dari database
    var $joinTable1 = 'site b'; //nama join tabel 1 dari database
    var $joinTable2 = 'status c'; //nama join tabel 2 dari database
    var $joinTable3 = 'user d'; //nama join tabel 3 dari database
    var $column_order = array(null, 'a.no', 'b.name', 'c.name', 'a.created_date', 'a.created_by','a.is_active', null); //Sesuaikan dengan field
    var $column_search = array('a.no', 'b.name', 'c.name', 'a.created_date', 'a.created_by'); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'desc'); // default order 

    private function _get_datatables_query($createdBy, $userId)
    {
		$this->db->select('
			a.id,
			a.no,
			a.site_id,
			b.name nama_site,
			a.status_id,
			c.name status,
			c.badge,
			a.created_date,
			a.created_by
		');
		$this->db->join($this->joinTable1, 'a.site_id = b.id');
		$this->db->join($this->joinTable2, 'a.status_id = c.id');
        $this->db->from($this->table);
        $this->db->where("a.created_by = '$createdBy' OR (
							a.superior_id_1 = $userId OR 
							a.superior_id_2 = $userId OR 
							a.staff_id_1 = $userId OR 
							a.staff_id_2 = $userId )
						");

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

    function get_datatables($createdBy, $userId)
    {
        $this->_get_datatables_query($createdBy, $userId);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function getData()
    {
        return $this->db->get($this->table);
    }

    function count_filtered($createdBy, $userId)
    {
        $this->_get_datatables_query($createdBy, $userId);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function simpan($no, $siteId, $superiorId1, $superiorId2, $staffId1, $staffId2, $start, $end, $createdBy)
    {
        $simpan = [
            'no'          	=> $no,
            'site_id'		=> $siteId,
            'superior_id_1'	=> $superiorId1,
            'superior_id_2'	=> $superiorId2,
            'staff_id_1'	=> $staffId1,
            'staff_id_2'	=> $staffId2,
            'start_date'	=> $start,
            'end_date'		=> $end,
            'created_by'    => $createdBy
        ];
        $this->db->insert('form_check', $simpan);
    }

    public function ambildata($id)
    {
		$this->db->select('
			a.id,
			a.site_id,
			a.category_id,
			b.name nama_site,
			a.name,
			a.is_active,
			a.created_date,
			a.created_by

		');
		$this->db->join($this->joinTable1, 'a.site_id = b.id');
		$query =  $this->db->get_where($this->table, ['a.id' => $id]);
        return $query;
    }

    public function update($id, $siteId, $categoryId, $name, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'site_id'       => $siteId,
            'category_id'   => $categoryId,
            'name'          => $name,
            'is_active'     => $is_active,
            'modified_by'   => $modifiedBy,
            'modified_date' => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('form_check', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('form_check', ['id' => $id]);
    }

	public function done($id)
    {
        $simpan = [
            'status_id'       => 16,
        ];
        $this->db->where('id', $id);
        $this->db->update('form_check', $simpan);

		return TRUE;
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('form_check', ['id' => $id[$i]]);
        }

        return TRUE;
    }

}
