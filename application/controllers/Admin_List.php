<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_List extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
    }

    public function index()
    {
        $ses_id = $this->session->userdata('email');
        $data['user'] = $this->db->get_where('user', ['email' => $ses_id])->row_array();
        $data['title'] = "Admin List";

        if (empty($ses_id)) {
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-danger" role="alert">
				Oupps, you\'re not Login!
			</div>'
            );
            redirect('auth');
        }

        $data['admin_list'] = $this->db->get('user')->result_array();

        $this->load->helper('url');
        $this->load->view('templates/dashboard_header', $data);
        $this->load->view('templates/dashboard_sidebar', $data);
        $this->load->view('templates/dashboard_topbar', $data);
        $this->load->view('admin/admin_list', $data);
        $this->load->view('templates/dashboard_footer');
    }
}
