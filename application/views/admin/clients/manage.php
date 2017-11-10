<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <?php if (has_permission('customers','','create')) { ?>
                     <a href="#" onclick="init_client(); return false;" class="btn mright5 btn-info pull-left display-block">
                     <?php echo _l('new_client'); ?></a>
                     <a href="<?php echo admin_url('clients/import'); ?>" class="btn btn-info pull-left display-block mright5">
                     <?php echo _l('import_customers'); ?></a>
                     <?php } ?>
                     <div class="visible-xs">
                        <div class="clearfix"></div>
                     </div>
                     <div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                           <li class="active"><a href="#" data-cview="all" onclick="dt_custom_view('','.table-clients',''); return false;"><?php echo _l('customers_sort_all'); ?></a></li>
                           <li class="divider"></li>
                           <?php if(count($groups) > 0){ ?>
                           <li class="dropdown-submenu pull-left groups">
                              <a href="#" tabindex="-1"><?php echo _l('customer_groups'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($groups as $group){ ?>
                                 <li><a href="#" data-cview="customer_group_<?php echo $group['id']; ?>" onclick="dt_custom_view('customer_group_<?php echo $group['id']; ?>','.table-clients','customer_group_<?php echo $group['id']; ?>'); return false;"><?php echo $group['name']; ?></a></li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <?php } ?>
                           <li class="dropdown-submenu pull-left invoice hidden">
                              <a href="#" tabindex="-1"><?php echo _l('invoices'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($invoice_statuses as $status){ ?>
                                 <li>
                                    <a href="#" data-cview="invoices_<?php echo $status; ?>" data-cview="1" onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-clients','invoices_<?php echo $status; ?>'); return false;"><?php echo _l('customer_have_invoices_by',format_invoice_status($status,'',false)); ?></a>
                                 </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <li class="dropdown-submenu pull-left estimate hidden">
                              <a href="#" tabindex="-1"><?php echo _l('estimates'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($estimate_statuses as $status){ ?>
                                 <li>
                                    <a href="#" data-cview="estimates_<?php echo $status; ?>" onclick="dt_custom_view('estimates_<?php echo $status; ?>','.table-clients','estimates_<?php echo $status; ?>'); return false;">
                                    <?php echo _l('customer_have_estimates_by',format_estimate_status($status,'',false)); ?>
                                    </a>
                                 </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left project">
                              <a href="#" tabindex="-1"><?php echo _l('projects'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($project_statuses as $status){ ?>
                                 <li>
                                    <a href="#" data-cview="projects_<?php echo $status; ?>" onclick="dt_custom_view('projects_<?php echo $status; ?>','.table-clients','projects_<?php echo $status; ?>'); return false;">
                                    <?php echo _l('customer_have_projects_by',_l('project_status_'.$status)); ?>
                                    </a>
                                 </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <li class="dropdown-submenu pull-left proposal hidden">
                              <a href="#" tabindex="-1"><?php echo _l('proposals'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($proposal_statuses as $status){ ?>
                                 <li>
                                    <a href="#" data-cview="proposals_<?php echo $status; ?>" onclick="dt_custom_view('proposals_<?php echo $status; ?>','.table-clients','proposals_<?php echo $status; ?>'); return false;">
                                    <?php echo _l('customer_have_proposals_by',format_proposal_status($status,'',false)); ?>
                                    </a>
                                 </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <?php if(count($contract_types) > 0) { ?>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left contract_types">
                              <a href="#" tabindex="-1"><?php echo _l('contract_types'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($contract_types as $type){ ?>
                                 <li>
                                    <a href="#" data-cview="contract_type_<?php echo $type['id']; ?>" onclick="dt_custom_view('contract_type_<?php echo $type['id']; ?>','.table-clients','contract_type_<?php echo $type['id']; ?>'); return false;">
                                    <?php echo _l('customer_have_contracts_by_type',$type['name']); ?>
                                    </a>
                                 </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <?php } ?>
                           <?php if(count($customer_admins) > 0 && (has_permission('customers','','create') || has_permission('customers','','edit'))){ ?>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left responsible_admin">
                              <a href="#" tabindex="-1"><?php echo _l('Nhân viên phụ trách khách hàng'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($customer_admins as $cadmin){ ?>
                                 <li>
                                    <a href="#" data-cview="responsible_admin_<?php echo $cadmin['staff_id']; ?>" onclick="dt_custom_view('responsible_admin_<?php echo $cadmin['staff_id']; ?>','.table-clients','responsible_admin_<?php echo $cadmin['staff_id']; ?>'); return false;">
                                    <?php echo get_staff_full_name($cadmin['staff_id']); ?>
                                    </a>
                                 </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <?php } ?>
                        </ul>
                     </div>
                  </div>
                  <div class="clearfix"></div>
                  <?php if(has_permission('customers','','view') || have_assigned_customers()) {
                     $where_summary = '';
                     if(!has_permission('customers','','view')){
                         $where_summary = ' AND userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id='.get_staff_user_id().')';
                     }
                     ?>
                  <hr />
                  <div class="row mbot15">
                     <div class="col-md-12">
                        <h3 class="text-success no-margin"><?php echo _l('customers_summary'); ?></h3>
                     </div>
                     <div class="col-md-2 col-xs-6 border-right">
                        <h3 class="bold"><?php echo total_rows('tblclients',($where_summary != '' ? substr($where_summary,5) : '')); ?></h3>
                        <span class="text-dark"><?php echo _l('customers_summary_total'); ?></span>
                     </div>
                     <div class="col-md-2 col-xs-6 border-right">
                        <h3 class="bold"><?php echo total_rows('tblclients','active=1'.$where_summary); ?></h3>
                        <span class="text-success"><?php echo _l('active_customers'); ?></span>
                     </div>
                     <div class="col-md-2 col-xs-6 border-right">
                        <h3 class="bold"><?php echo total_rows('tblclients','active=0'.$where_summary); ?></h3>
                        <span class="text-danger"><?php echo _l('inactive_active_customers'); ?></span>
                     </div>
                     <div class="col-md-2 col-xs-6 border-right">
                        <h3 class="bold"><?php echo total_rows('tblcontacts','active=1'.$where_summary); ?></h3>
                        <span class="text-info"><?php echo _l('customers_summary_active'); ?></span>
                     </div>
                     <div class="col-md-2  col-xs-6 border-right">
                        <h3 class="bold"><?php echo total_rows('tblcontacts','active=0'.$where_summary); ?></h3>
                        <span class="text-danger"><?php echo _l('customers_summary_inactive'); ?></span>
                     </div>
                     <div class="col-md-2 col-xs-6">
                        <h3 class="bold"><?php echo total_rows('tblcontacts','last_login LIKE "'.date('Y-m-d').'%"'.$where_summary); ?></h3>
                        <span class="text-muted"><?php echo _l('customers_summary_logged_in_today'); ?></span>
                     </div>
                  </div>
                  <?php } ?>
               </div>
            </div>
            <div class="panel_s">
               <div class="panel-body">
                   <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                       <?php
                           echo render_select('client_name', $clients_iv, array('userid', 'company'), 'Tên');
                       ?>
                   </div>
                   <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                       <?php
                           echo render_input('client_phone','Số điện thoại');
                       ?>
                   </div>
                   <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                       <?php
                           echo render_input('clients_address','Địa chỉ');
                       ?>
                   </div>

                   <div class="col-md-6">
                     <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                     <div class="input-group date">
                        <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                        <div class="input-group-addon">
                           <i class="fa fa-calendar calendar-icon"></i>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                     <div class="input-group date">
                        <input type="text" class="form-control datepicker" id="report-to" name="report-to">
                        <div class="input-group-addon">
                           <i class="fa fa-calendar calendar-icon"></i>
                        </div>
                     </div>
                  </div>

               </div>
           </div>
            <div class="_filters _hidden_inputs hidden">
               <?php
                  foreach($groups as $group){
                      echo form_hidden('customer_group_'.$group['id']);
                  }
                  foreach($contract_types as $type){
                      echo form_hidden('contract_type_'.$type['id']);
                  }
                  foreach($invoice_statuses as $status){
                      echo form_hidden('invoices_'.$status);
                  }
                  foreach($estimate_statuses as $status){
                      echo form_hidden('estimates_'.$status);
                  }
                  foreach($project_statuses as $status){
                     echo form_hidden('projects_'.$status);
                  }
                  foreach($proposal_statuses as $status){
                     echo form_hidden('proposals_'.$status);
                  }
                  foreach($customer_admins as $cadmin){
                     echo form_hidden('responsible_admin_'.$cadmin['staff_id']);
                  }
                  ?>
            </div>
            <div class="panel_s">
               <div class="panel-body">
                  <a href="#" data-toggle="modal" data-target="#customers_bulk_action" class="btn btn-info mbot15"><?php echo _l('bulk_actions'); ?></a>
                  <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                     <div class="modal-dialog" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                           </div>
                           <div class="modal-body">
                              <?php if(has_permission('customers','','delete')){ ?>
                              <div class="checkbox checkbox-danger">
                                 <input type="checkbox" name="mass_delete" id="mass_delete">
                                 <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                              </div>
                              <hr class="mass_delete_separator" />
                              <?php } ?>
                              <div id="bulk_change">
                                 <?php echo render_select('move_to_groups_customers_bulk[]',$groups,array('id','name'),'customer_groups','', array('multiple'=>true),array(),'','',false); ?>
                                 <p class="text-danger"><?php echo _l('bulk_action_customers_groups_warning'); ?></p>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                              <a href="#" class="btn btn-info" onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                           </div>
                        </div>
                        <!-- /.modal-content -->
                     </div>
                     <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                  <div class="clearfix"></div>
                  <?php
                     $table_data = array(

                         _l('#'),
                         '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>',
                        _l('Mã khách hàng'),
                        _l('clients_list_company'),
                        _l('clients_list_phone'),

                        _l('company_primary_email'),

                        _l('clients_address'),
                        _l('als_staff'),

                        _l('customer_active'),
                        _l('customer_groups'),
                        _l('Ngày tạo'),
                        );



                     $custom_fields = get_custom_fields('customers',array('show_on_table'=>1));
                     foreach($custom_fields as $field){
                        array_push($table_data,$field['name']);
                     }

                     $table_data = do_action('customers_table_columns',$table_data);

                     $_op = _l('options');

                     array_push($table_data, $_op);
                     render_datatable($table_data,'clients');
                     ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<div id="contact_data"></div>
<script>

      var filterList = {
         "client_id" : "[name='client_name']",
         "report-from" : "[name='report-from']",
         "report-to" : "[name='report-to']",
      };

      var filterList1 = {
         "client_phone" : "[name='client_phone']",
         "clients_address" : "[name='clients_address']",
      };


    // $('#client_name').change(function(){
    //   var v = $(this).val();
    //   if(v){
    //      jQuery.ajax({
    //             type: "post",
    //             // url:admin_url+"categories/get_childs/"+v,
    //             data: {
    //               'client_id' : v,
    //             },
    //             cache: false,
    //             success: function (data) {

    //             },
    //         });
    //   }
    // });
       function _validate_form(a, b, c) {
  var d = ($(a).validate({
    rules: b,
    messages: {
      email: {
        remote: email_exists
      },
      company: {
        remote: 'Khách hàng này đã tồn tại'
      },
      phonenumber: {
        remote: 'Khách hàng này đã tồn tại'
      },
    },
    ignore: [],
    submitHandler: function(a) {
      return "undefined" == typeof c || void c(a)
    }
  }), $(a).find("[data-custom-field-required]"));
  return d.length > 0 && $.each(d, function() {
    $(this).rules("add", {
      required: !0
    });
    var a = $(this).attr("name"),
      b = $(this).parents(".form-group").find('[for="' + a + '"]');
    b.length > 0 && 0 == b.find(".req").length && b.prepend(' <small class="req text-danger">* </small>')
  }), $.each(b, function(b, c) {
    if ("required" == c && !jQuery.isPlainObject(c) || jQuery.isPlainObject(c) && c.hasOwnProperty("required")) {
      var d = $(a).find('[for="' + b + '"]');
      d.length > 0 && 0 == d.find(".req").length && d.prepend(' <small class="req text-danger">* </small>')
    }
  }), !1
}

   var CustomersServerParams = {};
   $.each($('._hidden_inputs._filters input'),function(){
    CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
   });
   $.each(filterList, function(key, value) {
      CustomersServerParams[key] = value;
   });
   $.each(filterList1, function(key, value) {
      CustomersServerParams[key] = value;
   });
   var headers_clients = $('.table-clients').find('th');
   var not_sortable_clients = (headers_clients.length - 1);
   initDataTable('.table-clients', window.location.href, [not_sortable_clients,0], [not_sortable_clients,0], CustomersServerParams,<?php echo do_action('customers_table_default_order',json_encode(array(0,'DESC'))); ?>);



   $.each(filterList, (key,value)=>{
      $('' + value).on('change', () => {
          $('.table-clients').DataTable().ajax.reload();
      });
  });

   $.each(filterList1, (key,value)=>{
      $('' + value).on('keyup', () => {
          $('.table-clients').DataTable().ajax.reload();
      });
  });

   function customers_bulk_action(event) {
       var r = confirm(confirm_action_prompt);
       if (r == false) {
           return false;
       } else {
           var mass_delete = $('#mass_delete').prop('checked');
           var ids = [];
           var data = {};
           if(mass_delete == false || typeof(mass_delete) == 'undefined'){
               data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
               if (data.groups.length == 0) {
                   data.groups = 'remove_all';
               }
           } else {
               data.mass_delete = true;
           }
           var rows = $('.table-clients').find('tbody tr');
           $.each(rows, function() {
               var checkbox = $($(this).find('td').eq(0)).find('input');
               if (checkbox.prop('checked') == true) {
                   ids.push(checkbox.val());
               }
           });
           data.ids = ids;
           $(event).addClass('disabled');
           setTimeout(function(){
             $.post(admin_url + 'clients/bulk_action', data).done(function() {
                window.location.reload();
            });
         },50);
       }
   }
   function init_client(a) {
       init_client_modal_data(a) && $(".client-modal").modal("show")

   }
   function init_client_modal_data(id, url) {
       if (typeof(id) == 'undefined') {
           id = '';
       }
       var _url = admin_url + 'clients/modal/' + id;
       if (typeof(url) != 'undefined') {
           _url = url;
       }
       // get the current hash
       var hash = window.location.hash;
       // clean the modal
       // $('.lead-modal .modal-content').html('');
       $.get(_url, function(response) {
           $('.client-modal .modal-content').html(response.data)
           $('#client_reminder_modal').html(response.reminder_data);
           //
           Dropzone.options.clientAttachmentsUpload = false;
    if ($('#client-attachments-upload').length > 0) {
      new Dropzone('#client-attachments-upload', {
        paramName: "file",
        dictDefaultMessage:drop_files_here_to_upload,
        dictFallbackMessage:browser_not_support_drag_and_drop,
        dictRemoveFile:remove_file,
        dictFileTooBig: file_exceds_maxfile_size_in_form,
        dictMaxFilesExceeded:you_can_not_upload_any_more_files,
        maxFilesize: max_php_ini_upload_size.replace(/\D/g, ''),
        addRemoveLinks: false,
        accept: function(file, done) {
          done();
        },
        acceptedFiles: allowed_files,
        error: function(file, response) {
          alert_float('danger', response);
          $('.dz-preview.dz-file-preview').remove();
        },
        success: function(file, response) {
         response = JSON.parse(response);
         if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $('.dz-preview.dz-file-preview').remove();
            $('.dropzone.dz-started .dz-message').css({'display':'block'});
            alert_float(response.alert_type,response.message);
         }
       }
     });
   }

   $(".class-form-reminder").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.

    var url1 = $(this)[0].action; // the script where you handle the form input.

        $.ajax({
               type: "POST",
               url: url1,
               dataType:'JSON',
               data: $(".class-form-reminder").serialize(), // serializes the form's elements.
               success: function(data)
               {
                  alert_float(data.alert_type,data.message);
                  $("body").find(".reminder-modal--").modal('hide');
               }
             });

    });


      $("body").on("click", ".delete_attachment", function(a) {
         var id = $(this).data('id');
         var id_cus = $(this).data('customer');
         var url = "<?php echo admin_url('clients/delete_attachment/'); ?>";
         var b = confirm(confirm_action_prompt);
         if(1 == b){
            $.ajax({
           type: "GET",
           url: url+ id_cus +'/' + id,
           dataType:'JSON',
           success: function(data)
           {
              alert_float(data.alert_type,data.message);
           }
         });
         }
      });


   if(id){
   $("#client_form").submit(function(e) {
          e.preventDefault(); // avoid to execute the actual submit of the form.

          var url1 = $(this)[0].action; // the script where you handle the form input.

          $.ajax({
                 type: "POST",
                 url: url1,
                 dataType:'JSON',
                 data: $("#client_form").serialize(), // serializes the form's elements.
                 success: function(data)
                 {
                    alert_float(data.alert_type,data.message);
                 }
               });

      });
   }

   $("#form-assign").submit(function(e) {
          e.preventDefault(); // avoid to execute the actual submit of the form.

          var url1 = $(this)[0].action; // the script where you handle the form input.

          $.ajax({
                 type: "POST",
                 url: url1,
                 dataType:'JSON',
                 data: $("#form-assign").serialize(), // serializes the form's elements.
                 success: function(data)
                 {
                    alert_float(data.alert_type,data.message);
                 }
               });

      });



   _validate_form($('#client_form'), {
            address: 'required',
            name_contact: 'required',
            mobilephone_number: 'required',
            company:{
                required:true,
                remote: {
                    url: admin_url + "clients/check_exists",
                    type: 'post',
                    data: {
                        company:function(){
                            return $('input[name="company"]').val();
                        },
                        clientid:function(){
                            return $('#clientid').val();
                        },
                    }
                }
            },

            phonenumber:{

                remote: {
                    url: admin_url + "clients/check_exists",
                    type: 'post',
                    data: {
                        phonenumber:function(){
                            return $('input[name="phonenumber"]').val();
                        },
                        clientid:function(){
                            return $('#clientid').val();
                        },
                    }
                }
            },



        });


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
        init_rel_tasks_table(id,'customer');
        init_rel_tasks_table1(id,'customer');
        /* Custome profile reminders table */
       initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + id + '/' + 'customer', [4], [4]);
       // initDataTable('.table-customer_admin-client', admin_url + 'misc/get_reminders/' + id + '/' + 'customer', [4], [4]);
       initDataTableOffline('.table.dt-table');
           //
           //

           /* Custome profile contacts table */
           let not_sortable_contracts = $('.table-contracts-single-client').find('th').length -1;
           _table_api = initDataTable('.table-contracts-single-client', admin_url + 'contracts/index/' + id, [not_sortable_contracts], [not_sortable_contracts], 'undefined', [3, 'DESC']);
           if(_table_api){
             _table_api.column(2).visible(false,false).columns.adjust();
           }

           /* Custome profile contacts table */
           let not_sortable_contacts = $('.table-contacts').find('th').length -1;
           initDataTable('.table-contacts', admin_url + 'clients/contacts/' + id, [not_sortable_contacts], [not_sortable_contacts]);

           let not_sortable_quote = $('.table-quote_clients').find('th').length -1;
           initDataTable('.table-quote_clients', admin_url + 'clients/quotes/' + id, [not_sortable_quote], [not_sortable_quote]);

           //

           //

           /* Custome profile payments table */
           _table_api = initDataTable('.table-payments-single-client', admin_url + 'payments/list_payments/' + id, [7], [7], 'undefined', [6, 'DESC']);
           if(_table_api){
             _table_api.column(4).visible(false, false).columns.adjust();
           }
           //
           $('.client-modal').modal({
               show: true,
               backdrop: 'static'
           });
           init_selectpicker();
           init_form_reminder();
           init_tags_input_phone();
           init_datepicker();
           init_color_pickers();
           validate_lead_form(lead_profile_form_handler);

           if (hash == '#tab_lead_profile' || hash == '#attachments' || hash == '#lead_notes') {
               window.location.hash = hash;
           }
           if (id != '') {

               if (typeof(Dropbox) != 'undefined') {
                   document.getElementById("dropbox-chooser-lead").appendChild(Dropbox.createChooseButton({
                       success: function(files) {
                           $.post(admin_url + 'leads/add_external_attachment', {
                               files: files,
                               lead_id: id,
                               external: 'dropbox'
                           }).done(function() {
                               init_lead_modal_data(id);
                           });
                       },
                       linkType: "preview",
                       extensions: allowed_files.split(','),
                   }));
               }

               if (typeof(leadAttachmentsDropzone) != 'undefined') {
                   leadAttachmentsDropzone.destroy();
               }

               leadAttachmentsDropzone = new Dropzone("#lead-attachment-upload", {
                   addRemoveLinks: false,
                   dictDefaultMessage: drop_files_here_to_upload,
                   dictFallbackMessage: browser_not_support_drag_and_drop,
                   dictRemoveFile: remove_file,
                   dictMaxFilesExceeded: you_can_not_upload_any_more_files,
                   sending: function(file, xhr, formData) {
                       formData.append("leadid", id);
                   },
                   acceptedFiles: allowed_files,
                   error: function(file, response) {
                       alert_float('danger', response);
                   },
                   success: function(file) {
                       if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                           init_lead_modal_data(id);
                       }
                   }
               });

               $('body').find('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
               $('#lead-latest-activity').html($('#lead_activity').find('.feed-item:last-child .text').html());
           }

       }, 'json').fail(function(data) {
           $('.lead-modal').modal('hide');
           alert_float('danger', data.responseText);
       });
   }

</script>
</body>
</html>
