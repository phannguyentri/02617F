<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body _buttons">
            <div class="row">

              <div class="col-md-12">
                <h4>DANH SÁCH GIAO DỊCH</h4>
                <?php
                  // echo "<pre>";
                  // print_r($clients);
                  // echo "</pre>";
                 ?>
                <!-- <a href="#" class="btn btn-info pull-left mbot25 mright5" onclick="new_task_from_relation1('.table-rel-tasks1'); return false;">Giao dịch mới</a> -->
                <a href="#" class="btn btn-info pull-left mbot25 mright5" onclick="add_task(0); return false">Giao dịch mới</a>

                <?php if(has_permission('tasks','','create')){ ?>
                <!-- <a href="#" onclick="new_task1(); return false;" class="btn btn-info pull-left new "><?php echo _l('new_task'); ?></a> -->
                <!-- <a href="#" onclick="new_task(); return false;" class="btn btn-info pull-left new mleft10"><?php echo _l('Công việc mới'); ?></a> -->
                <?php } ?>
               <!--  <a href="<?php echo admin_url('tasks/switch_kanban/'.$switch_kanban); ?>" class="btn btn-default mleft10 pull-left">
                 <?php if($switch_kanban == 1){ echo _l('switch_to_list_view');}else{echo _l('leads_switch_to_kanban');}; ?>
               </a> -->
                <?php if($this->input->get('project_id')){ ?>
              <a href="<?php echo admin_url('projects/view/'.$this->input->get('project_id').'?group=project_tasks'); ?>" class="mtop5 pull-left mleft10"><?php echo _l('back_to_project'); ?></a>
            <?php } ?>
            </div>
            <!-- <div class="col-md-4">
               <?php if($this->session->has_userdata('tasks_kanban_view') && $this->session->userdata('tasks_kanban_view') == 'true') { ?>
               <div data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('search_by_tags'); ?>">
               <?php echo render_input('search','','','search',array('data-name'=>'search','onkeyup'=>'tasks_kanban();','placeholder'=>_l('search_tasks')),array(),'no-margin') ?>
               </div>
               <?php } else { ?>

               <?php } ?>
            </div> -->

              <?php
                $purpose_type = array(
                  array(
                      'id' => 'COIL',
                      'name' => 'Thu thập thông tin',
                  ),
                  array(
                      'id' => 'CORE',
                      'name' => 'Giới thiệu, tư vấn',
                  ),
                  array(
                      'id' => 'SURV',
                      'name' => 'Khảo sát',
                  ),
                  array(
                      'id' => 'QUOT',
                      'name' => 'Báo giá',
                  ),
                  array(
                      'id' => 'NEGO',
                      'name' => 'Đàm phán',
                  ),
                  array(
                      'id' => 'TCK',
                      'name' => 'Chăm sóc',
                  ),
                  array(
                      'id' => 'DELI',
                      'name' => 'Giao hàng',
                  ),
                  array(
                      'id' => 'PAY',
                      'name' => 'Thanh toán',
                  ),
                );

                $transaction_type = array(
                    array(
                        'id' => 'direct',
                        'name' => 'Gặp trực tiếp',
                    ),
                    array(
                        'id' => 'phone',
                        'name' => 'Gọi điện',
                    ),
                    array(
                        'id' => 'email',
                        'name' => 'Email',
                    ),
                    array(
                        'id' => 'mess',
                        'name' => 'Chát',
                    ),
                );

                $status_type = array(
                    array(
                        'id' => '3',
                        'name' => 'Chưa bắt đầu',
                    ),
                    array(
                        'id' => '4',
                        'name' => 'Trong tiến trình',
                    ),
                    array(
                        'id' => '5',
                        'name' => 'Hoàn thành',
                    )
                );
               ?>
              <div class="col-md-4">
                <?php
                  echo render_select('task_id', $tasks, array('id', 'name'), 'Giao dịch');
                 ?>
              </div>
              <div class="col-md-4">
                <?php
                  echo render_select('client_id', $clients, array('userid', 'company'), 'Khách hàng');
                 ?>
              </div>
              <div class="col-md-4 hidden">
                <?php
                  echo render_select('purpose', $purpose_type, array('id', 'name'), 'Mục đích');
                 ?>
              </div>

              <div class="col-md-4">
                <?php
                  echo render_select('assigned_from', $staffs, array('staffid', 'fullname'), 'Người giao việc');
                 ?>
              </div>

              <div class="col-md-4">
                <?php
                  echo render_select('transaction', $transaction_type, array('id', 'name'), 'Loại giao dịch');
                 ?>
              </div>
              <div class="col-md-4">
                <?php
                  echo render_select('status', $status_type, array('id', 'name'), 'Trạng thái');
                 ?>
              </div>
              <div class="col-md-4">
                <?php
                  echo render_select('staff_task_assignee_id', $staffs, array('staffid', 'fullname'), 'Người phụ trách');
                 ?>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-4" style="border-right: 1px solid #777;">
                <div class="col-md-12 text-center"><label style="color: #03a9f4; font-size: 15px;"><i class="fa fa-clock-o"></i> Ngày bắt đầu</label></div>
                <div class="col-md-6">
                   <label for="start-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                   <div class="input-group date">
                      <input type="text" class="form-control datepicker" id="start-from" name="start-from">
                      <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                      </div>
                   </div>
                </div>
                <div class="col-md-6">
                   <label for="start-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                   <div class="input-group date">
                      <input type="text" class="form-control datepicker" id="start-to" name="start-to">
                      <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                      </div>
                   </div>
                </div>
              </div>
              <div class="col-md-4" style="border-right: 1px solid #777;">
                <div class="col-md-12 text-center"><label style="color: #03a9f4; font-size: 15px;"><i class="fa fa-clock-o"></i> Hạn hoàn thành</label></div>
                <div class="col-md-6">
                   <label for="duration-date-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                   <div class="input-group date">
                      <input type="text" class="form-control datepicker" id="duration-date-from" name="duration-date-from">
                      <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                      </div>
                   </div>
                </div>
                <div class="col-md-6">
                   <label for="duration-date-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                   <div class="input-group date">
                      <input type="text" class="form-control datepicker" id="duration-date-to" name="duration-date-to">
                      <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                      </div>
                   </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="col-md-12 text-center"><label style="color: #03a9f4; font-size: 15px;"><i class="fa fa-clock-o"></i> Ngày hoàn thành</label></div>
                <div class="col-md-6">
                   <label for="finish-date-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                   <div class="input-group date">
                      <input type="text" class="form-control datepicker" id="finish-date-from" name="finish-date-from">
                      <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                      </div>
                   </div>
                </div>
                <div class="col-md-6">
                   <label for="finish-date-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                   <div class="input-group date">
                      <input type="text" class="form-control datepicker" id="finish-date-to" name="finish-date-to">
                      <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                      </div>
                   </div>
                </div>
              </div>
           </div>
         </div>
       </div>
       <div class="panel_s mtop5">
        <div class="panel-body">
          <div class="clearfix"></div>
          <?php
          if($this->session->has_userdata('tasks_kanban_view') && $this->session->userdata('tasks_kanban_view') == 'true') { ?>
          <div class="kan-ban-tab" id="kan-ban-tab" style="overflow:auto;">
           <div class="row">

           <div id="kanban-params">
              <?php echo form_hidden('project_id',$this->input->get('project_id')); ?>
           </div>
            <div class="container-fluid">
             <div id="kan-ban"></div>
           </div>
         </div>
       </div>
       <?php } else { ?>
       <?php $this->load->view('admin/tasks/tasks_filter_by',array('view_table_name'=>'.table-tasks')); ?>
               <a href="<?php echo admin_url('tasks/detailed_overview'); ?>" class="btn btn-success hidden pull-right mright5"><?php echo _l('detailed_overview'); ?></a>
       <a href="#" data-toggle="modal" data-target="#tasks_bulk_actions" class="btn btn-info mbot15"><?php echo _l('bulk_actions'); ?></a>

       <?php $this->load->view('admin/tasks/_table',array('bulk_actions'=>true)); ?>

     </div>
     <div class="modal fade bulk_actions" id="tasks_bulk_actions" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
          </div>
          <div class="modal-body">
            <?php if(has_permission('tasks','','delete')){ ?>
            <div class="checkbox checkbox-danger">
              <input type="checkbox" name="mass_delete" id="mass_delete">
              <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
            </div>
            <hr class="mass_delete_separator" />
            <?php } ?>
            <div id="bulk_change">
            <div class="form-group">
              <label for="move_to_status_tasks_bulk_action"><?php echo _l('task_status'); ?></label>
              <select name="move_to_status_tasks_bulk_action" id="move_to_status_tasks_bulk_action" data-width="100%" class="selectpicker" data-none-selected-text="<?php echo _l('task_status'); ?>">
                <option value=""></option>
                <?php foreach($task_statuses as $status){ ?>
                <option value="<?php echo $status; ?>"><?php echo format_task_status($status,false,true); ?></option>
                <?php } ?>
              </select>
            </div>
            <?php if(has_permission('tasks','','edit')){ ?>
            <div class="form-group hidden">
              <label for="task_bulk_priority" class="control-label"><?php echo _l('task_add_edit_priority'); ?></label>
              <select name="task_bulk_priority" class="selectpicker" id="task_bulk_priority" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                <option value=""></option>
                <option value="1"><?php echo _l('task_priority_low'); ?></option>
                <option value="2"><?php echo _l('task_priority_medium'); ?></option>
                <option value="3"><?php echo _l('task_priority_high'); ?></option>
                <option value="4"><?php echo _l('task_priority_urgent'); ?></option>
              </select>
            </div>
            <?php } ?>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <a href="#" class="btn btn-info" onclick="tasks_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <?php } ?>
</div>
</div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
  $(function(){
    var filterList = {
        "purpose"       : "[name='purpose']",
        "transaction"   : "[name='transaction']",
        "status"        : "[name='status']",
        "client_id"     : "[name='client_id']",
        "assigned_from" : "[name='assigned_from']",
        "task_id"       : "[name='task_id']",
        "start-from"    : "[name='start-from']",
        "start-to"      : "[name='start-to']",
        "duration-date-from"      : "[name='duration-date-from']",
        "duration-date-to"        : "[name='duration-date-to']",
        "finish-date-from"        : "[name='finish-date-from']",
        "finish-date-to"          : "[name='finish-date-to']",
        "staff_task_assignee_id"  : "[name='staff_task_assignee_id']",
    };
    initDataTable('.table-tasks1', admin_url+'tasks?bulk_actions=true', [0], [0], filterList,[0, 'DESC']);
    $.each(filterList, (filterIndex, filterItem) => {
        $('' + filterItem).on('change', () => {
          $('.table-tasks1').DataTable().ajax.reload();
        });
    });
  })

   taskid = '<?php echo $taskid; ?>';

   $(document).ready(function(){
   $('body').on('click', '.task-delete', function() {
        var r = confirm(confirm_action_prompt);
        var table='.table-tasks';
        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
                $('body .task-modal-single').modal('hide');
                alert_float(response.alert_type, response.message);
                // Looop throug all availble reminders table to reload the data
                    if ($.fn.DataTable.isDataTable(table)) {
                        $('body').find(table).DataTable().ajax.reload();
                    }
            }, 'json');
        }
        return false;
    });

 })



  $(function(){
      // let not_sortable_contracts = $('.table-contracts-single-client').find('th').length -1;
      //      _table_api = initDataTable('.table-contracts-single-client', admin_url + 'contracts/index/' + id, [not_sortable_contracts], [not_sortable_contracts], 'undefined', [3, 'DESC']);
      //      if(_table_api){
      //        _table_api.column(2).visible(false,false).columns.adjust();
      //      }
      // initDataTable('.table-contacts', admin_url + 'clients/contacts/' + id, [not_sortable_contacts], [not_sortable_contacts]);

      //  let not_sortable_quote = $('.table-quote_clients').find('th').length -1;
      //  initDataTable('.table-quote_clients', admin_url + 'clients/quotes/' + id, [not_sortable_quote], [not_sortable_quote]);

    // initDataTableFixedHeader('.table-sale_orders', admin_url+'sale_orders/list_sale_orders',
    //     [not_sortable_sale_orders], [not_sortable_sale_orders],
    //     filterList,
    //     [1,'DESC'],
    // initDataTableFixedHeader(".table-tasks1", admin_url+'tasks?bulk_actions=true' , [0], [0], '', [0, "ASC"]);
    tasks_kanban();
  });
</script>
</body>
</html>
