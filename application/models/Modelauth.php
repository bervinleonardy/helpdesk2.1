<?php
class Modelauth extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }

    function checkUsername($username)
    {
        $query = $this->db->get_where('user', array('username' => $username));
        return $query;
    }

    function getDataUser($username, $password)
    {
        $query = $this->db->get_where('user', array('username' => $username, 'password' => $password));
        return $query;
    }

    public function newUser($data)
    {
        return $this->db->insert('user', $data);
    }
}
