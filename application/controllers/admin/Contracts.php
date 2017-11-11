<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contracts extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('contracts_model');
        $this->load->model('quotes_model');
        $this->load->model('contract_templates_model');
        $this->load->model('warehouse_model');
    }
    /* List all contracts */
    public function index($clientid = false)
    {
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('contracts', array(
                'clientid' => $clientid
            ));
        }
        $data['chart_types']        = json_encode($this->contracts_model->get_contracts_types_chart_data());
        $data['chart_types_values'] = json_encode($this->contracts_model->get_contracts_types_values_chart_data());
        $data['clients_iv'] = $this->clients_model->get();
        $this->load->model('staff_model');
        $data['user_iv'] = $this->staff_model->get();
        $data['contract_types']     = $this->contracts_model->get_contract_types();
        $data['years']              = $this->contracts_model->get_contracts_years();
        $data['title']              = _l('contracts');
        $data['chart_js_assets']   = true;
        $this->load->view('admin/contracts/manage', $data);
    }
    /* Edit contract or add new contract */
    public function contract($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('contracts', '', 'create')) {
                    access_denied('contracts');
                }
                $data=$this->input->post();

                if($data['contract_type']==2)
                {

                    $data['content']=$this->contract_templates_model->get_contract_template_by_id(1)->content;

                }
                else
                {
                    $data['content']=$this->contract_templates_model->get_contract_template_by_id(1)->content;
                }
                $id = $this->contracts_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('contract')));
                    redirect(admin_url('contracts/contract/' . $id));
                }
            } else {

                if (!has_permission('contracts', '', 'edit')) {
                    access_denied('contracts');
                }

                $success = $this->contracts_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfuly', _l('contract')));
                }
                redirect(admin_url('contracts/contract/' . $id));
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('contract_lowercase'));
            $data['quotes']         = $this->contracts_model->get_quote_contracts();
        } else {

            $data['contract']                 = $this->contracts_model->get($id, array(), true);
            $a = array();
            $a         =  $this->contracts_model->get_quote_contracts();
            $data['item'] = $this->contracts_model->getContractByID($id);
            // $data['item_returns'] = $this->sale_oders_model->getReturnSaleItems($id);
            // var_dump($data['item']);die();

            $i=0;
            foreach ($data['item']->items as $key => $value) {
                $data['item']->items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }
            $data['warehouse_type_id'] = $data['item']->items[0]->warehouse_id;
            $data['warehouse_type_id1'] = $data['item']->items1[0]->warehouse_id;
            // var_dump($data['item']);die();

            if (!$data['item']) {
                blank_page('Sale Not Found');
            }

            array_push($a, (array)$this->contracts_model->get_quote_contracts1($data['contract']->rel_id));
            $data['quotes'] = $a;
            $data['contract_renewal_history'] = $this->contracts_model->get_contract_renewal_history($id);
            if (!$data['contract'] || (!has_permission('contracts', '', 'view') && $data['contract']->addedfrom != get_staff_user_id())) {
                blank_page(_l('contract_not_found'));
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
            $title                         = _l('edit', _l('contract_lowercase'));

            $contact = $this->clients_model->get_contact(get_primary_contact_user_id($data['contract']->client));
            $email   = '';
            if ($contact) {
                $email = $contact->email;
            }

            $template_name         = 'send-contract';
            $data['template']      = get_email_template_for_sending($template_name, $email);
            $data['template_name'] = 'send-contract';
        }
        if ($this->input->get('customer_id')) {
            $data['customer_id']        = $this->input->get('customer_id');
            $data['do_not_auto_toggle'] = true;
        }

        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['types']         = $this->contracts_model->get_contract_types();
        $data['categories_a'] = $this->quotes_model->getCategory(1,NULL,388);
        $data['categories_b'] = $this->quotes_model->getCategory(1,NULL,446);
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }

        $staff =  $this->quotes_model->getStaff(get_staff_user_id());
        $day = date('d/m/y');
        $count = $this->contracts_model->getContractByIDDate(get_staff_user_id(),$day);

        $dayF = str_replace('/','',$day);
        $code = $staff->staff_code.sprintf('%02d',$count+1).$dayF;
        $data['code'] = $code;

        $data['clients'] = $this->clients_model->get('', $where_clients);
        $this->load->model('invoice_items_model');
        $data['items']= $this->invoice_items_model->get_full();

        if ($id != '') {
            if (total_rows('tblclients', array(
                'active' => 0,
                'userid' => $data['contract']->client
            )) > 0 || (total_rows('tblcustomeradmins', array(
                'staff_id' => get_staff_user_id(),
                'customer_id' => $data['contract']->client
            )) == 0 && !has_permission('customers', '', 'view'))) {
                $data['clients'][] = $this->clients_model->get($data['contract']->client, array(), 'row_array');
            }
        }
        $data['title'] = $title;
        $this->load->view('admin/contracts/contract', $data);
    }

    public function getIteamQuote(){
        $this->load->model('quotes_model');
        $data         = $this->quotes_model->getQuoteByID($this->input->post('id'));
        echo json_encode($data);
    }

    public function create_order($id)
    {
        if (!has_permission('sale_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('sale_items');
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('sales'));

        } else {
            $title = _l('add_new', _l('sale_orders'));
            $data['item'] = $this->contracts_model->getContractByID($id);
            // $data['item_returns'] = $this->sale_oders_model->getReturnSaleItems($id);
            // var_dump($data['item']);die();

            $i=0;
            foreach ($data['item']->items as $key => $value) {
                $data['item']->items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }

            // var_dump($data['item']);die();

            if (!$data['item']) {
                blank_page('Sale Not Found');
            }
        }
        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }

        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['customers'] = $this->clients_model->get('', $where_clients);
        // $data['items']= $this->invoice_items_model->get_full();

        // $data['warehouse_types']= $this->sale_oders_model->getWarehouseTypes();

        $data['convert']= $data['item']->export_status ? false : true ;

        $data['title'] = $title;
        $this->load->view('admin/contracts/export_order_detail', $data);
    }
    public function pdf($id)
    {
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }
        if (!$id) {
            redirect(admin_url('contracts'));
        }
        if($this->input->get('?type')){
            $type = $this->input->get('?type');
        }
        if($this->input->get('type')){
            $type = $this->input->get('type');
        }
        $contract = $this->contracts_model->get3($id,$type);
        $pdf      = contract_pdf($contract);
        $type     = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(slug_it($contract->subject) . '.pdf', $type);
    }

     public function word($id){
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }
        if (!$id) {
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $contract = $this->contracts_model->get($id);

        header("Content-Type: application/vnd.msword");
        header("Expires: 0");//no-cache
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("content-disposition: attachment;filename=".$contract->subject.".doc");
        echo '<html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                </head>
                <body>'.$contract->content.'</body>
              </html>';
        // echo mb_convert_encoding($contract->content,'HTML-ENTITIES','UTF-8');
    }
    public function send_to_email($id)
    {
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }
        $success = $this->contracts_model->send_contract_to_client($id, $this->input->post('attach_pdf'), $this->input->post('cc'));
        if ($success) {
            set_alert('success', _l('contract_sent_to_client_success'));
        } else {
            set_alert('danger', _l('contract_sent_to_client_fail'));
        }
        redirect(admin_url('contracts/contract/' . $id));
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
        if ($this->input->post('content')) {
            $this->db->where('id', $this->input->post('contract_id'));
            $this->db->update('tblcontracts', array(
                'content' => $this->input->post('content', FALSE)
            ));

            if ($this->db->affected_rows() > 0) {
                $success = true;
                $message = _l('updated_successfuly', _l('contract'));
            }
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message
        ));
    }
    public function renew()
    {
        if (!has_permission('contracts', '', 'create') && !has_permission('contracts', '', 'edit')) {
            access_denied('contracts');
        }
        if ($this->input->post()) {
            $data    = $this->input->post();
            $success = $this->contracts_model->renew($data);
            if ($success) {
                set_alert('success', _l('contract_renewed_successfuly'));
            } else {
                set_alert('warning', _l('contract_renewed_fail'));
            }
            redirect(admin_url('contracts/contract/' . $data['contractid'] . '?tab=tab_renewals'));
        }
    }
    public function delete_renewal($renewal_id, $contractid)
    {
        $success = $this->contracts_model->delete_renewal($renewal_id, $contractid);
        if ($success) {
            set_alert('success', _l('contract_renewal_deleted'));
        } else {
            set_alert('warning', _l('contract_renewal_delete_fail'));
        }
        redirect(admin_url('contracts/contract/' . $contractid . '?tab=tab_renewals'));
    }
    /* Delete contract from database */
    public function delete($id)
    {
        if (!has_permission('contracts', '', 'delete')) {
            access_denied('contracts');
        }
        if (!$id) {
            redirect(admin_url('contracts'));
        }
        $response = $this->contracts_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('contract')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('contract_lowercase')));
        }
        redirect(admin_url('contracts'));
    }
    /* Manage contract types Since Version 1.0.3 */
    public function type($id = '')
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->contracts_model->add_contract_type($this->input->post());
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('contract_type'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->contracts_model->update_contract_type($data, $id);
                $message = '';
                if ($success) {
                    $message = _l('updated_successfuly', _l('contract_type'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            }
        }
    }
    public function types()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('contract_types');
        }
        $data['title'] = _l('contract_types');
        $this->load->view('admin/contracts/manage_types', $data);
    }
    /* Delete announcement from database */
    public function delete_contract_type($id)
    {
        if (!$id) {
            redirect(admin_url('contracts/types'));
        }
        if (!is_admin()) {
            access_denied('contracts');
        }
        $response = $this->contracts_model->delete_contract_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('contract_type_lowercase')));
        } else if ($response == true) {
            set_alert('success', _l('deleted', _l('contract_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('contract_type_lowercase')));
        }
        redirect(admin_url('contracts/types'));
    }
    public function add_contract_attachment($id)
    {
        handle_contract_attachment($id);
    }
    public function add_external_attachment()
    {
        if ($this->input->post()) {
            $this->misc_model->add_attachment_to_database($this->input->post('contract_id'), 'contract', $this->input->post('files'), $this->input->post('external'));
        }
    }
    public function delete_contract_attachment($attachment_id)
    {
        $file = $this->misc_model->get_file($attachment_id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo json_encode(array(
                'success' => $this->contracts_model->delete_contract_attachment($attachment_id)
            ));
        }
    }
}
