
<style type="text/css">
	#customers-report thead  tr  th{
		text-align: center;
	}
</style>
   
  <div id="customers-report" class="hide">
      <div class="col-xs-12" style="padding: 0px; ">
   <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="padding-left: 0px;">
          <?php 
              echo render_select('clientid3', $clients_iv, array('userid', 'company'), 'Khách hàng');
          ?>
      </div>
      <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
         <?php 
             echo render_select('staffsid3[]', $user_iv, array('staffid', 'fullname'), 'Nhân viên kinh doanh','',array('multiple'=>true),array(),'','',false);
          ?>
      </div>
   </div>   

       <table class="table table table-striped  table-customers-report">
         <thead>
            <tr>
               <th rowspan="2"><?php echo _l('Mã khách hàng'); ?></th>
               <th rowspan="2"><?php echo _l('reports_sales_dt_customers_client'); ?></th>
               <th colspan="2"><?php echo _l('Báo giá'); ?></th>
               <th colspan="2"><?php echo _l('Hợp đồng'); ?></th>
               <th rowspan="2"><?php echo _l('Ngày tạo'); ?></th>
               <th rowspan="2"><?php echo _l('Nhân viên kinh doanh'); ?></th>
            </tr>
            <tr>
            	<th>Số lượng</th>
            	<th>Số tiền</th>
            	<th>Số lượng</th>
            	<th>Số tiền</th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <tr>  
               <td colspan="2" style="text-align: right">TỔNG:</td>

               <td class="subtotalquotes"></td>
               <td class="totalquotes"></td>
               <td class="subtotalcontracts"></td>
               <td class="totalcontracts"></td>
               <td></td>
               <td></td>
             
            </tr>
         </tfoot>
      </table>
   </div>
