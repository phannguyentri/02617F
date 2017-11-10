  <div id="tasks-report" class="hide">
      <div class="col-xs-12" style="padding: 0px; ">
      <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="padding-left: 0px;">
          <?php 
              echo render_select('clientid2', $clients_iv, array('userid', 'company'), 'Khách hàng');
          ?>
      </div>
      
      <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
         <?php 
             echo render_select('staffsid2[]', $user_iv, array('staffid', 'fullname'), 'Nhân viên kinh doanh','',array('multiple'=>true),array(),'','',false);
          ?>
      </div> 

      <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
         <?php 
            $status = array(
                            array(
                                'id' => '',
                                'name' => '',
                            ),
                            array(
                                'id' => '1',
                                'name' => 'Chưa bắt đầu',
                            ),
                            array(
                                'id' => '4',
                                'name' => 'Trong tiến trình',
                            ),
                            array(
                                'id' => '5',
                                'name' => 'Hoàn thành',
                            ),                            
              );
             echo render_select('status2', $status, array('id', 'name'), 'Trạng thái','',array(),array(),'','',false);
          ?>
      </div>     
   </div>   

       <table class="table table-striped  table-tasks-report">
         <thead>
            <tr>
               <th ><?php echo _l('Tên giao dịch'); ?></th>
               <th ><?php echo _l('Ngày bắt đầu'); ?></th>
               <th ><?php echo _l('Trạng thái'); ?></th>               
               <th ><?php echo _l('Người tạo'); ?></th>    
            </tr>            
         </thead>
         <tbody></tbody>
         
      </table>
   </div>
