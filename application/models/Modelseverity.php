<?php
class Modelseverity extends CI_Model
{
    var $table = 'severity'; //nama tabel dari database
    var $column_order = array(null, 'name', 'response_weekdays_o', 'response_weekdays_ao', 'response_weekends_o', 'response_weekends_ao', 'resolve_weekdays_o', 'resolve_weekdays_ao', 'resolve_weekends_o', 'resolve_weekends_ao', 'is_active', null); //Sesuaikan dengan field
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

    public function simpan($nama, $response_weekday_o, $response_weekday_ao, $resolve_weekday_o, $resolve_weekday_ao, $response_weekend_o, $response_weekend_ao, $resolve_weekend_o, $resolve_weekend_ao, $createdBy)
    {
        $simpan = [
            'name'                  => $nama,
            'response_weekdays_o'    => $response_weekday_o,
            'response_weekdays_ao'   => $response_weekday_ao,
            'resolve_weekdays_o'     => $resolve_weekday_o,
            'resolve_weekdays_ao'    => $resolve_weekday_ao,
            'response_weekends_o'    => $response_weekend_o,
            'response_weekends_ao'   => $response_weekend_ao,
            'resolve_weekends_o'     => $resolve_weekend_o,
            'resolve_weekends_ao'    => $resolve_weekend_ao,
            'created_by'            => $createdBy
        ];
        $this->db->insert('severity', $simpan);
    }

    public function getData()
    {
        return $this->db->get_where('severity', ['is_active' => '1']);
    }

    public function ambildata($id)
    {
        return $this->db->get_where('severity', ['id' => $id]);
    }

    public function update($id, $nama, $response_weekday_o, $response_weekday_ao, $resolve_weekday_o, $resolve_weekday_ao, $response_weekend_o, $response_weekend_ao, $resolve_weekend_o, $resolve_weekend_ao, $is_active, $modifiedBy, $modifiedDate)
    {
        $simpan = [
            'name'                  => $nama,
            'response_weekdays_o'   => $response_weekday_o,
            'response_weekdays_ao'  => $response_weekday_ao,
            'resolve_weekdays_o'    => $resolve_weekday_o,
            'resolve_weekdays_ao'   => $resolve_weekday_ao,
            'response_weekends_o'   => $response_weekend_o,
            'response_weekends_ao'  => $response_weekend_ao,
            'resolve_weekends_o'    => $resolve_weekend_o,
            'resolve_weekends_ao'   => $resolve_weekend_ao,
            'is_active'             => $is_active,
            'modified_by'           => $modifiedBy,
            'modified_date'         => $modifiedDate
        ];
        $this->db->where('id', $id);
        $this->db->update('severity', $simpan);
    }

    public function hapus($id)
    {
        return $this->db->delete('severity', ['id' => $id]);
    }

    public function hapusbanyak($id, $jmldata)
    {
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->delete('severity', ['id' => $id[$i]]);
        }

        return TRUE;
    }

    public function ambilestimasi_respon($severity_id)
    {
        $this->db->select('response_weekdays_o, response_weekdays_ao, response_weekends_o, response_weekends_ao');
        $this->db->limit(1);
        $query = $this->db->get_where($this->table, array('id' => $severity_id));
        $row = $query->row_array();
        return $row;
    }
}
