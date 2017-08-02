<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_contracts extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_contacts_model');
        $this->load->model('invoice_items_model');
        $this->load->model('orders_model');
    }
    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchase_contracts');
        }
        $data['title'] = _l('purchase_contract');
        $this->load->view('admin/purchase_contracts/manage', $data);
    }
    public function view($id='') {
        if(is_numeric($id)) {
            $contract = $this->purchase_contacts_model->get($id);
            if($contract) {
                $data = array();
                $data['title'] = _l('orders_view_heading');
                $data['suppliers'] = $this->orders_model->get_suppliers();
                // get purchase suggested id
                $this->db->where('id', $contract->id_order);
                $ps = $this->db->get('tblorders')->row();
                if($ps) {
                    $contract->code_order = $ps->code;
                }
                else {
                    $contract->code_order = "";
                }
                $data['item'] = $contract;
                $content = $this->load->view('admin/purchase_contracts/view', $data, true);
                exit($content);
            }
        }
        redirect(admin_url() . 'purchase_contracts');
    }
    public function detail_pdf($id='') {
        if (!$id) {
            redirect(admin_url('purchase_contracts'));
        }
        $purchase_contract        = $this->purchase_contacts_model->get($id);
        $purchase_contract_code   = $purchase_contract->code;

        $pdf            = purchase_contract_pdf($purchase_contract);
        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($purchase_contract_code)) . '.pdf', $type);
    }
}