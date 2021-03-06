<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Input_Member extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        // untuk pagination
        $this->load->library('pagination');
        $this->load->model('anggota_model');
    }


    public function index()
    {
        // pagination
        $config['base_url'] = 'http://localhost/website-hmisi-ci/Input_Member/index';
        $config['total_rows'] = $this->anggota_model->countAllMember();
        $config['per_page'] = 5;

        // styling pagination
        $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = array('class' => 'page-link');

        // initialisasi
        $this->pagination->initialize($config);

        $ses_id = $this->session->userdata('email');
        $data['user'] = $this->db->get_where('user', ['email' => $ses_id])->row_array();
        $data['title'] = "Member HMISI";
        $data['start'] = $this->uri->segment(3);
        $data['members'] = $this->anggota_model->get_page($config['per_page'], $data['start']);



        if (empty($ses_id)) {
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-danger" role="alert">
				Oupps, you\'re not Login!
			</div>'
            );
            redirect('auth');
        }


        $this->load->helper('url');
        $this->load->view('templates/dashboard_header', $data);
        $this->load->view('templates/dashboard_sidebar', $data);
        $this->load->view('templates/dashboard_topbar', $data);
        $this->load->view('admin/input_member', $data);
        $this->load->view('templates/dashboard_footer');
    }


    public function create()
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('full-name', 'Full Name', 'required');
        $this->form_validation->set_rules('depart', 'Department', 'required');
        $this->form_validation->set_rules('image', 'Image', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->helper('url');

            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-warning" role="alert">
                    Member failed Saved!
                </div>'
            );
        } else {
            $this->anggota_model->set_member();
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-success" role="alert">
                    Member Saved!
                </div>'
            );
            redirect('Input_Member');
        }
    }

    public function hapus($id)
    {
        $where = array('id' => $id);

        $this->anggota_model->hapus_data($where, 'anggota');
        $this->session->set_flashdata(
            'message',
            '<div class="alert alert-success" role="alert">
                Member deleted!
            </div>'
        );
        redirect('Input_Member');
    }
}
