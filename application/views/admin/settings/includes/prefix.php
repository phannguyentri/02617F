    <div role="tabpanel" class="tab-pane" id="company_info">
        <p class="text-muted">
            <?php echo _l('Thông tin tiếp đầu ngữ chung(Hóa đơn, Nhân viên, Kho, Khách hàng,...)'); ?>
        </p>
        <?php echo render_input('settings[prefix_staff]','Nhân Viên',get_option('prefix_staff')); ?>
        <?php echo render_input('settings[prefix_customer]','Khách Hàng',get_option('prefix_customer')); ?>
        <?php echo render_input('settings[prefix_purchase_plan]','Kế hoạch mua',get_option('prefix_purchase_plan')); ?>
        <?php echo render_input('settings[prefix_purchase_suggested]','Đề xuất mua',get_option('prefix_purchase_suggested')); ?>
        <?php echo render_input('settings[prefix_contract]','Hợp đồng mua',get_option('prefix_contract')); ?>
        <?php echo render_input('settings[prefix_purchase_order]','Đơn hàng mua',get_option('prefix_purchase_order')); ?>
        <?php echo render_input('settings[prefix_product]','Sản phẩm',get_option('prefix_product')); ?>
        
        <hr />
    </div>
