<?php
class Modelchecklist extends CI_Model
{
    var $table = 'checklist a'; //nama tabel dari database
    var $joinTable1 = 'site b'; //nama join tabel 1 dari database
    var $joinTable2 = 'category c'; //nama join tabel 2 dari database
    var $column_order = array(null, 'a.name', 'c.name', 'b.name', 'a.created_date', 'a.created_by','a.is_active', null); //Sesuaikan dengan field
    var $column_search = array('a.name', 'b.name', 'c.name', 'a.created_date', 'a.created_by'); //field yang diizin untuk pencarian 
    var $order = array('a.created_date' => 'desc'); // default order 

    private function _get_datatables_query()
    {
		$this->db->select('
			a.id,
			b.name nama_site,
			c.name nama_category,
			a.name,
			a.is_active,
			a.created_date,
			a.created_by
		');
		$this->db->join($this->joinTable1, 'a.site_id = b.id');
		$this->db->join($this->joinTable2, 'a.category_id = c.id');
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

    public function simpan($siteId, $categoryId, $name, $createdBy)
    {
        $simpan = [
            'name'          => $name,
            'site_id'		=> $siteId,
            'category_id'	=> $categoryId,
            'created_by'    => $createdBy
        ];
        $this->db->insert('checklist', $simpan);
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
        return $this->db->get_where($this->table, ['a.id' => $id]);
    }

	public function itemCheckPdf($formId) {

		$this->db->select("
			a.id,
			a.category_id,
			c.name category,
			a.name description,
			CASE 
			  WHEN e.validate = 0 THEN 'Check'
			  WHEN e.validate = 1 THEN 'Validate'
			  WHEN e.validate = 2 THEN 'Revision'
			  ELSE '-'
			END AS status
		");
		$this->db->join($this->joinTable2, 'e.category_id = c.id', 'left');
		$this->db->join('form_check d', 'd.id = e.item_id', 'left');
		$this->db->join($this->table, 'a.id = e.item_id', 'left');
		$query = $this->db->get_where('answer_check e', ['d.id' => $formId])->result_array();
		// die($this->db->last_query($query));
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
        $this->db->update('checklist', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('checklist', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('checklist', ['id' => $id[$i]]);
        }

        return TRUE;
    }

	public function totalApps()
    {
        $this->db->select('COUNT(id) apps');
        return $this->db->get_where('checklist', ['is_active' => 1]);
    }

}
