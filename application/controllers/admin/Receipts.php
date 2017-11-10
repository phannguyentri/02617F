<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Receipts extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('receipts_model');
    }
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('receipts');
        }
        if (!has_permission('customers', '', 'view')) {
            if (!is_customer_admin(get_staff_user_id())) {
                access_denied('customers');
            }
        }
        $data['title'] = _l('receipts');
        $this->load->view('admin/receipts/manage', $data);
    }
    public function receipts($id="")
    {
        if($this->input->post())
        {
            if($id=="")
            {
                $data=$this->input->post();
                $_data=$data['items'];
                unset($data['items']);
                $data['id_staff']=get_staff_user_id();
                $id = $this->receipts_model->insert($data);
                if($id) {
                    $_id = $this->receipts_model->insert_receipts_contract($id, $_data);
                    if($_id)
                    {
                        set_alert('success', _l('added_successfuly', _l('receipts')));
                        redirect(admin_url('receipts/receipts/'.$id));
                    }
                    else
                    {
                        set_alert('danger', _l('problem_adding'));
                        redirect(admin_url('receipts/receipts/'.$id));
                    }
                }
                else
                {
                    set_alert('danger', _l('problem_adding'));
                    redirect(admin_url('receipts/receipts'));
                }
            }
            else
            {
                $data=$this->input->post();
                $_data['item']=$data['item'];
                $_data['items']=$data['items'];
                unset($data['item']);
                unset($data['items']);
                $result=$this->receipts_model->update($id,$data);
                $_result=$this->receipts_model->update_receipts_cotract($id,$_data['item']);
                $__result=$this->receipts_model->insert_receipts_contract($id,$_data['items']);
                if($result||$_result||$__result)
                {
                    set_alert('success', _l('updated_successfuly'));
                }
                redirect(admin_url('receipts/receipts/'.$id));
            }

        }
        else
        {
            if($id=="")
            {
                $data['heading']=_l('receipts_add_heading');
                $data['title']=_l('receipts_add_heading');
                $data['client']=$this->receipts_model->get_table_where('tblclients');
                $data['currencies']=$this->receipts_model->get_table_where('tblcurrencies');
                $data['tk_no']=$this->receipts_model->get_table_where('tblaccounts','idAccountAttribute=1 or idAccountAttribute=3');
                $data['tk_co']=$this->receipts_model->get_table_where('tblaccounts','idAccountAttribute=2 or idAccountAttribute=3');
                $data['tk_ck']=$this->receipts_model->get_table_where('tblaccounts');
                $data['code_vouchers']=$this->receipts_model->get_vouchers();

            }
            else
            {
                $data['heading']=_l('receipts_update_heading');
                $data['title']=_l('receipts_update_heading');
                $data['client']=$this->receipts_model->get_table_where('tblclients');
                $data['currencies']=$this->receipts_model->get_table_where('tblcurrencies');
                $data['contract']=$this->receipts_model->get_contract();
                $data['tk_no']=$this->receipts_model->get_table_where('tblaccounts','idAccountAttribute=1 or idAccountAttribute=3');
                $data['tk_co']=$this->receipts_model->get_table_where('tblaccounts','idAccountAttribute=2 or idAccountAttribute=3');
                $data['tk_ck']=$this->receipts_model->get_table_where('tblaccounts');
                $data['receipt']=$this->receipts_model->get_index_receipts($id);
                if($data['receipt'])
                {
                    $data['sales']=$this->receipts_model->get_sales_receipts($data['receipt']->id_client);
                    $data['receipts']= $this->receipts_model->index_receipts_contract($id);
                }
                else
                {
                    set_alert('warning', _l('no_data_found', _l('receipts')));
                    redirect(admin_url('receipts/receipts'));
                }
            }
            $this->load->view('admin/receipts/detail',$data);
        }

    }
    public function get_sales()
    {
        $id_client=$this->input->post('id_client');
        $client=$this->receipts_model->get_sales_receipts($id_client);
        echo json_encode($client);
    }
    public function get_sales_id()
    {
        $id=$this->input->post('sales');
        if($id){
            $result=$this->receipts_model->get_table_id('tblsales','id='.$id);
            $this->db->where('sale_id',$id);
            $project_sales=$this->db->get('tblsale_items')->result_array();
            $money=0;
            $money_tax=0;
            foreach($project_sales as $rom)
            {
                $money_tax+=$rom['tax'];
                $money+=$rom['sub_total'];
            }
            $result->money_tax=$money_tax.'';
            $result->money=$money.'';
            echo json_encode($result);
        }
    }
    public function pdf($id="")
    {
        $receipts = $this->receipts_model->get_data_pdf($id);
        if ($this->input->get('combo')) {
            $receipts->combo=$this->input->get('combo');
        }
        $pdf      = receipts_pdf($receipts);

        $type     = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(slug_it($receipts->code_vouchers) . '.pdf', $type);
    }


    public function update_status()
    {
        $id=$this->input->post('id');
        $status=$this->input->post('status');


        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data=array('status'=>$status);

        $inv=$this->receipts_model->get_index_receipts($id);

        if(is_admin() && $status==0)
        {
            $data['staff_browse']=$staff_id;
            $data['date_status']=$date;

            $data['staff_browse']=$staff_id;
            $data['date_status']=$date;

            $data['status']=2;
        }
        elseif(is_admin() && $status==1)
        {
            $data['status']=2;
            $data['staff_browse']=$staff_id;
            $data['date_status']=$date;
        }
        elseif(is_head($inv->id_staff))
        {
            $data['status']+=1;
            $data['staff_browse']=$staff_id;
            $data['date_status']=$date;
        }
        $success=false;

        if(is_admin() || is_head($inv->id_staff))
        {
            $success=$this->receipts_model->update_status($id,$data);
        }

        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận đề xuất thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        die;
    }

}
