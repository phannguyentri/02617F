<?php init_head(); ?>

<style type="text/css">
  .span-tag{
    padding: 2px 5px 2px 5px;
    background: #fff;
    color: #e47724;
    border: 1px solid #e47724;
    line-height : 2;
    font-weight: 400;
    font-size: 13px;
    border-radius: 3px;
  }
</style>

<div id="detail-sms"></div>

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
                              '#',
                              _l('Chủ đề'),
                              _l('Người gửi'),
                              _l('SĐT người nhận'),
                              _l('Template SMS'),
                              _l('Nội dung'),
                              _l('Thời gian'),
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

  function loadDetailSmsModel(id){

  }

</script>
