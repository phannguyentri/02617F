<?php include_once(APPPATH.'views/admin/includes/helpers_bottom.php'); ?>
<?php do_action('before_js_scripts_render'); ?>
<script src="<?php echo base_url('assets/plugins/app-build/jquery-with-ui.min.js'); ?>"></script>
<?php if(ENVIRONMENT !== 'production' || isset($jquery_migrate_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery-migrate.js'); ?>"></script>
<?php } ?>
<script>
    $(window).on('load',function(){
        init_btn_with_tooltips();
    });
</script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/datatables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/app-build/metis-tagit-areyousure-bootstrapcolorpicker-dropzone-datetimepicker.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/app-build/moment.min.js'); ?>"></script>
<?php app_select_plugin_js($locale); ?>
<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js'); ?>"></script>
<?php app_jquery_validation_plugin_js($locale); ?>
<?php if(isset($chart_js_assets)){ ?>
<script id="chart-js-script" src="<?php echo base_url('assets/plugins/Chart.js/Chart.min.js'); ?>" type="text/javascript"></script>
<?php } ?>
<?php if(get_option('dropbox_app_key') != ''){ ?>
<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="<?php echo get_option('dropbox_app_key'); ?>"></script>
<?php } ?>
<?php if(isset($lightbox_assets)){ ?>
<script id="lightbox-js" src="<?php echo base_url('assets/plugins/lightbox/js/lightbox.min.js'); ?>"></script>
<?php } ?>
<?php if(isset($form_builder_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/form-builder/form-builder.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/form-builder/form-render.min.js'); ?>"></script>
<?php } ?>
<?php if(isset($media_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/elFinder/js/elfinder.min.js'); ?>"></script>
<?php if(file_exists(FCPATH.'assets/plugins/elFinder/js/i18n/elfinder.'.$locale.'.js')){ ?>
<script src="<?php echo base_url('assets/plugins/elFinder/js/i18n/elfinder.'.$locale.'.js'); ?>"></script>
<?php } ?>
<?php } ?>
<?php if(isset($projects_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/jquery-comments/js/jquery-comments.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/gantt/js/jquery.fn.gantt.min.js'); ?>"></script>
<?php } ?>
<?php if(isset($circle_progress_asset)){ ?>
<script src="<?php echo base_url('assets/plugins/jquery-circle-progress/circle-progress.min.js'); ?>"></script>
<?php } ?>
<?php if(isset($accounting_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/accounting.js/accounting.min.js'); ?>"></script>
<?php } ?>
<?php if(isset($calendar_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/fullcalendar/fullcalendar.min.js'); ?>"></script>
<?php if(get_option('google_api_key') != ''){ ?>
<script src="<?php echo base_url('assets/plugins/fullcalendar/gcal.min.js'); ?>"></script>
<?php } ?>
<?php if(file_exists(FCPATH.'assets/plugins/fullcalendar/locale/'.$locale.'.js')){ ?>
<script src="<?php echo base_url('assets/plugins/fullcalendar/locale/'.$locale.'.js'); ?>"></script>
<?php } ?>
<?php echo app_script('assets/js','calendar.js'); ?>
<?php } ?>
<?php echo app_script('assets/js','main.js'); ?>
<?php echo get_custom_fields_hyperlink_js_function(); ?>
<?php do_action('after_js_scripts_render'); ?>
<?php
$alertclass = "";
if($this->session->flashdata('message-success')){
    $alertclass = "success";
} else if ($this->session->flashdata('message-warning')){
    $alertclass = "warning";
} else if ($this->session->flashdata('message-info')){
    $alertclass = "info";
} else if ($this->session->flashdata('message-danger')){
    $alertclass = "danger";
}
if($alertclass != ''){
    $alert_message = '';
    $alert = $this->session->flashdata('message-'.$alertclass);
    if(is_array($alert)){
        foreach($alert as $alert_data){
            $alert_message.= '<span>'.$alert_data . '</span><br />';
        }
    } else {
        $alert_message .= $alert;
    }
    ?>
    <script>
        $(function(){
            alert_float('<?php echo $alertclass; ?>','<?php echo $alert_message; ?>');
        });
    </script>
    <?php } ?>
     <script>
    $(function() {
        $('body.hide-sidebar').find('ul').removeClass('in');
        $('.hide-sidebar #side-menu').find('li').removeClass('active');
    })
</script>

<script type="text/javascript">
function initDataTableFixedHeader(table, url, notsearchable, notsortable, fnserverparams, defaultorder, fixedColumns) {
    // alert(table);
    var _table_name = table;
    if ($(table).length == 0) {
        return;
    }
    if (fnserverparams == 'undefined' || typeof(fnserverparams) == 'undefined') {
        fnserverparams = []
    }

    // If not order is passed order by the first column
    if (typeof(defaultorder) == 'undefined') {
        defaultorder = [
            [0, 'ASC']
        ];
    } else {
        if (defaultorder.length == 1) {
            defaultorder = [defaultorder]
        }
    }

    var length_options = [10, 25, 50, 100];
    var length_options_names = [10, 25, 50, 100];

    tables_pagination_limit = parseFloat(tables_pagination_limit);

    if ($.inArray(tables_pagination_limit, length_options) == -1) {
        length_options.push(tables_pagination_limit)
        length_options_names.push(tables_pagination_limit)
    }

    length_options.sort(function(a, b) {
        return a - b;
    });
    length_options_names.sort(function(a, b) {
        return a - b;
    });

    length_options.push(-1);
    length_options_names.push(dt_length_menu_all);

    var table = $('body').find(table).dataTable({
        fixedColumns: fixedColumns || false,
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "bScrollCollapse": true,
        scrollY:        '80vh',
        scrollCollapse: true,
        "language": dt_lang,
        "processing": true,
        "retrieve": true,
        "serverSide": true,
        'paginate': true,
        'searchDelay': 700,
        "bDeferRender": true,
        "responsive": false,
        "autoWidth": false,
        dom: "<'mbot25'B><'row'><'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-4'i>><'row'<'#colvis'>p>",
        "pageLength": tables_pagination_limit,
        "lengthMenu": [length_options, length_options_names],
        "columnDefs": [{
            "searchable": false,
            "targets": notsearchable,
        }, {
            "sortable": false,
            "targets": notsortable
        }],
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
            // If tooltips found
            $(nRow).attr('data-title', aData.Data_Title)
            $(nRow).attr('data-toggle', aData.Data_Toggle)
        },
        "initComplete": function(settings, json) {
            var _table = $(table);
            var th_last_child = _table.find('thead th:last-child');
            var th_first_child = _table.find('thead th:first-child');
            if (th_last_child.text().trim() == options_string_translate) {
                th_last_child.addClass('not-export');
            }
            if (th_first_child.find('input[type="checkbox"]').length > 0) {
                th_first_child.addClass('not-export');
            }
        },
        "order": defaultorder,
        "ajax": {
            "url": url,
            "type": "POST",
            "data": function(d) {
                for (var key in fnserverparams) {
                    d[key] = $(fnserverparams[key]).val();
                }
            }
        },
        buttons: get_dt_export_buttons(table),
    });

    var tableApi = table.DataTable();
    var hiddenHeadings = $(table).find('th.not_visible');
    $.each(hiddenHeadings, function() {
        tableApi.columns(this.cellIndex).visible(false, false);
    });
    // Fix for hidden tables colspan not correct if the table is empty
    if ($(_table_name).is(':hidden')) {
        $(_table_name).find('.dataTables_empty').attr('colspan', $(_table_name).find('thead th').length);
    }

    return tableApi;
}

</script>
