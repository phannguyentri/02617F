<?php
// Theese lines should aways at the end of the document left side. Dont indent these lines
if(isset($contract->content))
$html = <<<EOF
<div style="width:680px !important;">
$contract->content
</div>
EOF;
else {
$html = <<<EOF
    <div style="width:680px !important;">
    $contract->template
    </div>
EOF;
}

// var_dump($html);
// exit();

$pdf->writeHTML($html, true, false, true, false, '');
