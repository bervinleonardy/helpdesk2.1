<?php
class Modelformchecklist extends CI_Model
{
    var $table = 'form_check a'; //nama tabel dari database
    var $joinTable1 = 'site b'; //nama join tabel 1 dari database
    var $joinTable2 = 'checklist c'; //nama join tabel 2 dari database
    var $joinTable3 = 'category d'; //nama join tabel 2 dari database
    var $joinTable4 = 'answer_check e'; //nama join tabel 2 dari database
    var $column_order = array(null, 'a.no', 'b.name', 'c.name', 'a.created_date', 'a.created_by','a.is_active', null); //Sesuaikan dengan field
    var $column_search = array('a.no', 'b.name', 'c.name', 'a.created_date', 'a.created_by'); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'desc'); // default order 

    private function _get_datatables_query()
    {
		$this->db->select('
			a.id,
			a.no,
			a.site_id,
			b.name nama_site,
			c.name status,
			c.badge,
			a.created_date,
			a.created_by
		');
		$this->db->join($this->joinTable1, 'a.site_id = b.id');
		$this->db->join($this->joinTable2, 'a.status_id = c.id');
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

	public function getDataItem($siteId)
    {
		$this->db->select('
			c.site_id,
			c.category_id,
			c.id,
			d.name category,
			c.name description,
			e.remark
		');
		$this->db->join($this->joinTable1, 'b.id = c.site_id', 'left');
		$this->db->join($this->table, 'a.site_id = b.id', 'left');
		$this->db->join($this->joinTable3, 'd.id = c.category_id', 'left');
		$this->db->join($this->joinTable4, 'e.form_id = a.id', 'left', 'left');
		$query = $this->db->get_where($this->joinTable2, ['c.site_id' => $siteId, 'c.is_active' => 1]);
        return $query;
    }

	public function getDataItemValidate($siteId)
    {
		$this->db->select('
			e.form_id id,
			e.site_id,
			e.item_id,
			e.category_id,
			c.name description,
			d.name category,
			d.name category,
			e.remark,
			e.comment,
		');
		$this->db->join($this->joinTable3, 'd.id = e.category_id', 'left');
		$this->db->join($this->joinTable2, 'c.id = e.item_id', 'left');
		$query = $this->db->get_where($this->joinTable4, ['e.site_id' => $siteId]);
		// die($this->db->last_query($query));
        return $query;
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
			b.id site_id,
			d.name category,
			b.name nama_site,
			b.address alamat,
			b.phone telepon,
			b.logo,
			a.superior_id_1,
			a.superior_id_2,
			a.staff_id_1,
			a.staff_id_2,
			c.id item_id,
			c.name item
		');
		$this->db->join($this->joinTable1, 'a.site_id = b.id', 'left');
		$this->db->join($this->joinTable2, 'b.id = c.site_id', 'left');
		$this->db->join($this->joinTable3, 'c.category_id = d.id', 'left');
		$query =  $this->db->get_where($this->table, ['a.id' => $id, 'c.is_active' => 1]);
        return $query;
    }

    public function update($id, $status, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'status_id'   	=> $status,
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

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('form_check', ['id' => $id[$i]]);
        }
        return TRUE;
    }

}
