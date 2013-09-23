<?php
$dbname = "master_tw";
$dbtable = "master_dt";

include('con.php');

$all = "SELECT *  FROM `".$dbtable."`";
$cricketNation = "SELECT * FROM `".$dbtable."` WHERE  `report_title` LIKE  'Cricketnation'"

$export = mysql_query($cricketNation) or die("Sql error : " . mysql_error());
$fields = mysql_num_fields($export);

for ($i = 0; $i < $fields; $i++) {
    $header .= mysql_field_name($export, $i) . "\t";
}

while ($row = mysql_fetch_row($export)) {
    $line = '';
    foreach ($row as $value) {
        if ((!isset($value) ) || ( $value == "" )) {
            $value = "\t";
        } else {
            $value = str_replace('"', '""', $value);
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $data .= trim($line) . "\n";
}
$data = str_replace("\r", "", $data);

if ($data == "") {
    $data = "\n(0) Records Found!\n";
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=all_entries.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
