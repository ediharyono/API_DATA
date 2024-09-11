<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->helper('url');
        
        				$this->load->model('token/token_uwm_model','tkn');
    }

    public function index() {
        // Ganti dengan token dan ID mahasiswa yang sesuai
        $token = $this->tkn->token_simak();
        $id = '231412323';

        $mytoken = array(
            'token' => $token,
            'mahasiswa_kode' => $id
        );
        $data_manual = json_encode($mytoken);
        log_message('debug', 'Data Manual: ' . $data_manual);

        // Set up CURL request
        $url = 'https://api.widyamataram.ac.id/bil_billing/get_tagihan';
        $this->curl->create($url);
        $this->curl->option(CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_manual)));
        $this->curl->post($data_manual);
        $result = $this->curl->execute();

        // Decode the JSON response
        $data['result'] = json_decode($result, true);

        // Load the view and pass the data
        $this->load->view('simkeu/billing_view', $data);
    }
}
