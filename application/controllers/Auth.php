<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->library('form_validation');
        $this->load->model('Modelauth', 'a');
    }

    public function index()
    {
        if ($this->session->userdata('session_id') == '1') {
            redirect('tiket');
        } else if ($this->session->userdata('session_id') == '0') {
            redirect('user');
        }

        $data['title']    = 'Helpdesk 2.1 - Login';

        $template           = array(
            'header'    => $this->load->view('templates/auth/header', $data, TRUE),
            'content'   => $this->load->view('templates/auth/content', '', TRUE),
            'script'    => $this->load->view('templates/auth/script', '', TRUE),
        );
        $this->parser->parse('auth/login', $template);
    }

    public function loginHelpdesk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $username = strtolower($this->security->xss_clean($this->input->post('username')));
            $password = $this->security->xss_clean($this->input->post('password'));

            $this->form_validation->set_rules(
                'username',
                'Username',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'password',
                'Password',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            if ($this->form_validation->run() == TRUE) {

                $check = $this->checkLogin($username, $password);

                if ($check == "Success !") {
                    $msg = [
                        'sukses' => 'You successfully Login'
                    ];
                } else if ($check == "Invalid password !") {
                    $msg = [
                        'error' => $check
                    ];
                } else {
                    $ldap_host      = "192.168.1.18";
                    $ldap_port      = 389;
                    $ldap           = ldap_connect($ldap_host, $ldap_port);
                    $ldap_userlogin = 'KUNINGAN' . "\\" . $username;

                    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

                    $bind = @ldap_bind($ldap, $ldap_userlogin, $password);
                    if ($bind == TRUE) {
                        $filter = "(&(objectClass=user)(sAMAccountName=$username))";
                        $result = ldap_search($ldap, "DC=Kuningan,DC=local", $filter);
                        $info   = ldap_get_entries($ldap, $result);

                        if (count((array)$info["count"]) > 0) {
                            $getUsername = "";

                            for ($i = 0; $i < $info["count"]; $i++) {
                                $nik            = $info[$i]["employeeid"][0];
                                $name           = $info[$i]["displayname"][0];
                                $getUsername    = strtolower($info[$i]["samaccountname"][0]);
                                $mail           = $info[$i]["mail"][0];
                            }
                            $data = [
                                'nik'           => $nik,
                                'name'          => $name,
                                'username'      => htmlspecialchars($getUsername),
                                'email'         => htmlspecialchars($mail),
                                'password'      => password_hash($password, PASSWORD_DEFAULT),
                                'image'         => 'default.jpg',
                                'role_id'       => '6',
                                'is_active'     => '0'
                            ];

                            $this->a->newUser($data);
                            $check = $this->checkLogin($username, $password);

                            if ($check == "Success !") {
                                $msg = [
                                    'sukses' => 'You successfully Login'
                                ];
                            } else {
                                $msg = [
                                    'error' => 'Error 1 !'
                                ];
                            }
                        } else {
                            $msg = [
                                'error' => 'Mohon hubungin Admin ICT untuk melengkapi data diri LDAP anda'
                            ];
                        }
                    }
                }
            } else {
                $msg = [
                    'error' => validation_errors()
                ];
            }
            echo json_encode($msg);
        }
    }

    public function signInHelpdesk()
    {
        if ($this->input->is_ajax_request() == TRUE) {

			$this->session->unset_userdata('nik');
			$this->session->unset_userdata('name');
			$this->session->unset_userdata('username');
			$this->session->unset_userdata('email');
			$this->session->unset_userdata('department');
			$this->session->unset_userdata('role_id');
			$this->session->unset_userdata('session_id');

            $usernameSignIn = strtolower($this->security->xss_clean($this->input->post('username_signin')));
            $passwordSignIn = $this->security->xss_clean($this->input->post('password_signin'));

            $this->form_validation->set_rules(
                'username_signin',
                'Username',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            $this->form_validation->set_rules(
                'password_signin',
                'Password',
                'required|trim',
                [
                    'required'  => '%s tidak boleh kosong'
                ]
            );

            if ($this->form_validation->run() == TRUE) {

                $ldap_host      = "192.168.1.18";
                $ldap_port      = 389;
                $ldap           = ldap_connect($ldap_host, $ldap_port);
                $ldap_userlogin = 'KUNINGAN' . "\\" . $usernameSignIn;

                ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

                $bind = @ldap_bind($ldap, $ldap_userlogin, $passwordSignIn);
                if ($bind == TRUE) {
                    $filter = "(&(objectClass=user)(sAMAccountName=$usernameSignIn))";
                    $result = ldap_search($ldap, "DC=Kuningan,DC=local", $filter);
                    $info   = ldap_get_entries($ldap, $result);

                    if (count((array)$info["count"]) > 0) {
                        $getUsername = "";

                        for ($i = 0; $i < $info["count"]; $i++) {
                            $nik            = $info[$i]["employeeid"][0];
                            $name           = $info[$i]["displayname"][0];
                            $getUsername    = strtolower($info[$i]["samaccountname"][0]);
                            $mail           = $info[$i]["mail"][0];
                            $dept1          = $info[$i]["distinguishedname"][0];
                        }
                        $dept2      = explode("OU=", $dept1);
                        $department = explode(",", $dept2[1]);
                        $data       = [
                            'nik'           => $nik,
                            'username'      => $getUsername,
                            'name'          => $name,
                            'email'         => htmlspecialchars($mail),
                            'department'    => $department[0],
                            'session_id'    => '1'
                        ];
                        $this->session->set_userdata($data);
                        $msg = ['sukses' => 'You successfully Login'];
                    } else {
                        $msg = ['error' => 'Mohon hubungin Admin ICT untuk melengkapi data diri LDAP anda'];
                    }
                } else {
                    $msg = ['error' => 'Username/Password LDAP anda salah mohon, hubungi ICT Admin'];
                }
            } else {
                $msg = ['error' => validation_errors()];
            }
            echo json_encode($msg);
        }
    }

    private function checkLogin()
    {
        try {
            $username   = strtolower($this->security->xss_clean($this->input->post('username')));
            $password   = $this->security->xss_clean($this->input->post('password'));
            $check      = $this->a->checkUsername($username);
            if ($check->num_rows() != 1) throw new UnexpectedValueException("Wrong user !");
            $row        = $check->row_array();
            if (!password_verify($password, $row['password'])) throw new UnexpectedValueException("Invalid password !");
            $status     = "Success !";
            $data       = [
                'nik'           => $row['nik'],
                'username'      => $row['username'],
                'name'          => $row['name'],
                'role_id'       => $row['role_id'],
                // 'level'      	=> $row['rank_id'],
                'session_id'    => '0'
            ];
            $this->session->set_userdata($data);
        } catch (Exception $e) {
            $status = $e->getMessage();
        }
        return $status;
    }

    public function logout()
    {
        $this->session->unset_userdata('nik');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('department');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('session_id');
        // session_destroy();

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out</div>');
        redirect('auth');
    }

    public function signOut()
    {
        if ($this->input->is_ajax_request() == TRUE) {
			$this->session->unset_userdata('nik');
			$this->session->unset_userdata('name');
			$this->session->unset_userdata('username');
			$this->session->unset_userdata('email');
			$this->session->unset_userdata('department');
			$this->session->unset_userdata('role_id');
			$this->session->unset_userdata('session_id');
            // session_destroy();

            $msg = ['sukses' => 'You session has been removed !'];
            echo json_encode($msg);
            session_destroy();
        } else {
            var_dump('gagal ajax request');
            die();
        }
    }

    public function blocked()
    {
        $data['title']  = 'Access Blocked';
        $template       = array(
            'header'    => $this->load->view('templates/auth/auth_header', $data, TRUE),
            'script'    => $this->load->view('user/script', '', TRUE),
            'footer'    => $this->load->view('templates/footer', '', TRUE),
        );
        $this->parser->parse('auth/blocked', $template);
    }
}
