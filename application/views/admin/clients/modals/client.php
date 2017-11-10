<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title">
      <?php if(isset($client)){
         if(!empty($client->name)){
            $name = $client->name;
         } else if(!empty($client->company)){
            $name = $client->company;
         } else {
            $name = _l('client');
         }
         echo '#'.$client->userid . ' - ' .  $name;
         } else {
         echo _l('add_new',_l('client'));
         }
         ?>
   </h4>
</div>
<div class="modal-body">
   <div class="top-lead-menu">
      <ul class="nav nav-tabs<?php if(!isset($client)){echo ' client-new';} ?>" role="tablist">
         <li role="presentation" class="active" >
            <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
            <?php echo _l('customer_profile_details'); ?>
            </a>
         </li>
         <?php if(isset($client)){ ?>
         <li role="presentation">
            <a href="#contacts" aria-controls="contacts" role="tab" data-toggle="tab">
            <?php echo _l( 'customer_contacts'); ?>
            </a>
         </li>
         <li role="presentation">
            <a href="#customer_admins" aria-controls="customer_admins" role="tab" data-toggle="tab">
            <?php echo _l( 'customer_admins'); ?>
            </a>
         </li>
         <li role="presentation">
            <a href="#tasks" aria-controls="task" role="tab" data-toggle="tab">
            <?php echo _l( 'Giao dịch'); ?>
            </a>
         </li>
         <li role="presentation">
            <a href="#quotes" aria-controls="quotes" role="tab" data-toggle="tab">
            <?php echo _l( 'Báo giá'); ?>
            </a>
         </li>
         <li role="presentation">
            <a href="#contracts" aria-controls="contracts" role="tab" data-toggle="tab">
            <?php echo _l( 'contracts'); ?>
            </a>
         </li>
         <li role="presentation">
            <a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">
            <?php echo _l( 'customer_attachments'); ?>
            </a>
         </li>
         <li role="presentation">
            <a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">
            <?php echo _l( 'payments'); ?>
            </a>
         </li>
         <!-- <li role="presentation">
            <a href="#reminders" aria-controls="reminders" role="tab" data-toggle="tab">
            <?php echo _l( 'reminder'); ?>
            </a>
            </li> -->
         <li role="presentation">
            <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">
            <?php echo _l( 'note'); ?>
            </a>
         </li>
         <?php } ?>
      </ul>
   </div>
   <!-- Tab panes -->
   <div class="tab-content">
      <!-- from leads modal -->
      <div role="tabpanel" class="tab-pane active" id="contact_info">
         <?php $this->load->view('admin/clients/modals/profile'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="customer_admins">
        <h4 class="no-mtop bold">Phụ trách khách hàng</h4>
          <hr>
         <?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
         <a href="#" data-toggle="modal" data-target="#customer_admins_assign" class="btn btn-info mbot30"><?php echo _l('assign_admin'); ?></a>
         <?php } ?>
         <table class="table dt-table table-customer_admin-client">
            <thead>
               <tr>
                  <th><?php echo _l('staff_member'); ?></th>
                  <th><?php echo _l('customer_admin_date_assigned'); ?></th>
                  <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                  <th><?php echo _l('options'); ?></th>
                  <?php } ?>
               </tr>
            </thead>
            <tbody>
               <?php foreach($customer_admins as $c_admin){ ?>
               <tr>
                  <td><a href="<?php echo admin_url('profile/'.$c_admin['staff_id']); ?>">
                     <?php echo staff_profile_image($c_admin['staff_id'], array(
                        'staff-profile-image-small',
                        'mright5'
                        ));
                        echo get_staff_full_name($c_admin['staff_id']); ?></a>
                  </td>
                  <td data-order="<?php echo $c_admin['date_assigned']; ?>"><?php echo _dt($c_admin['date_assigned']); ?></td>
                  <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                  <td>
                     <a href="<?php echo admin_url('clients/delete_customer_admin/'.$client->userid.'/'.$c_admin['staff_id']); ?>" class="btn btn-danger _delete btn-icon"><i class="fa fa-remove"></i></a>
                  </td>
                  <?php } ?>
               </tr>
               <?php } ?>
            </tbody>
         </table>
      </div>
      <div role="tabpanel" class="tab-pane" id="contacts">
        <h4 class="no-mtop bold">Liên hệ</h4>
        <hr>
         <?php if(has_permission('customers','','create') || is_customer_admin($client->userid)){
            $disable_new_contacts = false;
            if(is_empty_customer_company($client->userid) && total_rows('tblcontacts',array('userid'=>$client->userid)) == 1){
               $disable_new_contacts = true;
            }
            ?>
         <div class="inline-block"<?php if($disable_new_contacts){ ?> data-toggle="tooltip" data-title="<?php echo _l('customer_contact_person_only_one_allowed'); ?>"<?php } ?>>
            <a href="#" onclick="contact(<?php echo $client->userid; ?>); return false;" class="btn btn-info mbot25<?php if($disable_new_contacts){echo ' disabled';} ?>"><?php echo _l('new_contact'); ?></a>
         </div>
         <?php } ?>
         <?php
            $table_data = array(_l('client_firstname'),_l('client_lastname'),_l('client_email'),_l('contact_position'),_l('client_phonenumber'),_l('contact_active'),_l('clients_list_last_login'));
            $custom_fields = get_custom_fields('contacts',array('show_on_table'=>1));
            foreach($custom_fields as $field){
               array_push($table_data,$field['name']);
            }
            array_push($table_data,_l('options'));
            echo render_datatable($table_data,'contacts'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="payments">
         <?php if(isset($client)){ ?>
         <h4 class="no-mtop bold"><?php echo _l('client_payments_tab'); ?></h4>
         <hr />
         <a href="#" class="btn btn-info mbot25" data-toggle="modal" data-target="#client_zip_payments"><?php echo _l('zip_payments'); ?></a>
         <?php render_datatable(array(
            _l('payments_table_number_heading'),
            _l('payments_table_invoicenumber_heading'),
            _l('payments_table_mode_heading'),
            _l('payment_transaction_id'),
            _l('payments_table_client_heading'),
            _l('payments_table_amount_heading'),
            _l('payments_table_date_heading'),
            _l('options')
            ),'payments-single-client'); ?>
         <?php include_once(APPPATH . 'views/admin/clients/modals/zip_payments.php'); ?>
         <?php } ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="contracts">
         <h4 class="no-mtop bold"><?php echo _l('contracts_invoices_tab'); ?></h4>
         <hr />
         <?php if(has_permission('contracts','','create')){ ?>
         <a href="<?php echo admin_url('contracts/contract?customer_id='.$client->userid); ?>" class="btn btn-info mbot25<?php if($client->active == 0){echo ' disabled';} ?>" target="_blank"><?php echo _l('new_contract'); ?></a>
         <div class="clearfix"></div>
         <?php } ?>
         <?php
            $table_data = array(
              '#',
              _l('Số hợp đồng'),
              _l('contract_list_client'),
              _l('contract_types_list_name'),
              _l('contract_list_start_date'),
              _l('contract_list_end_date'),
              );
            $custom_fields = get_custom_fields('contracts',array('show_on_table'=>1));
            foreach($custom_fields as $field){
              array_push($table_data,$field['name']);
            }
            array_push($table_data,_l('options'));
            render_datatable($table_data, 'contracts-single-client'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="quotes">
          <h4 class="no-mtop bold">Báo giá</h4>
          <hr>
         <a href="#" onclick="init_quote_client(<?php echo $client->userid ?>); return false;" class="btn btn-info mbot30">Thêm báo giá</a>
         <!-- <hr />
            <?php if(has_permission('contracts','','create')){ ?>
            <a href="<?php echo admin_url('contracts/contract?customer_id='.$client->userid); ?>" class="btn btn-info mbot25<?php if($client->active == 0){echo ' disabled';} ?>"><?php echo _l('new_contract'); ?></a> -->
         <div class="clearfix"></div>
         <?php } ?>
         <?php
            $table_data = array(
              '#',
              _l('Số báo giá'),
              _l('Người tạo'),
              _l('Ngày tạo'),
              _l('Tổng giá trị sản phẩm'),
              _l('Tổng phát sinh'),
              );
            $custom_fields = get_custom_fields('quotes',array('show_on_table'=>1));
            foreach($custom_fields as $field){
              array_push($table_data,$field['name']);
            }
            // array_push($table_data,_l('options'));
            render_datatable($table_data, 'quote_clients'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="tasks">
        <h4 class="no-mtop bold"><?php echo _l('Giao dịch'); ?></h4>
          <hr>
         <?php if(isset($client)){

            init_relation_tasks_table1(array( 'data-new-rel-id'=>$client->userid,'data-new-rel-type'=>'customer'));
            } ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="attachments">
         <div class="modal fade" id="customer_file_share_file_with" data-total-contacts="<?php echo count($contacts); ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title"><?php echo _l('share_file_with'); ?></h4>
                  </div>
                  <div class="modal-body">
                     <?php echo form_hidden('file_id'); ?>
                     <?php echo render_select('share_contacts_id[]',$contacts,array('id',array('firstname','lastname')),'customer_contacts',array(get_primary_contact_user_id($client->userid)),array('multiple'=>true),array(),'','',false); ?>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                     <button type="button" class="btn btn-info" onclick="do_share_file_contacts();"><?php echo _l('confirm'); ?></button>
                  </div>
               </div>
               <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
         </div>
         <!-- /.modal -->
         <h4 class="no-mtop bold"><?php echo _l('customer_attachments'); ?></h4>
         <hr />
         <?php if(isset($client)){ ?>
         <?php echo form_open_multipart(admin_url('clients/upload_attachment/'.$client->userid),array('class'=>'dropzone','id'=>'client-attachments-upload')); ?>
         <input type="file" name="file" multiple />
         <?php echo form_close(); ?>
         <div class="text-right mtop15">
            <div id="dropbox-chooser"></div>
         </div>
         <div class="attachments">
            <div class="table-responsive mtop25">
               <table class="table dt-table" data-order-col="2" data-order-type="desc">
                  <thead>
                     <tr>
                        <th width="30%"><?php echo _l('customer_attachments_file'); ?></th>
                        <th><?php echo _l('customer_attachments_show_in_customers_area'); ?></th>
                        <th><?php echo _l('file_date_uploaded'); ?></th>
                        <th><?php echo _l('options'); ?></th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach($attachments as $type => $attachment){
                        $download_indicator = 'id';
                        $key_indicator = 'rel_id';
                        $upload_path = get_upload_path_by_type($type);
                        if($type == 'invoice'){
                            $url = site_url() .'download/file/sales_attachment/';
                            $download_indicator = 'attachment_key';
                        } else if($type == 'proposal'){
                            $url = site_url() .'download/file/sales_attachment/';
                            $download_indicator = 'attachment_key';
                        } else if($type == 'estimate'){
                            $url = site_url() .'download/file/sales_attachment/';
                            $download_indicator = 'attachment_key';
                        } else if($type == 'contract'){
                            $url = site_url() .'download/file/contract/';
                        } else if($type == 'lead'){
                            $url = site_url() .'download/file/lead_attachment/';
                        } else if($type == 'task'){
                            $url = site_url() .'download/file/taskattachment/';
                        } else if($type == 'ticket'){
                            $url = site_url() .'download/file/ticket/';
                            $key_indicator = 'ticketid';
                        } else if($type == 'customer'){
                            $url = site_url() .'download/file/client/';
                        } else if($type == 'expense'){
                            $url = site_url() .'download/file/expense/';
                            $download_indicator = 'rel_id';
                        }
                        ?>
                     <?php foreach($attachment as $_att){
                        ?>
                     <tr id="tr_file_<?php echo $_att['id']; ?>">
                        <td>
                           <?php
                              $path = $upload_path . $_att[$key_indicator] . '/' . $_att['file_name'];
                              $is_image = false;
                              if(!isset($_att['external'])) {
                                 $attachment_url = $url . $_att[$download_indicator];
                                 $is_image = is_image($path);
                                 $img_url = site_url('download/preview_image?path='.protected_file_url_by_path($path).'&type='.$_att['filetype']);
                              } else if(isset($_att['external']) && !empty($_att['external'])){

                                 if(!empty($_att['thumbnail_link'])){
                                     $is_image = true;
                                     $img_url = optimize_dropbox_thumbnail($_att['thumbnail_link']);
                                 }

                                 $attachment_url = $_att['external_link'];
                              }
                              if($is_image){
                                 echo '<div class="preview_image">';
                              }
                              ?>
                           <a href="<?php if($is_image){ echo $img_url; } else {echo $attachment_url; } ?>"<?php if($is_image){ ?> data-lightbox="customer-profile" <?php } ?> class="display-block mbot5">
                              <?php if($is_image){ ?>
                              <div class="table-image">
                                 <img src="<?php echo $img_url; ?>">
                              </div>
                              <?php } else { ?>
                              <i class="<?php echo get_mime_class($_att['filetype']); ?>"></i> <?php echo $_att['file_name']; ?>
                              <?php } ?>
                           </a>
                           <?php if($is_image){
                              echo '</div>';
                              }
                              ?>
                        </td>
                        <td>
                           <div class="onoffswitch"<?php if($type != 'customer'){?> data-toggle="tooltip" data-title="<?php echo _l('customer_attachments_show_notice'); ?>" <?php } ?>>
                              <input type="checkbox" <?php if($type != 'customer'){echo 'disabled';} ?> id="<?php echo $_att['id']; ?>" data-id="<?php echo $_att['id']; ?>" class="onoffswitch-checkbox customer_file" data-switch-url="<?php echo admin_url(); ?>misc/toggle_file_visibility" <?php if(isset($_att['visible_to_customer']) && $_att['visible_to_customer'] == 1){echo 'checked';} ?>>
                              <label class="onoffswitch-label" for="<?php echo $_att['id']; ?>"></label>
                           </div>
                           <?php if($type == 'customer' && $_att['visible_to_customer'] == 1){
                              $file_visibility_message = '';
                              $total_shares = total_rows('tblcustomerfiles_shares',array('file_id'=>$_att['id']));

                              if($total_shares == 0){
                                  $file_visibility_message = _l('file_share_visibility_notice');
                              } else {
                                  $share_contacts_id = get_customer_profile_file_sharing(array('file_id'=>$_att['id']));
                                  if(count($share_contacts_id) == 0){
                                      $file_visibility_message = _l('file_share_visibility_notice');
                                  }
                              }
                              echo '<span class="text-warning'.(empty($file_visibility_message) || total_rows('tblcontacts',array('userid'=>$client->userid)) == 0 ? ' hide': '').'">'.$file_visibility_message.'</span>';
                              if(isset($share_contacts_id) && count($share_contacts_id) > 0){
                                  $names = '';
                                  $contacts_selected = '';
                                  foreach($share_contacts_id as $file_share){
                                      $names.= get_contact_full_name($file_share['contact_id']) .', ';
                                      $contacts_selected .= $file_share['contact_id'].',';
                                  }
                                  if($contacts_selected != ''){
                                      $contacts_selected = substr($contacts_selected,0,-1);
                                  }
                                  if($names != ''){
                                      echo '<a href="#" onclick="do_share_file_contacts(\''.$contacts_selected.'\','.$_att['id'].'); return false;"><i class="fa fa-pencil-square-o"></i></a> ' . _l('share_file_with_show',mb_substr($names, 0,-2));
                                  }
                              }
                              }
                              ?>
                        </td>
                        <td data-order="<?php echo $_att['dateadded']; ?>"><?php echo _dt($_att['dateadded']); ?></td>
                        <td>
                           <?php if(!isset($_att['external'])){ ?>
                           <button type="button" data-toggle="modal" data-file-name="<?php echo $_att['file_name']; ?>" data-filetype="<?php echo $_att['filetype']; ?>" data-path="<?php echo $path; ?>" data-target="#send_file" class="btn btn-info btn-icon"><i class="fa fa-envelope"></i></button>
                           <?php } else if(isset($_att['external']) && !empty($_att['external'])) {
                              echo '<a href="'.$_att['external_link'].'" class="btn btn-info btn-icon" target="_blank"><i class="fa fa-dropbox"></i></a>';
                              } ?>
                           <?php if($type == 'customer'){ ?>
                           <!-- <?php echo admin_url('clients/delete_attachment/'.$_att['rel_id'].'/'.$_att['id']); ?> -->
                           <a data-id="<?php echo $_att['id'] ?>" data-customer="<?php echo $_att['rel_id']  ?>" href="javascript:void(0)"  class="btn btn-danger btn-icon delete_attachment"><i class="fa fa-remove"></i></a>
                           <?php } ?>
                        </td>
                        <?php } ?>
                     </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
         <?php
            include_once(APPPATH . 'views/admin/clients/modals/send_file_modal.php');
            } ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="reminders">
         <h4 class="no-mtop bold"><?php echo _l('client_reminders_tab'); ?></h4>
         <hr />
         <?php if(isset($client)){ ?>
         <a href="#" data-toggle="modal" data-target=".reminder-modal-customer-<?php echo $client->userid; ?>" class="btn btn-info mbot25"><i class="fa fa-bell-o"></i> <?php echo _l('set_reminder'); ?></a>
         <div class="clearfix"></div>
         <?php render_datatable(array( _l( 'reminder_description'), _l( 'reminder_date'), _l( 'reminder_staff'), _l( 'reminder_is_notified'), _l( 'options'), ), 'reminders');
            $this->load->view('admin/includes/modals/reminder',array('id'=>$client->userid,'name'=>'customer','members'=>$members,'reminder_title'=>_l('set_reminder')));
            } ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="notes">
         <div class="">
            <h4 class="no-mtop bold"><?php echo _l('contracts_notes_tab'); ?></h4>
            <a href="#" class="btn btn-success mtop15 mbot10" onclick="slideToggle('.usernote'); return false;"><?php echo _l('new_note'); ?></a>
            <div class="clearfix"></div>
            <hr />
            <div class="clearfix"></div>
            <div class="usernote hide">
               <?php echo form_open(admin_url( 'misc/add_note/'.$client->userid.'/customer'),array('id'=>'form-note')); ?>
               <?php echo render_textarea( 'description', 'note_description', '',array( 'rows'=>5)); ?>
               <button class="btn btn-info pull-right mbot15">
               <?php echo _l( 'submit'); ?>
               </button>
               <?php echo form_close(); ?>
            </div>
            <div class="table-responsive mtop15">
               <table class="table dt-table" data-order-col="2" data-order-type="desc">
                  <thead>
                     <tr>
                        <th width="50%">
                           <?php echo _l( 'clients_notes_table_description_heading'); ?>
                        </th>
                        <th>
                           <?php echo _l( 'clients_notes_table_addedfrom_heading'); ?>
                        </th>
                        <th>
                           <?php echo _l( 'clients_notes_table_dateadded_heading'); ?>
                        </th>
                        <th>
                           <?php echo _l( 'options'); ?>
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach($user_notes as $note){ ?>
                     <tr>
                        <td width="50%">
                           <div data-note-description="<?php echo $note['id']; ?>">
                              <?php echo $note['description']; ?>
                           </div>
                           <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide">
                              <textarea name="description" class="form-control" rows="4"><?php echo clear_textarea_breaks($note['description']); ?></textarea>
                              <div class="text-right mtop15">
                                 <button type="button" class="btn btn-default" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                                 <button type="button" class="btn btn-info" onclick="edit_note(<?php echo $note['id']; ?>);"><?php echo _l('update_note'); ?></button>
                              </div>
                           </div>
                        </td>
                        <td>
                           <?php
                              ?>
                           <?php echo '<a href="'.admin_url( 'profile/'.$note[ 'addedfrom']). '">'.$note[ 'fullname']. '</a>' ?>
                        </td>
                        <td data-order="<?php echo $note['dateadded']; ?>">
                           <?php echo _dt($note[ 'dateadded']); ?>
                        </td>
                        <td>
                           <?php if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
                           <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
                           <a href="<?php echo admin_url('misc/delete_note/'. $note['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                           <?php } ?>
                        </td>
                     </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<?php if(isset($client)){ ?>
<?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('clients/assign_admins/'.$client->userid),array('id'=>'form-assign')); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('assign_admin'); ?></h4>
         </div>
         <div class="modal-body">
            <?php
               $selected = array();
               foreach($customer_admins as $c_admin){
                  array_push($selected,$c_admin['staff_id']);
               }
               echo render_select('customer_admins[]',$staff,array('staffid',array('firstname','lastname')),'',$selected,array('multiple'=>true),array(),'','',false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>
<?php } ?>
<script type="text/javascript">
   // others script write here
   $('.client-form').validate({});
   function addRules(rulesObj){
     for (var item in rulesObj){
       $('#'+item).rules('add',rulesObj[item]);
     }
   }


   function removeRules(rulesObj){
     for (var item in rulesObj){
       $('#'+item).rules('remove');
     }
   }
   var client_type = $('#client_type option:selected').val();

   var personalRules = {
       short_name : {
         require: true,
       },
   };
   var companyRules = {
       type_of_organization : {
         require: true,
       },
   };
   var switchMode = () => {
     var name_title                    = $('#name_title');
     var cooperative_day               = $('#cooperative_day');
     var legal_representative          = $('#legal_representative');
     var bussiness_registration_number = $('#bussiness_registration_number');
     var id_card                       = $('#id_card');
     var birthday                      = $('label[for="birthday"]');
     var mobilephone_number            = $('#mobilephone_number,#phonenumber');
     var type_of_organization          = $('#type_of_organization');
     var vat                           = $('#vat2');
     var short_name                    = $('#short_name');
     var billing_col                   = $('.shipping_address_lane');
     var website                       = $('#website,#business');
     var zip                           = $('#zip');
     var default_language              = $('#default_language');

     if(client_type == 1) {
       $('label[for="company"]').html('<small class="req text-danger">* </small> <?=_l('client-name')?> <?=_l('client-personal')?>');

       // Company
       birthday.html('<?=_l('date_birth')?>');
       type_of_organization.parent().parent().hide();
       vat.parent().hide();
       bussiness_registration_number.parent().hide();
       legal_representative.parent().hide();
       cooperative_day.parent().parent().hide();
       billing_col.hide();

       website.parents('.form-group').hide();
       zip.parents('.form-group').hide();
       default_language.parents('.form-group').hide();

       // Personal
       id_card.parent().show();
       mobilephone_number.parent().show();
       short_name.parent().show();

       // removeRules(companyRules);
       // addRules(personalRules);
     }
     else {
       $('label[for="company"]').html('<small class="req text-danger">* </small> <?=_l('client-name')?> <?=_l('client-company')?>');
       // Company
       birthday.html('<?=_l('client-company-birthday')?>');
       type_of_organization.parent().parent().show();
       vat.parent().show();
       bussiness_registration_number.parent().show();
       legal_representative.parent().show();
       cooperative_day.parent().parent().show();
       billing_col.show();
       website.parents('.form-group').show();
       zip.parents('.form-group').show();
       default_language.parents('.form-group').show();

       // Personal
       mobilephone_number.parent().hide();
       short_name.parent().hide();

       // removeRules(personalRules);
       // addRules(companyRules);

     }
   };
   // switchMode();
   $(document).on('click','.close',function(){
     $('#customer_admins_assign').modal("hide");
     $('#client_zip_payments').modal("hide");
   })
   $(document).ready(()=>{
      $('body').on('click', '.delete-reminder', function() {
         $(this).parents('tr').remove();
      });
     var default_city  = '<?php echo isset($client) ? $client->city : 0 ?>';
     var default_state = '<?php echo isset($client) ? $client->state : 0 ?>';
     var default_ward  = '<?php echo isset($client) ? $client->address_ward : 0?>';

     var default_city_billing  = '<?php echo isset($client) ? $client->billing_city : 0 ?>';
     var default_state_billing = '<?php echo isset($client) ? $client->billing_state : 0 ?>';
     var default_ward_billing  = '<?php echo isset($client) ? $client->billing_ward : 0?>';

     var default_city_shipping  = '<?php echo isset($client) ? $client->shipping_city : 0 ?>';
     var default_state_shipping = '<?php echo isset($client) ? $client->shipping_state : 0 ?>';
     var default_ward_shipping  = '<?php echo isset($client) ? $client->shipping_ward : 0?>';

     var default_city_dkkd  = '<?php echo isset($client) ? $client->dkkd_city : 0 ?>';
     var default_state_dkkd = '<?php echo isset($client) ? $client->dkkd_state : 0 ?>';
     var default_ward_dkkd  = '<?php echo isset($client) ? $client->dkkd_ward : 0?>';

     var loadFromCity = (city_id, currentTarget, default_value_state, default_value_ward) => {

       var objState = $(currentTarget).parents('.col-md-4').next().find('select');
       var objWard = $(currentTarget).parent().parent().next().next().find('select');


       objState.find('option').remove();
       objState.append('<option value=""></option>');
       objWard.find('option').remove();
       objWard.append('<option value=""></option>');

       objState.selectpicker("refresh");
       objWard.selectpicker("refresh");

       if(city_id != 0 && city_id != '') {
         $.ajax({
           url : admin_url + 'clients/get_districts/' + city_id,
           dataType : 'json',
         })
         .done((data) => {
           objState.find('option').remove();
           objState.append('<option value=""></option>');
           var foundSelected = false;
           $.each(data, (key,value) => {
             var stringSelected = "";
             if(!foundSelected && value.districtid == default_value_state) {
               stringSelected = ' selected="selected"';
               foundSelected = true;
             }
             objState.append('<option value="' + value.districtid + '"'+stringSelected+'>' + value.name + '</option>');
           });
           objState.selectpicker('refresh');
           if(foundSelected) {
             loadFromState(default_value_state, objState, default_value_ward);
           }
         });
       }
     };
     var loadFromState = (state_id, currentTarget, default_value_ward) => {
       var objWard = $(currentTarget).parent().parent().next().find('select');

       objWard.find('option').remove();
       objWard.append('<option value=""></option>');
       objWard.selectpicker("refresh");
       if(state_id != 0 && state_id != '') {
         $.ajax({
           url : admin_url + 'clients/get_wards/' + state_id,
           dataType : 'json',
         })
         .done((data) => {
           $.each(data, (key,value) => {
             var stringSelected = "";
             if(value.wardid == default_value_ward) {
               stringSelected = 'selected="selected"';
             }
             objWard.append('<option value="' + value.wardid + '"' + stringSelected + '>' + value.name + '</option>');
           });
           objWard.selectpicker('refresh');
         });
       }
     };
     $(document).on('change','#city',(e)=>{
       var city_id = $(e.currentTarget).val();
       loadFromCity(city_id, e.currentTarget, default_state, default_ward);
     })

     $('#billing_city').change((e)=>{
       var city_id = $(e.currentTarget).val();
       loadFromCity(city_id, e.currentTarget, default_state_billing, default_ward_billing);
     });

     $(document).on('change','#shipping_city',(e)=>{
       var city_id = $(e.currentTarget).val();
        loadFromCity(city_id, e.currentTarget, default_state_shipping, default_ward_shipping);
     })

     $('#dkkd_city').change((e)=>{
       var city_id = $(e.currentTarget).val();
       loadFromCity(city_id, e.currentTarget, default_state_dkkd, default_ward_dkkd);
     });

     $('#state').change((e)=>{
       var state_id = $(e.currentTarget).val();
       loadFromState(state_id, e.currentTarget, default_ward);
     });
     $('#billing_state').change((e)=>{
       var state_id = $(e.currentTarget).val();
       loadFromState(state_id, e.currentTarget, default_ward_billing);
     });
     $('#shipping_state').change((e)=>{
       var state_id = $(e.currentTarget).val();
       loadFromState(state_id, e.currentTarget, default_ward_shipping);
     });
     $('#dkkd_state').change((e)=>{
       var state_id = $(e.currentTarget).val();
       loadFromState(state_id, e.currentTarget, default_ward_dkkd);
     });

     loadFromCity(default_city, $('#city'), default_state, default_ward);
     loadFromCity(default_city_billing, $('#billing_city'), default_state_billing, default_ward_billing);
     loadFromCity(default_city_shipping, $('#shipping_city'), default_state_shipping, default_ward_shipping);
     loadFromCity(default_city_dkkd, $('#dkkd_city'), default_state_dkkd, default_ward_dkkd);

     $('#client_type').change((e)=>{
       client_type = $(e.currentTarget).find('option:selected').val();
       switchMode();
     });

     $('.billing-same-as-customer').on('click', function(e) {
       e.preventDefault();
       $('select[name="billing_area"]').selectpicker('val', $('select[name="address_area"]').selectpicker('val'));
       $('select[name="billing_country"]').selectpicker('val', $('select[name="country"]').selectpicker('val'));
       $('select[name="billing_city"]').selectpicker('val', $('select[name="city"]').selectpicker('val'));
       loadFromCity($('select[name="city"]').selectpicker('val'), $('select[name="billing_city"]'), $('select[name="state"]').selectpicker('val'), $('select[name="address_ward"]').selectpicker('val'));

       $('input[name="billing_room_number"]').val($('input[name="address_room_number"]').val());
       $('input[name="billing_building"]').val($('input[name="address_building"]').val());
       $('input[name="billing_home_number"]').val($('input[name="address_home_number"]').val());
       $('input[name="billing_street"]').val($('input[name="address"]').val());
       $('input[name="billing_town"]').val($('input[name="address_town"]').val());
       $('input[name="billing_zip"]').val($('input[name="zip"]').val());

     });
     $('.customer-copy-billing-address').on('click', function(e) {
       e.preventDefault();
       $('select[name="shipping_area"]').selectpicker('val', $('select[name="billing_area"]').selectpicker('val'));
       $('select[name="shipping_country"]').selectpicker('val', $('select[name="billing_country"]').selectpicker('val'));

       $('select[name="shipping_city"]').selectpicker('val', $('select[name="billing_city"]').selectpicker('val'));
       loadFromCity($('select[name="shipping_city"]').selectpicker('val'), $('select[name="shipping_city"]'), $('select[name="billing_state"]').selectpicker('val'), $('select[name="billing_ward"]').selectpicker('val'));


       $('input[name="shipping_room_number"]').val($('input[name="billing_room_number"]').val());
       $('input[name="shipping_building"]').val($('input[name="billing_building"]').val());
       $('input[name="shipping_home_number"]').val($('input[name="billing_home_number"]').val());
       $('input[name="shipping_street"]').val($('input[name="billing_street"]').val());
       $('input[name="shipping_town"]').val($('input[name="billing_town"]').val());
       $('input[name="shipping_zip"]').val($('input[name="billing_zip"]').val());
     });
     $('.customer-copy-billing-address-dkkd').on('click', function(e) {
       e.preventDefault();
       $('select[name="dkkd_area"]').selectpicker('val', $('select[name="billing_area"]').selectpicker('val'));
       $('select[name="dkkd_country"]').selectpicker('val', $('select[name="billing_country"]').selectpicker('val'));

       $('select[name="dkkd_city"]').selectpicker('val', $('select[name="billing_city"]').selectpicker('val'));
       loadFromCity($('select[name="dkkd_city"]').selectpicker('val'), $('select[name="dkkd_city"]'), $('select[name="billing_state"]').selectpicker('val'), $('select[name="billing_ward"]').selectpicker('val'));


       $('input[name="dkkd_room_number"]').val($('input[name="billing_room_number"]').val());
       $('input[name="dkkd_building"]').val($('input[name="billing_building"]').val());
       $('input[name="dkkd_home_number"]').val($('input[name="billing_home_number"]').val());
       $('input[name="dkkd_street"]').val($('input[name="billing_street"]').val());
       $('input[name="dkkd_town"]').val($('input[name="billing_town"]').val());
       $('input[name="dkkd_zip"]').val($('input[name="billing_zip"]').val());
     });

   });


</script>
<?php if(isset($client)){ ?>
<script></script>
<?php } ?>
<?php if(!empty($google_api_key) && !empty($client->latitude) && !empty($client->longitude)){ ?>
<script>
   var latitude = '<?php echo $client->latitude; ?>';
   var longitude = '<?php echo $client->longitude; ?>';
   var marker = '<?php echo $client->company; ?>';

</script>
<?php echo app_script('assets/js','map.js'); ?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&callback=initMap"></script>
<?php } ?>
<?php $this->load->view('admin/clients/client_js'); ?>
