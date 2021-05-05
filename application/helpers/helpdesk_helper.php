<?php

function isLogIn()
{
    $ci = get_instance();
    if ($ci->session->userdata('session_id') == 0) {
        $role_id = $ci->session->userdata('role_id');
        $menu    = $ci->uri->segment(1);

        $queryMenu  = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menuId     = $queryMenu['id'];

		// die($ci->db->last_query($queryMenu));

		// var_dump($role_id, $menuId);die();

        $userAccess   = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menuId
        ]);


		// var_dump( $role_id, $menuId);die();
		// die($ci->db->last_query($userAccess));
		
        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    } else if ($ci->session->userdata('session_id') == 1) {
        redirect('tiket');
    } else {
        redirect('auth');
    }
}

function isSignIn()
{
    // phpinfo();
    $ci = get_instance();
    if ($ci->session->userdata('session_id') != 1) {
        redirect('auth');
    }
}

function checkAccess($role_id, $menu_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function checkItem($form_id, $site_id, $category_id, $item_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('answer_check', ['form_id' => $form_id, 'site_id' => $site_id, 'category_id' => $category_id, 'item_id' => $item_id]);


    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function reCheckItem($form_id, $site_id, $category_id, $item_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('answer_check', ['form_id' => $form_id, 'site_id' => $site_id, 'category_id' => $category_id, 'item_id' => $item_id, 'validate' => 1]);

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function checkItemStatus($form_id, $item_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('answer_check', ['form_id' => $form_id, 'item_id' => $item_id])->row();

	$name = '';
	$badge	= '';
    if ($result->validate == 0) {
		$badge	= 'primary';
		$name = 'Checked';
	} else if ($result->validate == 1) {
		$badge	= 'success';
		$name = 'Validate';
	} else if ($result->validate == 2) {
		$badge	= 'warning';
		$name = 'Revision';
	}
	
	return "<span class=\"badge badge-pill badge-" . $badge . "\">" . $name . "</span>";
}

function validateItem($form_id, $site_id, $category_id, $item_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('answer_check', ['form_id' => $form_id, 'site_id' => $site_id, 'category_id' => $category_id, 'item_id' => $item_id, 'validate' => 1]);

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function revisionItem($form_id, $site_id, $category_id, $item_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('answer_check', ['form_id' => $form_id, 'site_id' => $site_id, 'category_id' => $category_id, 'item_id' => $item_id, 'validate' => 2]);

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function checkFormItemRemark($form_id, $site_id, $category_id, $item_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('answer_check', ['form_id' => $form_id, 'site_id' => $site_id, 'category_id' => $category_id, 'item_id' => $item_id]);

    if ($result->num_rows() > 0) {
        return $result->row()->remark;
    }
}

function checkFormItemComment($form_id, $site_id, $category_id, $item_id)
{
    $ci     = get_instance();
    $result = $ci->db->get_where('answer_check', ['form_id' => $form_id, 'site_id' => $site_id, 'category_id' => $category_id, 'item_id' => $item_id]);

    if ($result->num_rows() > 0) {
        return $result->row()->comment;
    }
}

function getMaxbyDate($prefix = null, $table = null, $field = null)
{
    $ci = get_instance();
    $ci->db->select('no');
    $ci->db->like($field, $prefix, 'after');
    $ci->db->order_by($field, 'desc');
    $ci->db->limit(1);
    return $ci->db->get($table)->row_array()[$field];
}

function getAddressEmail($user_id, $group)
{
    $ci = get_instance();
    $ci->db->select(
        '
            a.email,
            c.email emailDept,
        '
    );
    $ci->db->join('department b', 'a.department_id = b.id', 'left');
    $ci->db->join('manager c', 'c.department_id = b.id', 'left');
    $query = $ci->db->get_where('user a', ['a.id' => $user_id, 'b.name' => $group])->row_array();
    return $query;
}

function sendEmail($no, $to, $cc, $subject, $nama_creator, $keterangan)
{
    $ci = get_instance();
    $config = array(
        'protocol'      => 'smtp',
        'smtp_host'     => 'mail.medikaplaza.com',
        'smtp_port'     => 25,
        'smtp_user'     => 'noreply@medikaplaza.com',
        'smtp_pass'     => '',
        'mailtype'      => 'html',
        'smtp_timeout'  => '2',
        'charset'       => 'utf-8',
        'newline'       => "\r\n",
        'crlf'          => "\r\n",
        'wordwrap'      => 'TRUE'
    );

    $ci->email->initialize($config);
    $ci->email->from($config['smtp_user'], 'HELPDESK 2.1');
    $ci->email->cc($cc);
    $ci->email->to($to);


    $ci->db->select(
        '
            a.id, 
            c.name status,
            c.words,
            b.name employee,
            b.mobile mobile,
            b.email email,

        '
    );
    $ci->db->join('user b', 'a.user_id = b.id', 'left');
    $ci->db->join('status c', 'a.status_id = c.id', 'left');
    $message = $ci->db->get_where('tiket a', ['a.no' => $no])->row_array();

    $data = array(
        'tiket'         => $no,
        'nama'          => $nama_creator,
        'status'        => $message['status'],
        'subject'       => $subject,
        'words'         => $message['words'],
        'employee'      => $message['employee'],
        'mobile'        => $message['mobile'],
        'email'         => $message['email'],
        'keterangan'    => $keterangan
    );

    $body = $ci->load->view('tiket/message.php', $data, TRUE);

    $ci->email->subject('[' . $message['status'] . '] ' . $no);
    $ci->email->message($body);

    $status = $ci->email->send();

    if ($status == FALSE) {
        return FALSE;
    } else {
        return TRUE;
    }
}
