<?php
$dimensions = $pdf->getPageDimensions();


function mb_ucfirst($string, $encoding)
{
    return mb_convert_case($string, MB_CASE_TITLE, $encoding);
}
// Tag - used in BULK pdf exporter
if ($tag != '') {
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetDrawColor(245, 245, 245);
    $pdf->SetXY(0, 0);
    $pdf->SetFont($font_name, 'B', 15);
    $pdf->SetTextColor(0);
    $pdf->SetLineWidth(0.75);
    $pdf->StartTransform();
    $pdf->Rotate(-35, 109, 235);
    $pdf->Cell(100, 1, mb_strtoupper($tag, 'UTF-8'), 'TB', 0, 'C', '1');
    $pdf->StopTransform();
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setX(10);
    $pdf->setY(10);
}

$pdf_text_color_array = hex2rgb(get_option('pdf_text_color'));
if (is_array($pdf_text_color_array) && count($pdf_text_color_array) == 3) {
    $pdf->SetTextColor($pdf_text_color_array[0], $pdf_text_color_array[1], $pdf_text_color_array[2]);
}

$info_right_column = '';
$info_left_column  = '';
// if (get_option('show_status_on_pdf_ei') == 1) {
//     $status_name = format_purchase_status($status, '', false);
//     if ($status == 0) {
//         $bg_status = '252, 45, 66';
//     } else if ($status == 1) {
//         $bg_status = '0, 191, 54';
//     } else if ($status == 2) {
//         $bg_status = '255, 111, 0';
//     } else if ($status == 3) {
//         $bg_status = '255, 111, 0';
//     } else if ($status == 4 || $status == 6) {
//         $bg_status = '114, 123, 144';
//     }

//     $info_right_column .= '
//     <table style="text-align:center;border-spacing:3px 3px;padding:3px 4px 3px 4px;">
//     <tbody>
//         <tr>
//             <td></td>
//             <td></td>
//             <td style="color:#fff;">' . '' . '</td>
//         </tr>
//     </tbody>
//     </table>';
// }
// $info_right_column=$info_right_column .= '<a href="' . admin_url('#') . '" style="color:#4e4e4e;text-decoration:none;"><b> ' . date('Y-m-d H:i:s') . '</b></a>';
$info_right_column=get_option('invoice_company_city').', '._l('date_',date('d'))._l('month_',date('m'))._l('date_',date('Y'));

$invoice_info='';
$invoice_info = '<b>' . get_option('invoice_company_name') . '</b><br />';
$invoice_info .= _l('address').': '.get_option('invoice_company_address') . '<br/>';
// if (get_option('invoice_company_city') != '') {
//     $invoice_info .= get_option('invoice_company_city') . ', ';
// }
if(get_option('company_vat') != ''){
    $invoice_info .= _l('vat_no').': '.get_option('company_vat').'<br/>';
}
$invoice_info .= get_option('invoice_company_country_code') . ' ';
$invoice_info .= get_option('invoice_company_postal_code') . ' ';

if (get_option('invoice_company_phonenumber') != '') {
    $invoice_info .= _l('Tel').': '.get_option('invoice_company_phonenumber').'  ';
}
if (get_option('invoice_company_faxnumber') != '') {
    $invoice_info .= _l('Fax').': '.get_option('invoice_company_faxnumber').'  ';
}
if (get_option('main_domain') != '') {
    $invoice_info .= _l('Website').': '.get_option('main_domain');
}

// if ($status != 2 && $status != 5 && get_option('show_pay_link_to_invoice_pdf') == 1) {
//     $info_right_column .= '<a style="color:#84c529;text-decoration:none;" href="' . site_url('viewinvoice/' . $invoice->id . '/' . $invoice->hash) . '">' . _l('view_invoice_pdf_link_pay') . '</a><br />';
// }
// $info_right_column .= '<span style="font-weight:bold;font-size:20px;">' . _l('Kế hoạch mua') . '</span><br />';
// $info_right_column .= '<a href="' . site_url('viewinvoice/' . $invoice->id . '/' . $invoice->hash) . '" style="color:#4e4e4e;text-decoration:none;"><b># ' . $invoice_number . '</b></a>';

// write the first column
$info_left_column .= pdf_logo_url();
// var_dump($info_left_column);die();
// var_dump(FCPATH.'uploads/client_profile_images/55/Penguins.jpg');die();
// var_dump('<img width="' . 100 . 'px" src="' . FCPATH.'uploads/client_profile_images/55/Penguins.jpg' . '">');die();
// var_dump();
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['lm'], 0, $info_left_column, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);
// write the second column
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['rm'], 0, $info_right_column, 0, 'R', 0, 1, '', '', true, 0, true, false, 0);
// $pdf->MultiCell(0, 0, $invoice_info, 0, 'C', 0, 1, '', '', true, 0, true, false, 0);
// $y            = $pdf->getY();
$divide=_l('divider');
$pdf->ln(6);
$y            = $pdf->getY();
$pdf->writeHTMLCell('', '', '', $y, $divide, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
$pdf->ln(1);
$y            = $pdf->getY();
$pdf->writeHTMLCell((true ? ($dimensions['wk']) - ($dimensions['lm'] * 2) : ($dimensions['wk'] / 2) - $dimensions['lm']), '', '', $y, $invoice_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
$pdf->ln(20);
// Set Head
$plan_name=_l('als_quotation');

$pdf->SetFont($font_name, 'B', 20);
$pdf->Cell(0, 0, mb_strtoupper($plan_name, 'UTF-8') , 0, 1, 'C', 0, '', 0);
// $pdf->ln(10);
//Set purchase no
// var_dump($pdf->get_fonts_list());die();
// $pdf->SetFont($font_name, 'B', $font_size);
// $pdf->MultiCell(0, 0, '<b>Kế hoạch: #' . $invoice_number . '</b>' , 0, 'L', 0, 0, '', '', true, 0, true, false, 0);
// $pdf->ln(4);
//Set code
$pdf->SetFont($font_name, 'I', $font_size);
$pdf->Cell(0, 0, _l('code_no').($invoice_number) , 0, 1, 'C', 0, '', 0);
// $pdf->ln(4);
//Set date
$pdf->SetFont($font_name, 'I', $font_size);
$pdf->Cell(0, 0, _l('view_date').': '._d($invoice->date) , 0, 1, 'C', 0, '', 0);
$pdf->ln(4);




// Get Y position for the separation
// $y            = $pdf->getY();
// $invoice_info = '<b>' . get_option('invoice_company_name') . '</b><br />';

// $invoice_info .= get_option('invoice_company_address') . '<br/>';
// if (get_option('invoice_company_city') != '') {
//     $invoice_info .= get_option('invoice_company_city') . ', ';
// }
// $invoice_info .= get_option('invoice_company_country_code') . ' ';
// $invoice_info .= get_option('invoice_company_postal_code') . ' ';

// if (get_option('invoice_company_phonenumber') != '') {
//     $invoice_info .= '<br />' . get_option('invoice_company_phonenumber');
// }
// if(get_option('company_vat') != ''){
//     $invoice_info .= '<br />'.get_option('company_vat');
// }
// check for company custom fields
// $custom_company_fields = get_company_custom_fields();
// if (count($custom_company_fields) > 0) {
//     $invoice_info .= '<br />';
// }
// foreach ($custom_company_fields as $field) {
//     $invoice_info .= $field['label'] . ': ' . $field['value'] . '<br />';
// }

// $pdf->writeHTMLCell(($swap == '1' ? ($dimensions['wk']) - ($dimensions['lm'] * 2) : ($dimensions['wk'] / 2) - $dimensions['lm']), '', '', $y, $invoice_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);

//Set detail
$pdf->SetFont($font_name, '', $font_size);
$pdf->writeHTMLCell(0, '', '', '', _l('dear').'<b>'.$invoice->customer_name.'</b>', 0, 1, false, true, 'L', true);
$pdf->ln(2);

$pdf->SetFont($font_name, '', $font_size);
$pdf->writeHTMLCell(0, '', '', '', '<div style="padding-left: 100px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'._l('first_1st').'</div>', 0, 1, false, true, 'L', true);
$pdf->ln(2);

// Bill to
// $client_details = '<b>' . _l('invoice_bill_to') . '</b><br />';
// if($invoice->client->show_primary_contact == 1){
//     $pc_id = get_primary_contact_user_id($invoice->clientid);
//     if($pc_id){
//         $client_details .= get_contact_full_name($pc_id) .'<br />';
//     }
// }
// $client_details .= $invoice->client->company . '<br />';
// $client_details .= $invoice->billing_street . '<br />';
// if (!empty($invoice->billing_city)) {
//     $client_details .= $invoice->billing_city;
// }
// if (!empty($invoice->billing_state)) {
//     $client_details .= ', ' . $invoice->billing_state;
// }
// $billing_country = get_country_short_name($invoice->billing_country);
// if (!empty($billing_country)) {
//     $client_details .= '<br />' . $billing_country;
// }
// if (!empty($invoice->billing_zip)) {
//     $client_details .= ', ' . $invoice->billing_zip;
// }
// if (!empty($invoice->client->vat)) {
//     $client_details .= '<br />' . _l('invoice_vat') . ': ' . $invoice->client->vat;
// }
// check for invoice custom fields which is checked show on pdf
// $pdf_custom_fields = get_custom_fields('customers', array(
//     'show_on_pdf' => 1
// ));
// if (count($pdf_custom_fields) > 0) {
//     $client_details .= '<br />';
//     foreach ($pdf_custom_fields as $field) {
//         $value = get_custom_field_value($invoice->clientid, $field['id'], 'customers');
//         if ($value == '') {
//             continue;
//         }
//         $client_details .= $field['name'] . ': ' . $value . '<br />';
//     }
// }
// $pdf->writeHTMLCell(($dimensions['wk'] / 2) - $dimensions['rm'], '', '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);
// $pdf->Ln(5);
// ship to to
// if ($invoice->include_shipping == 1 && $invoice->show_shipping_on_invoice == 1) {
//     $pdf->Ln(5);
//     $shipping_details = '<b>' . _l('ship_to') . '</b><br />';
//     $shipping_details .= $invoice->shipping_street . '<br />' . $invoice->shipping_city . ', ' . $invoice->shipping_state . '<br />' . get_country_short_name($invoice->shipping_country) . ', ' . $invoice->shipping_zip;
//     $pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $shipping_details, 0, 1, false, true, ($swap == '1' ? 'L' : 'R'), true);
//     $pdf->Ln(5);
// }
// Dates
// $pdf->Cell(0, 0, _l('invoice_data_date') . ' ' . _d($invoice->date), 0, 1, ($swap == '1' ? 'L' : 'R'), 0, '', 0);
// if (!empty($invoice->duedate)) {
//     $pdf->Cell(0, 0, _l('invoice_data_duedate') . ' ' . _d($invoice->duedate), 0, 1, ($swap == '1' ? 'L' : 'R'), 0, '', 0);
// }
// if ($invoice->sale_agent != 0) {
//     if (get_option('show_sale_agent_on_invoices') == 1) {
//         $pdf->Cell(0, 0, _l('sale_agent_string') . ': ' . get_staff_full_name($invoice->sale_agent), 0, 1, ($swap == '1' ? 'L' : 'R'), 0, '', 0);
//     }
// }
// check for invoice custom fields which is checked show on pdf
// $pdf_custom_fields = get_custom_fields('invoice', array(
//     'show_on_pdf' => 1
// ));
// foreach ($pdf_custom_fields as $field) {
//     $value = get_custom_field_value($invoice->id, $field['id'], 'invoice');
//     if ($value == '') {
//         continue;
//     }
//     $pdf->writeHTMLCell(0, '', '', '', $field['name'] . ': ' . $value, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);
// }
// The Table
$pdf->Ln(5);
$tblhtml = '
<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="1px">
    <tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">
        <th scope="col"  width="5%" align="center" valign="middle">STT</th>
        <th scope="col"  width="15%" align="center" valign="middle">' . _l('Sản phẩm') . '</th>
        <th scope="col"  width="8%" align="center" valign="middle">' . _l('Mã số') . '</th>
        <th scope="col"  width="15%" align="center" valign="middle">' . _l('Chức năng/Đặc tính/Quy cách') . '</th>
        <th scope="col"  width="8%" align="center" valign="middle">' . _l('Hình ảnh') . '</th>
        <th scope="col"  width="10%" align="center" valign="middle">' . _l('Xuất xứ') . '</th>
        <th scope="col" width="8%" align="center" valign="middle">' . _l('Bảo hành').'('.get_option('default_warranty_time').')' . '</th>
        <th scope="col" width="8%" align="center" valign="middle">' . _l('Số lượng').'</th>
        <th scope="col" width="5%" align="center" valign="middle">' . _l('Đơn vị tính'). '</th>
        <th scope="col" width="8%" align="center" valign="middle">' . _l('Đơn giá').'('.get_option('default_currency').')' . '</th>
        <th scope="col" width="10%" align="center" valign="middle">' . _l('Thành tiền').'('.get_option('default_currency').')' . '</th>';
$tblhtml .= '</tr>';
// Items


$tblhtml .= '<tbody>';
$grand_total=0;
for ($i=0; $i < count($invoice->items) ; $i++) { 
    $grand_total+=$invoice->items[$i]->sub_total;
    $img='';
    if($invoice->items[$i]->image)
    {
        $img=FCPATH .$invoice->items[$i]->image;
    }
    $tblhtml.='<tr>';
    $tblhtml.='<td align="center">'.($i+1).'</td>';
    $tblhtml.='<td>'.$invoice->items[$i]->product_name.'</td>';
    $tblhtml.='<td align="center">'.$invoice->items[$i]->prefix.$invoice->items[$i]->code.'</td>';
    $tblhtml.='<td align="right" nowrap>'.$invoice->items[$i]->specification.'</td>';
    $tblhtml.='<td align="right" >'.'<img width="80px" src="' .$img.'" style="padding: 5px">'.'</td>';
    $tblhtml.='<td align="right">'.$invoice->items[$i]->made_in.'</td>';
    $tblhtml.='<td align="right">'.$invoice->items[$i]->warranty.'</td>';
    $tblhtml.='<td align="right">'.$invoice->items[$i]->quantity.'</td>';
    $tblhtml.='<td align="right">'.$invoice->items[$i]->unit_name.'</td>';
    $tblhtml.='<td align="right">'.format_money($invoice->items[$i]->unit_cost).'</td>';
    $tblhtml.='<td align="right">'.format_money($invoice->items[$i]->sub_total).'</td>';
    $tblhtml.='</tr>';
}


    $tblhtml.='<tr>';
    $tblhtml.='<td colspan="9" align="right">'._l('Số sản phẩm').'</td>';
    $tblhtml.='<td colspan="2" align="right">'._format_number(count($invoice->items)).'</td>';
    $tblhtml.='</tr>';
    $tblhtml.='<tr>';
    $tblhtml.='<td colspan="9" align="right">'._l('Tổng tiền').'</td>';
    $tblhtml.='<td colspan="2" align="right">'.format_money($grand_total,get_option('default_currency')).'</td>';
    $tblhtml.='</tr>';
$tblhtml .= '</tbody>';
$tblhtml .= '</table>';
$pdf->writeHTML($tblhtml, true, false, false, false, '');

// $pdf->writeHTML(_l('blank_date'), true, false, false, false, 'R');

// $strmoney='<div class="col-md-12"><ul>';
// $strmoney.='<li>'._l('str_money').'</li>';
// $strmoney.='<li>'._l('certificate_root').'</li>';;
// $strmoney.='</ul></div>';
// $pdf->writeHTMLCell(0, '', '', '', $strmoney, 0, 1, false, true, 'L', true);
// var_dump('<b>'.mb_strtoupper(_l('sumary_note').': </b>', 'UTF-8'));die();

if (get_option('total_to_words_enabled') == 1) {
    // Set the font again to normal like the rest of the pdf
    $pdf->SetFont($font_name, '', $font_size);
    $strmoney='<div class="col-md-12"><ul>';
    $strmoney.='<li>'._l('str_money').'<i>'.$CI->numberword->convert($grand_total, get_option('default_currency')).'</i>'.'</li>';
    $strmoney.='<li>'._l('certificate_root')._l('blank___').'</li>';
    $strmoney.='</ul></div>';
    $pdf->writeHTMLCell(0, '', '', '', $strmoney, 0, 1, false, true, 'L', true);
}


$pdf->SetFont($font_name, '', $font_size);
$pdf->writeHTMLCell(0, '', '', '', '<b>'.mb_strtoupper(_l('sumary_note').': </b>', 'UTF-8').(($invoice->reason)? $invoice->reason : _l('sumary_detail_html')), 0, 1, false, true, 'L', true);
$pdf->ln(5);

// $pdf->SetFont($font_name, '', $font_size);
// $pdf->writeHTMLCell(0, '', '', '', '<b>'.mb_strtoupper(_l('(*)').': </b>', 'UTF-8').clear_textarea_breaks(_l('sumary_detail')), 0, 1, false, true, 'L', true);
// $pdf->ln(5);


// $pdf->SetFont($font_name, '', $font_size);
// $pdf->writeHTMLCell(0, '', '', '', '<b>'.mb_strtoupper(_l('(*)').': </b>', 'UTF-8').(_l('sumary_detail_html')), 0, 1, false, true, 'L', true);
// $pdf->ln(5);

$table = "<table style=\"width: 100%;text-align: center\" border=\"0\">
        <tr>
            <td>" . mb_ucfirst('', "UTF-8") . "</td>
            <td><b>" . mb_strtoupper(get_option('invoice_company_name'), "UTF-8") . "</b></td>
        </tr>
        <tr>
            <td>" . mb_ucfirst('', "UTF-8") . "</td>
            <td><i>" . mb_ucfirst(_l('sale_department'), "UTF-8") . "</i></td>
        </tr>
        
</table>";
$pdf->writeHTML($table, true, false, false, false, '');

// $pdf->Ln(8);
// $tbltotal = '';
// $tbltotal .= '<table cellpadding="6">';
// $tbltotal .= '
// <tr>
//     <td align="right" width="80%">' . _l('Sản phẩm') . '</td>
//     <td align="right" width="20%">' . count($invoice->items) . '</td>
// </tr>';
//     $tbltotal .= '
//     <tr>
//         <td align="right" width="80%">' . _l('Tổng tiền') . '</td>
//         <td align="right" width="20%">' . format_money($grand_total) . '</td>
//     </tr>';
// foreach ($taxes as $tax) {
//     $total = array_sum($tax['total']);
//     if ($invoice->discount_percent != 0 && $invoice->discount_type == 'before_tax') {
//         $total_tax_calculated = ($total * $invoice->discount_percent) / 100;
//         $total                = ($total - $total_tax_calculated);
//     }
//     // The tax is in format TAXNAME|20
//     $_tax_name = explode('|', $tax['tax_name']);
//     $tbltotal .= '<tr>
//     <td align="right" width="80%">' . $_tax_name[0] . '(' . _format_number($tax['taxrate']) . '%)' . '</td>
//     <td align="right" width="20%">' . format_money($total, $invoice->symbol) . '</td>
// </tr>';
// }
// if ($invoice->adjustment != '0.00') {
//     $tbltotal .= '<tr>
//     <td align="right" width="80%">' . _l('invoice_adjustment') . '</td>
//     <td align="right" width="20%">' . format_money($invoice->adjustment, $invoice->symbol) . '</td>
// </tr>';
// }
// $tbltotal .= '
// <tr style="background-color:#f0f0f0;">
//     <td align="right" width="80%">' . _l('invoice_total') . '</td>
//     <td align="right" width="20%">' . format_money($invoice->total, $invoice->symbol) . '</td>
// </tr>';

// if ($invoice->status == 3) {
//     $tbltotal .= '
//     <tr>
//         <td align="right" width="80%">' . _l('invoice_total_paid') . '</td>
//         <td align="right" width="20%">' . format_money(sum_from_table('tblinvoicepaymentrecords', array(
//         'field' => 'amount',
//         'where' => array(
//             'invoiceid' => $invoice->id
//         )
//     )), $invoice->symbol) . '</td>
//     </tr>
//     <tr style="background-color:#f0f0f0;">
//        <td align="right" width="80%">' . _l('invoice_amount_due') . '</td>
//        <td align="right" width="20%">' . format_money(get_invoice_total_left_to_pay($invoice->id, $invoice->total), $invoice->symbol) . '</td>
//    </tr>';
// }
// $tbltotal .= '</table>';
// $pdf->writeHTML($tbltotal, true, false, false, false, '');

// if (get_option('total_to_words_enabled') == 1) {
//     // Set the font bold
//     $pdf->SetFont($font_name, 'B', $font_size);
//     $pdf->Cell(0, 0, _l('num_word') . ': ' . $CI->numberword->convert($invoice->total, $invoice->currency_name), 0, 1, 'C', 0, '', 0);
//     // Set the font again to normal like the rest of the pdf
//     $pdf->SetFont($font_name, '', $font_size);
//     $pdf->Ln(4);
// }

// if (count($invoice->payments) > 0 && get_option('show_transactions_on_invoice_pdf') == 1) {
//     $pdf->Ln(4);
//     $border = 'border-bottom-color:#000000;border-bottom-width:1px;border-bottom-style:solid; 1px solid black;';
//     $pdf->SetFont($font_name, 'B', $font_size);
//     $pdf->Cell(0, 0, _l('invoice_received_payments'), 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', $font_size);
//     $pdf->Ln(4);
//     $tblhtml = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="0">
//         <tr height="20"  style="color:#000;border:1px solid #000;">
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_number_heading') . '</th>
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_mode_heading') . '</th>
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_date_heading') . '</th>
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_amount_heading') . '</th>
//     </tr>';
//     $tblhtml .= '<tbody>';
//     foreach ($invoice->payments as $payment) {
//         $payment_name = $payment['name'];
//         if (!empty($payment['paymentmethod'])) {
//             $payment_name .= ' - ' . $payment['paymentmethod'];
//         }
//         $tblhtml .= '
//             <tr>
//             <td>' . $payment['paymentid'] . '</td>
//             <td>' . $payment_name . '</td>
//             <td>' . _d($payment['date']) . '</td>
//             <td>' . format_money($payment['amount'], $invoice->symbol) . '</td>
//             </tr>
//         ';
//     }
//     $tblhtml .= '</tbody>';
//     $tblhtml .= '</table>';
//     $pdf->writeHTML($tblhtml, true, false, false, false, '');
// }

// if (found_invoice_mode($payment_modes, $invoice->id, true, true)) {
//     $pdf->Ln(4);
//     $pdf->SetFont($font_name, 'B', 10);
//     $pdf->Cell(0, 0, _l('invoice_html_offline_payment'), 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', 10);
//     foreach ($payment_modes as $mode) {
//         if (is_numeric($mode['id'])) {
//             if (!is_payment_mode_allowed_for_invoice($mode['id'], $invoice->id)) {
//                 continue;
//             }
//         }
//         if (isset($mode['show_on_pdf']) && $mode['show_on_pdf'] == 1) {
//             $pdf->Ln(2);
//             $pdf->Cell(0, 0, $mode['name'], 0, 1, 'L', 0, '', 0);
//             $pdf->MultiCell($dimensions['wk'] - ($dimensions['lm'] + $dimensions['rm']), 0, clear_textarea_breaks($mode['description']), 0, 'L');
//         }
//     }
// }

// if (!empty($invoice->clientnote)) {
//     $pdf->Ln(4);
//     $pdf->SetFont($font_name, 'B', 10);
//     $pdf->Cell(0, 0, _l('invoice_note'), 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', 10);
//     $pdf->Ln(2);
//     $pdf->MultiCell(0, 0, clear_textarea_breaks($invoice->clientnote), 0, 'L');
// }

// if (!empty($invoice->terms)) 
// {
//     $pdf->Ln(4);
//     $pdf->SetFont($font_name, 'B', 10);
//     $pdf->Cell(0, 0, _l('terms_and_conditions'), 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', 10);
//     $pdf->Ln(2);
//     $pdf->MultiCell(0, 0, clear_textarea_breaks($invoice->terms), 0, 'L');
// }

