  <div id="quotes-report" class="hide">
      <div class="col-xs-12" style="padding: 0px; ">
      <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="padding-left: 0px;">
          <?php 
              echo render_select('clientid', $clients_iv, array('userid', 'company'), 'Khách hàng');
          ?>
      </div>
      
      <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
         <?php 
             echo render_select('staffsid[]', $user_iv, array('staffid', 'fullname'), 'Nhân viên kinh doanh','',array('multiple'=>true),array(),'','',false);
          ?>
      </div>


      

     
   </div>   

       <table class="table  table-striped table-quotes-report">
         <thead>
            <tr>
               <th ><?php echo _l('Mã báo giá'); ?></th>
               <th ><?php echo _l('Khách hàng'); ?></th>
               <th ><?php echo _l('Người tạo'); ?></th>
               
               <th ><?php echo _l('Ngày tạo'); ?></th>
               <th ><?php echo _l('Tổng tiền'); ?></th>
            </tr>            
         </thead>
         <tbody></tbody>
         <tfoot>
            <tr>  
               <td colspan="4" style="text-align: right">TỔNG:</td>
               <td class="subtotalquotes"></td>      
            </tr>
         </tfoot>
      </table>
   </div>
