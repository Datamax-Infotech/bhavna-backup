<?php 
$inv_file_for_ocr = "https://www.ucbdata.com/ucbloop/AZFormR/formrecognizer/ai-form-recognizer/upload/testinv.pdf";									
$mainstr = shell_exec("/usr/bin/node node_test.js");
var_dump($mainstr);