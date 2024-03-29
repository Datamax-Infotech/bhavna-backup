<?php
$sales_rep_login = "no";
if (isset($_REQUEST["repchk"])) {
    if ($_REQUEST["repchk"] == "yes") {
        $sales_rep_login = "yes";
    }
} else {
    require("inc/header_session_client.php");
}

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

db();
?>
<?php
if ($_REQUEST["p"] == "g") {

    $getqry = db_query("SELECT quote_gaylord.id, quote_gaylord.quote_id FROM quote_gaylord WHERE id= '" . $_REQUEST["tableid"] . "'");
    $getrec = array_shift($getqry);
    //Delete quote request id from quote table
    db_b2b();
    $chk_quote_query1 = "SELECT * FROM quote WHERE companyID=" . $_REQUEST["companyid"];
    $chk_quote_res1 = db_query($chk_quote_query1);
    while ($quote_rows1 = array_shift($chk_quote_res1)) {
        //  echo "sdfsdf";
        $quote_req = $quote_rows1["quoteRequest"];

        if (strpos($quote_req, ',') !== false) {
            $quote_req_id = explode(",", $quote_req);
            $total_id = count($quote_req_id);


            for ($req = 0; $req < $total_id; $req++) {

                if ($quote_req_id[$req] == $getrec["quote_id"]) {
                    $n_quote_req_id = str_replace($quote_req_id[$req], "", $quote_req);
                    $new_quote_req_id = preg_replace("/,+/", ",", $n_quote_req_id);
                    $new_quote_req_id = trim($new_quote_req_id, ",");
                    //
                    //remove quote request ID and update in table
                    db_b2b();
                    $dbqry = db_query("UPDATE quote SET quoteRequest='" . $new_quote_req_id . "' WHERE id=" . $quote_rows1["ID"] . " AND companyID=" . $_REQUEST["companyid"]);
                    // echo $new_quote_req_id;
                    // echo $quote_rows1["ID"]."<br>";
                }
            }
        } else {
            // echo $getrec["quote_id"];
            if ($quote_req == $getrec["quote_id"]) {

                $delqry1 = "DELETE FROM quote WHERE id = " . $quote_rows1["ID"] . " AND companyID=" . $_REQUEST["companyid"];
                db_b2b();
                $result1 = db_query($delqry1);
            }
        } //End else 

    } //End quote while loop   
    //
    db();

    //Delete record from master table
    $delqrymaster = "DELETE FROM quote_request WHERE quote_id = '" . $getrec["quote_id"] . "'";
    $result1 = db_query($delqrymaster);
    //
    //Delete record from sub table
    $delqry = "DELETE FROM quote_gaylord WHERE id = " . $_REQUEST["tableid"] . "";
    $result = db_query($delqry);




    //
    //redirect($_SERVER['HTTP_REFERER']);
}
if ($_REQUEST["p"] == "sb") {
    //Delete record from master table
    $getqry = db_query("select quote_shipping_boxes.id, quote_shipping_boxes.quote_id from quote_shipping_boxes where id= '" . $_REQUEST["stableid"] . "'");
    $getrec = array_shift($getqry);
    //Delete quote request id from quote table
    db_b2b();
    $chk_quote_query1 = "Select * from quote where companyID=" . $_REQUEST["companyid"];
    $chk_quote_res1 = db_query($chk_quote_query1);
    while ($quote_rows1 = array_shift($chk_quote_res1)) {
        //  echo "sdfsdf";
        $quote_req = $quote_rows1["quoteRequest"];

        if (strpos($quote_req, ',') !== false) {
            $quote_req_id = explode(",", $quote_req);
            $total_id = count($quote_req_id);


            for ($req = 0; $req < $total_id; $req++) {

                if ($quote_req_id[$req] == $getrec["quote_id"]) {
                    $n_quote_req_id = str_replace($quote_req_id[$req], "", $quote_req);
                    $new_quote_req_id = preg_replace("/,+/", ",", $n_quote_req_id);
                    $new_quote_req_id = trim($new_quote_req_id, ",");
                    //
                    //remove quote request ID and update in table
                    db_b2b();
                    $dbqry = db_query("update quote set quoteRequest='" . $new_quote_req_id . "' where id=" . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"]);
                    // echo $new_quote_req_id;
                    // echo $quote_rows1["ID"]."<br>";
                }
            }
        } else {
            // echo $getrec["quote_id"];
            if ($quote_req == $getrec["quote_id"]) {

                $delqry1 = "delete from quote where id = " . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"];
                db_b2b();
                $result1 = db_query($delqry1);
            }
        } //End else 

    } //End quote while loop   
    //
    db();

    $delqrymaster = "delete from quote_request where quote_id = '" . $getrec["quote_id"] . "'";
    $result1 = db_query($delqrymaster);
    //
    //Delete record from sub table
    $delqry = "delete from quote_shipping_boxes where id = " . $_REQUEST["stableid"] . "";
    $result = db_query($delqry);
}
if ($_REQUEST["p"] == "sup") {
    //Delete record from master table
    $getqry = db_query("select quote_supersacks.id, quote_supersacks.quote_id from quote_supersacks where id= '" . $_REQUEST["suptableid"] . "'");
    $getrec = array_shift($getqry);
    //Delete quote request id from quote table
    db_b2b();
    $chk_quote_query1 = "Select * from quote where companyID=" . $_REQUEST["companyid"];
    $chk_quote_res1 = db_query($chk_quote_query1);
    while ($quote_rows1 = array_shift($chk_quote_res1)) {
        //  echo "sdfsdf";
        $quote_req = $quote_rows1["quoteRequest"];

        if (strpos($quote_req, ',') !== false) {
            $quote_req_id = explode(",", $quote_req);
            $total_id = count($quote_req_id);


            for ($req = 0; $req < $total_id; $req++) {

                if ($quote_req_id[$req] == $getrec["quote_id"]) {
                    $n_quote_req_id = str_replace($quote_req_id[$req], "", $quote_req);
                    $new_quote_req_id = preg_replace("/,+/", ",", $n_quote_req_id);
                    $new_quote_req_id = trim($new_quote_req_id, ",");
                    //
                    //remove quote request ID and update in table
                    db_b2b();
                    $dbqry = db_query("update quote set quoteRequest='" . $new_quote_req_id . "' where id=" . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"]);
                    // echo $new_quote_req_id;
                    // echo $quote_rows1["ID"]."<br>";
                }
            }
        } else {
            // echo $getrec["quote_id"];
            if ($quote_req == $getrec["quote_id"]) {

                $delqry1 = "delete from quote where id = " . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"];
                db_b2b();
                $result1 = db_query($delqry1);
            }
        } //End else 

    } //End quote while loop   
    //
    db();

    $delqrymaster = "delete from quote_request where quote_id = '" . $getrec["quote_id"] . "'";
    $result1 = db_query($delqrymaster);
    //
    //Delete record from sub table
    $delqry = "delete from quote_supersacks where id = " . $_REQUEST["suptableid"] . "";
    $result = db_query($delqry);
}
if ($_REQUEST["p"] == "pal") {
    //Delete record from master table
    $getqry = db_query("select quote_pallets.id, quote_pallets.quote_id from quote_pallets where id= '" . $_REQUEST["paltableid"] . "'");
    $getrec = array_shift($getqry);
    //Delete quote request id from quote table
    db_b2b();
    $chk_quote_query1 = "Select * from quote where companyID=" . $_REQUEST["companyid"];
    $chk_quote_res1 = db_query($chk_quote_query1);
    while ($quote_rows1 = array_shift($chk_quote_res1)) {
        //  echo "sdfsdf";
        $quote_req = $quote_rows1["quoteRequest"];

        if (strpos($quote_req, ',') !== false) {
            $quote_req_id = explode(",", $quote_req);
            $total_id = count($quote_req_id);


            for ($req = 0; $req < $total_id; $req++) {

                if ($quote_req_id[$req] == $getrec["quote_id"]) {
                    $n_quote_req_id = str_replace($quote_req_id[$req], "", $quote_req);
                    $new_quote_req_id = preg_replace("/,+/", ",", $n_quote_req_id);
                    $new_quote_req_id = trim($new_quote_req_id, ",");
                    //
                    //remove quote request ID and update in table
                    db_b2b();
                    $dbqry = db_query("update quote set quoteRequest='" . $new_quote_req_id . "' where id=" . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"]);
                    // echo $new_quote_req_id;
                    // echo $quote_rows1["ID"]."<br>";
                }
            }
        } else {
            // echo $getrec["quote_id"];
            if ($quote_req == $getrec["quote_id"]) {

                $delqry1 = "delete from quote where id = " . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"];
                db_b2b();
                $result1 = db_query($delqry1);
            }
        } //End else 

    } //End quote while loop   
    //
    db();
    $delqrymaster = "delete from quote_request where quote_id = '" . $getrec["quote_id"] . "'";
    $result1 = db_query($delqrymaster);
    //
    //Delete record from sub table
    $delqry = "delete from quote_pallets where id = " . $_REQUEST["paltableid"] . "";
    $result = db_query($delqry);
}
if ($_REQUEST["p"] == "dbi") {
    //Delete record from master table
    $getqry = db_query("select quote_dbi.id, quote_dbi.quote_id from quote_dbi where id= '" . $_REQUEST["dbitableid"] . "'");
    $getrec = array_shift($getqry);
    //Delete quote request id from quote table
    db_b2b();
    $chk_quote_query1 = "Select * from quote where companyID=" . $_REQUEST["companyid"];
    $chk_quote_res1 = db_query($chk_quote_query1);
    while ($quote_rows1 = array_shift($chk_quote_res1)) {
        //  echo "sdfsdf";
        $quote_req = $quote_rows1["quoteRequest"];

        if (strpos($quote_req, ',') !== false) {
            $quote_req_id = explode(",", $quote_req);
            $total_id = count($quote_req_id);


            for ($req = 0; $req < $total_id; $req++) {

                if ($quote_req_id[$req] == $getrec["quote_id"]) {
                    $n_quote_req_id = str_replace($quote_req_id[$req], "", $quote_req);
                    $new_quote_req_id = preg_replace("/,+/", ",", $n_quote_req_id);
                    $new_quote_req_id = trim($new_quote_req_id, ",");
                    //
                    //remove quote request ID and update in table
                    db_b2b();
                    $dbqry = db_query("update quote set quoteRequest='" . $new_quote_req_id . "' where id=" . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"]);
                    // echo $new_quote_req_id;
                    // echo $quote_rows1["ID"]."<br>";
                }
            }
        } else {
            // echo $getrec["quote_id"];
            if ($quote_req == $getrec["quote_id"]) {

                $delqry1 = "delete from quote where id = " . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"];
                db_b2b();
                $result1 = db_query($delqry1);
            }
        } //End else 

    } //End quote while loop   
    //
    db();
    $delqrymaster = "delete from quote_request where quote_id = '" . $getrec["quote_id"] . "'";
    $result1 = db_query($delqrymaster);
    //
    //Delete record from sub table
    $delqry = "delete from quote_dbi where id = " . $_REQUEST["dbitableid"] . "";
    $result = db_query($delqry);
}
if ($_REQUEST["p"] == "rec") {
    //Delete record from master table
    $getqry = db_query("select quote_recycling.id, quote_recycling.quote_id from quote_recycling where id= '" . $_REQUEST["rectableid"] . "'");
    $getrec = array_shift($getqry);
    //Delete quote request id from quote table
    db_b2b();
    $chk_quote_query1 = "Select * from quote where companyID=" . $_REQUEST["companyid"];
    $chk_quote_res1 = db_query($chk_quote_query1);
    while ($quote_rows1 = array_shift($chk_quote_res1)) {
        //  echo "sdfsdf";
        $quote_req = $quote_rows1["quoteRequest"];

        if (strpos($quote_req, ',') !== false) {
            $quote_req_id = explode(",", $quote_req);
            $total_id = count($quote_req_id);


            for ($req = 0; $req < $total_id; $req++) {

                if ($quote_req_id[$req] == $getrec["quote_id"]) {
                    $n_quote_req_id = str_replace($quote_req_id[$req], "", $quote_req);
                    $new_quote_req_id = preg_replace("/,+/", ",", $n_quote_req_id);
                    $new_quote_req_id = trim($new_quote_req_id, ",");
                    //
                    //remove quote request ID and update in table
                    db_b2b();
                    $dbqry = db_query("update quote set quoteRequest='" . $new_quote_req_id . "' where id=" . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"]);
                    // echo $new_quote_req_id;
                    // echo $quote_rows1["ID"]."<br>";
                }
            }
        } else {
            // echo $getrec["quote_id"];
            if ($quote_req == $getrec["quote_id"]) {

                $delqry1 = "delete from quote where id = " . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"];
                db_b2b();
                $result1 = db_query($delqry1);
            }
        } //End else 

    } //End quote while loop   
    //
    db();
    $delqrymaster = "delete from quote_request where quote_id = '" . $getrec["quote_id"] . "'";
    $result1 = db_query($delqrymaster);
    //
    //Delete record from sub table
    $delqry = "delete from quote_recycling where id = " . $_REQUEST["rectableid"] . "";
    $result = db_query($delqry);
}
if ($_REQUEST["p"] == "other") {
    //Delete record from master table
    $getqry = db_query("select quote_other.id, quote_other.quote_id from quote_other where id= '" . $_REQUEST["othertableid"] . "'");
    $getrec = array_shift($getqry);
    //Delete quote request id from quote table
    db_b2b();
    $chk_quote_query1 = "Select * from quote where companyID=" . $_REQUEST["companyid"];
    $chk_quote_res1 = db_query($chk_quote_query1);
    while ($quote_rows1 = array_shift($chk_quote_res1)) {
        //  echo "sdfsdf";
        $quote_req = $quote_rows1["quoteRequest"];

        if (strpos($quote_req, ',') !== false) {
            $quote_req_id = explode(",", $quote_req);
            $total_id = count($quote_req_id);

            for ($req = 0; $req < $total_id; $req++) {

                if ($quote_req_id[$req] == $getrec["quote_id"]) {
                    $n_quote_req_id = str_replace($quote_req_id[$req], "", $quote_req);
                    $new_quote_req_id = preg_replace("/,+/", ",", $n_quote_req_id);
                    $new_quote_req_id = trim($new_quote_req_id, ",");
                    //
                    //remove quote request ID and update in table
                    db_b2b();
                    $dbqry = db_query("update quote set quoteRequest='" . $new_quote_req_id . "' where id=" . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"]);
                }
            }
        } else {
            // echo $getrec["quote_id"];
            if ($quote_req == $getrec["quote_id"]) {

                $delqry1 = "delete from quote where id = " . $quote_rows1["ID"] . " and companyID=" . $_REQUEST["companyid"];
                db_b2b();
                $result1 = db_query($delqry1);
            }
        } //End else 

    } //End quote while loop   
    //
    db();
    $delqrymaster = "delete from quote_request where quote_id = '" . $getrec["quote_id"] . "'";
    $result1 = db_query($delqrymaster);
    //
    //Delete record from sub table
    $delqry = "delete from quote_other where id = " . $_REQUEST["othertableid"] . "";
    $result = db_query($delqry);
}