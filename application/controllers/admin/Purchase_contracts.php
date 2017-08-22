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
        $this->load->model('currencies_model');
        $this->load->model('warehouse_model');
        $this->load->model('contract_templates_model');
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
                $data['currencies'] = $this->currencies_model->get();
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
                foreach($data['item']->products as $key=>$value) {
                    $data['item']->products[$key]->warehouse_type = (object)$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id, true);
                }
                $contract_merge_fields  = get_available_merge_fields();
                $_contract_merge_fields = array();
                foreach ($contract_merge_fields as $key => $val) {
                    foreach ($val as $type => $f) {
                        if ($type == 'contract') {
                            foreach ($f as $available) {
                                foreach ($available['available'] as $av) {
                                    if ($av == 'contract') {
                                        array_push($_contract_merge_fields, $f);
                                        break;
                                    }
                                }
                                break;
                            }
                        } else if ($type == 'other') {
                            array_push($_contract_merge_fields, $f);
                        } else if ($type == 'clients') {
                            array_push($_contract_merge_fields, $f);
                        }
                    }
                }
                $data['contract_merge_fields'] = $_contract_merge_fields;
                $data['order'] = $order;
                
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
    public function pdf($id)
    {
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }
        if (!$id) {
            redirect(admin_url('contracts'));
        }
        $contract = $this->purchase_contacts_model->get($id);
        $pdf      = contract_purchase_pdf($contract);
        
        $type     = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(slug_it($contract->code) . '.pdf', $type);
    }
    public function save_contract_data()
    {
        if (!has_permission('contracts', '', 'edit') && !has_permission('contracts', '', 'create')) {
            header('HTTP/1.0 400 Bad error');
            echo json_encode(array(
                'success' => false,
                'message' => _l('access_denied')
            ));
            die;
        }

        $success = false;
        $message = '';
        if ($this->input->post('template')) {
            $this->db->where('id', $this->input->post('contract_id'));
            $this->db->update('tblpurchase_contracts', array(
                'template' => $this->input->post('template', FALSE)
            ));

            if ($this->db->affected_rows() > 0) {
                $success = true;
                $message = _l('updated_successfuly', _l('contract'));
            }
            else {
                $success = true;
                $message = "Không có thay đổi!";
            }
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message
        ));
    }
}