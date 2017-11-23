<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
      <div class="row">
          <div class="col-md-12">
              <div class="panel_s">
                  <div class="panel-body _buttons">
                      <div class="row">
                        <div class="col-md-12">
                          <h4>DANH SÁCH SMS ĐÃ GỬI</h4>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-md-12">
              <div class="panel_s">
                  <div class="panel-body _buttons">
                      <div class="row">
                        <div class="col-md-12">
                          <?php
                            $table_data = array(
                              _l('Chủ đề'),
                              _l('SĐT người nhận'),
                              _l('Template SMS'),
                              _l('Nội dung'),
                              _l('Thuộc tính')
                            );

                            render_datatable($table_data, 'sms');

                          ?>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>

<?php init_tail(); ?>

<script type="text/javascript">
  initDataTable('.table-sms', window.location.href, [1], [1]);

</script>
