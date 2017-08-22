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
$info_right_column .= '<a href="' . admin_url('#') . '" style="color:#4e4e4e;text-decoration:none;"><b> ' . date('Y-m-d H:i:s') . '</b></a>';

$invoice_info='';
$invoice_info = '<b>' . get_option('invoice_company_name') . '</b><br />';
$invoice_info .= _l('address').': '.get_option('invoice_company_address') . '<br/>';
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

// write the first column
$info_left_column .= pdf_logo_url();
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['lm'], 0, $info_left_column, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);
// write the second column
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['rm'], 0, $info_right_column, 0, 'R', 0, 1, '', '', true, 0, true, false, 0);
$divide=_l('divider');
$pdf->ln(6);
$y            = $pdf->getY();
$pdf->writeHTMLCell('', '', '', $y, $divide, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
$pdf->ln(2);
$y            = $pdf->getY();

$pdf->writeHTMLCell((true ? ($dimensions['wk']) - ($dimensions['lm'] * 2) : ($dimensions['wk'] / 2) - $dimensions['lm']), '', '', $y, $invoice_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
$pdf->ln(20);
// Set Head
$plan_name=_l('deliveries');

$pdf->SetFont($font_name, 'B', 20);
$pdf->Cell(0, 0, mb_strtoupper($plan_name, 'UTF-8') , 0, 1, 'C', 0, '', 0);
//Set code
$pdf->SetFont($font_name, 'I', $font_size);
$pdf->Cell(0, 0, _l('code_no').($invoice_number) , 0, 1, 'C', 0, '', 0);
//Set date
$pdf->SetFont($font_name, 'I', $font_size);
$pdf->Cell(0, 0, _l('view_date').': '._d($invoice->delivery_date) , 0, 1, 'C', 0, '', 0);
$pdf->ln(4);



//Set detail
$pdf->SetFont($font_name, '', $font_size);
$pdf->Cell(0, 0, _l('client').': '.$invoice->customer_name , 0, 1, 'L', 0, '', 0);
$pdf->ln(2);
$address=getClient($invoice->customer_id,1);
$fone_fax_email='';
$fone_fax_email.=$customer->phonenumber._l('more_blank')._l('Fax: ').$customer->fax._l('more_blank')._l('Email: ').$contact->email;
$shipping_address=getClient($invoice->customer_id,2);
$pdf->SetFont($font_name, '', $font_size);
$pdf->Cell(0, 0, _l('address').': '.$address , 0, 1, 'L', 0, '', 0);
$pdf->ln(2);
// var_dump($customer);die();
$pdf->SetFont($font_name, '', $font_size);
$pdf->writeHTMLCell(0, '', '', '', _l('clients_phone').': '.$fone_fax_email , 0, 1, false, true, 'L', true);
$pdf->ln(2);

$pdf->SetFont($font_name, '', $font_size);
$pdf->writeHTMLCell(0, '', '', '', _l('shipping_address').': '.$shipping_address , 0, 1, false, true, 'L', true);
$pdf->ln(2);


// The Table
$pdf->Ln(5);
$tblhtml = '
<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="1px">
    <tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">
        <th scope="col"  width="5%" align="center">STT</th>
        <th scope="col"  width="20%" align="center">' . _l('Sản phẩm') . '</th>
        <th scope="col"  width="17%" align="center">' . _l('Mã số') . '</th>
        <th scope="col"  width="18%" align="center">' . _l('Số lượng') . '</th>';
$tblhtml .='<th  width="20%" align="center">' . _l('Đơn giá') . '</th>
            <th  width="20%" align="center">' . _l('Thành tiền') . '</th>';
$tblhtml .= '</tr>';
// Items
$tblhtml .= '<tbody>';
$grand_total=0;
for ($i=0; $i < count($invoice->items) ; $i++) { 
    // var_dump($invoice->items[$i]);die();
    $grand_total+=$invoice->items[$i]->sub_total;
    $tblhtml.='<tr>';
    $tblhtml.='<td align="center">'.($i+1).'</td>';
    $tblhtml.='<td>'.$invoice->items[$i]->product_name.'</td>';
    $tblhtml.='<td>'.$invoice->items[$i]->prefix.$invoice->items[$i]->code.'</td>';
    $tblhtml.='<td align="right">'._format_number($invoice->items[$i]->quantity).'</td>';
    $tblhtml.='<td align="right">'.format_money($invoice->items[$i]->unit_cost).'</td>';
    $tblhtml.='<td align="right">'.format_money($invoice->items[$i]->sub_total).'</td>';
    $tblhtml.='</tr>';
}


    $tblhtml.='<tr>';
    $tblhtml.='<td colspan="4" align="right">Tổng tiền</td>';
    $tblhtml.='<td colspan="2" align="right">'.format_money($grand_total,get_option('default_currency')).'</td>';
    $tblhtml.='</tr>';
$tblhtml .= '</tbody>';
$tblhtml .= '</table>';
$pdf->writeHTML($tblhtml, true, false, false, false, '');
$strmoney='<ul>';
$strmoney.='<li>'._l('str_money').$CI->numberword->convert($grand_total,get_option('default_currency')).'</li>';
$strmoney.='<li>'._l('certificate_root').($invoice->certificate_root?$invoice->certificate_root:_l('blank___')).'</li>';;
$strmoney.='</ul>';
// $pdf->writeHTMLCell(0, '', '', '', $strmoney, 0, 1, false, true, 'L', true);
$pdf->writeHTML($strmoney, true, false, false, false, 'L');
$pdf->Ln(5);
$pdf->writeHTML('<b>'._l('note').'</b>'.':'._l('check_note'), true, false, false, false, 'L');
$pdf->Ln(5);
$table = "<table style=\"width: 100%;text-align: center\" border=\"0\">
        <tr>
            <td><b>" . mb_ucfirst(_l('buyer'), "UTF-8") . "</b></td>
            <td><b>" . mb_ucfirst(_l('saler'), "UTF-8") . "</b></td>
            <td><b>" . mb_ucfirst(_l('warehouseman'), "UTF-8") . "</b></td>
            <td><b>" . mb_ucfirst(_l('biller'), "UTF-8") . "</b></td>
        </tr>
        <tr>
            <td>(ký, ghi rõ họ tên)</td>
            <td>(ký, ghi rõ họ tên)</td>
            <td>(ký, ghi rõ họ tên)</td>
            <td>(ký, ghi rõ họ tên)</td>
        </tr>
        <tr>
            <td style=\"height: 100px\" colspan=\"4\"></td>
        </tr>
        <tr>
            <td>" . mb_ucfirst($customer->company,"UTF-8") . "</td>
            <td>" . mb_ucfirst(get_staff_full_name($invoice->saler),"UTF-8") . "</td>
            <td>" . mb_ucfirst(get_staff_full_name($invoice->warehouseman),"UTF-8") . "</td>
            <td>" . mb_ucfirst(get_staff_full_name($invoice->biller),"UTF-8") . "</td>
        </tr>
        
</table>";
$pdf->writeHTML($table, true, false, false, false, '');


