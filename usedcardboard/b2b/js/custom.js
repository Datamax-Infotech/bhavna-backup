/*contact pg js start*/
function chkFrmContactInfo(){
	var emailVal = document.getElementById("checkout_shipping_address_email").value;
	var firstNm  = document.getElementById("checkout_shipping_address_first_name").value; 
	var compNm  = document.getElementById("checkout_shipping_address_company").value; 
	//var create_acc = document.getElementById("chkContactFrmCreateAcc").checked;
	//var CrAccPass = document.getElementById("txtCrAccPass").value;
	//var CrAccPassConfrm = document.getElementById("txtCrAccPassConfrm").value;
	
	if (firstNm == ""){			
		alert("Please enter your first name.");
		document.getElementById("checkout_shipping_address_first_name").focus();
		return false;
	}
	if (compNm == ""){			
		alert("Please enter your Company name.");
		document.getElementById("checkout_shipping_address_company").focus();
		return false;
	}
	
	/*if (create_acc == true){			
		document.getElementById("chkContactFrmGuest").checked = false;
		
		if (CrAccPass == "" || CrAccPassConfrm == "") {
			alert("Please enter password.");
			document.getElementById("txtCrAccPass").focus();
			return false;
		}	
		if (CrAccPass != CrAccPassConfrm)  {
			alert("Password and Confirm Password do not match, please check.");
			document.getElementById("txtCrAccPass").focus();
			return false;
		}	
	}*/
	
	if (emailVal == ""){			
		alert("Please enter valid email.");
		document.getElementById("checkout_shipping_address_email").focus();
		return false;
	}else if(!(validateEmail(emailVal))) {
		alert("Please enter valid email.");
		document.getElementById("checkout_shipping_address_email").focus();
		return false;
	}
		
	//document.getElementById("frmContactInfo").submit();
	$.ajax({
		type:'POST',
		url:'add_contact_info.php',
		data:$('#frmContactInfo').serialize(),
		success:function(responce){
			//alert(responce);
			if(responce == 'STOP'){
				alert("Email already registered with us, please login to continue.");
				return false;
			}else{
				$('#frmContactInfo').submit();
				return true;
			}
		}
	}); 
	
}
/*contact pg js end*/
function validateEmail(email) {
 	const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  	return re.test(email);
}

/*shipping pg js start*/
$(document).ready(function(){
	$('#btnShippingadd').click(function(){
		var emailVal	= document.getElementById("txtshippingaddEmail").value;
		var firstNm 	= document.getElementById("txtshippingaddFNm").value; 
		var add1 		= document.getElementById("txtshippingAdd1").value;
		var city		= document.getElementById("txtshippingaddCity").value;
		var state		= document.getElementById("txtshippingaddState").value;
		var zip			= document.getElementById("txtshippingaddZip").value;
		if (firstNm == ""){			
			alert("Please enter your first name.");
			document.getElementById("txtshippingaddFNm").focus();
			return false;
		}
		if (emailVal == ""){			
			alert("Please enter valid email.");
			document.getElementById("txtshippingaddEmail").focus();
			return false;
		}else if(!(validateEmail(emailVal))) {
			alert("Please enter valid email.");
			document.getElementById("txtshippingaddEmail").focus();
			return false;
		}
		if (add1 == ""){			
			alert("Please enter your address.");
			document.getElementById("txtshippingAdd1").focus();
			return false;
		}
		if (city == ""){			
			alert("Please enter your city.");
			document.getElementById("txtshippingaddCity").focus();
			return false;
		}
		if (state == ""){			
			alert("Please enter your state.");
			document.getElementById("txtshippingaddState").focus();
			return false;
		}
		if (zip == ""){			
			alert("Please enter your zip code.");
			document.getElementById("txtshippingaddZip").focus();
			return false;
		}
		
		var spinner = $('#loader');
		spinner.show();
		$.ajax({			
			type:'POST',
			url:'add_shipping_info.php',
			data:$('#frmContinuePayment').serialize(),
			success:function(responce){
				if(responce != ''){

					var quoteRes = responce.split('~');
					for(i = 0; i < quoteRes.length; i++){
				        var quoteName 	= quoteRes[0];
				        var quoteQty 	= quoteRes[1];
				        var quoteUnitPr	= quoteRes[2];
				        var quoteTotal 	= quoteRes[3];
				        var quoteRate 	= quoteRes[4];
				        var total 		= quoteRes[5];
						var distance	= quoteRes[6];
						var shipadd		= quoteRes[7];
						var unitvalue	= quoteRes[8];
						var shipping_cost_err_flg	= quoteRes[9];
				    }
					
					//var n = distance.toString().split(".");
					//n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					//distance = n.join(".");
					
					if (shipping_cost_err_flg == 0){
						$('#quoteRate').html("Shipping Quote = $"+quoteRate);
						$('#chkcp2-txt').html("I approved the shipping quote of $" + quoteRate + " provided by UCB.");
					}else{
						$('#quoteRate').html(quoteRate);
						$('#chkcp2-txt').html("I approved the shipping quote of $0 provided by UCB.");
					}

					$('.total').html("$"+total);
					$('.payment-due__price').html("$"+total);
					$('.caltxt').html("$" + unitvalue + " \/ unit shipping incl.");
					$('#quoteName').html(quoteName);
					$('#quoteQty').html(quoteQty);
					$('#quoteUnitPr').html("$"+quoteUnitPr);
					$('#quoteTotal').html("$"+quoteTotal);
					$('#distance').html(distance + " mi from item origin.");
					$('#Span1').html(shipadd);

					document.getElementById("shipping_cost_err_flg").value = shipping_cost_err_flg;
					document.getElementById("btnShippingadd").value = 'Re-calculate shipping';

					//if (quoteRate > 0){

						//$('.button_slide_cal_shipping').hide();

						/*$('#chkcp1').removeAttr("disabled");
						$('#chkcp2').removeAttr("disabled");						
						
						$('#btnContinuePay').removeAttr("disabled");
						$("#frmContinuePayment").removeClass("coninuePayment");

						$(".content-box").removeClass("coninuePayment");
						$('#billingAdd1').removeAttr("disabled");
						$('#billingAdd2').removeAttr("disabled");
						$('#billingAddCity').removeAttr("disabled");
						$('#billingAddState').removeAttr("disabled");
						$('#billingAddZip').removeAttr("disabled");
						$('#billingAddEmail').removeAttr("disabled");
						$('#billingAddPhn').removeAttr("disabled");
						$('#rdoDiffBillingAdd').removeAttr("disabled");
						$('#rdoSameBillingAdd').removeAttr("disabled");						
						*/
					//}	


				}
				spinner.hide(); 
				return true;
			}
		}); 
	});
	$('#btnContinuePay').click(function(){
		/*var rdoVal = $("input[name='rdoBillingAdd']:checked").val();
		if(rdoVal == 'different'){
			var billingAddEmail	= document.getElementById("billingAddEmail").value;
			var billingAdd1		= document.getElementById("billingAdd1").value;
			var billingAddCity	= document.getElementById("billingAddCity").value;
			var billingAddState	= document.getElementById("billingAddState").value;
			var billingAddZip	= document.getElementById("billingAddZip").value;

			if (billingAddEmail == ""){			
				alert("Please enter valid email.");
				document.getElementById("billingAddEmail").focus();
				return false;
			}else if(!(validateEmail(billingAddEmail))) {
				alert("Please enter valid email.");
				document.getElementById("billingAddEmail").focus();
				return false;
			}

			if (billingAdd1 == ""){			
				alert("Please enter your address.");
				document.getElementById("billingAdd1").focus();
				return false;
			}
			if (billingAddCity == ""){			
				alert("Please enter your city.");
				document.getElementById("billingAddCity").focus();
				return false;
			}
			if (billingAddState == ""){			
				alert("Please enter your state.");
				document.getElementById("billingAddState").focus();
				return false;
			}
			if (billingAddZip == ""){			
				alert("Please enter your zip code.");
				document.getElementById("billingAddZip").focus();
				return false;
			}
		}
		*/

		var rdoUCB_CustVal = $("input[name='rdoPickUp']:checked").val();
		if (rdoUCB_CustVal == 'UCB Delivery')
		{
			var chkcp1 = $("input[name='chkcp1']:checked").val();
			var chkcp2 = $("input[name='chkcp2']:checked").val();
			var shipping_cost_err_flg = document.getElementById("shipping_cost_err_flg").value;

			//alert('checkbox1 -> '+chkcp1+' / checkbox2 -> '+chkcp2);
			if(shipping_cost_err_flg == ""){
				alert("Please calculate the shipping quote.");
				return false;
			}else{
				var errMsg1 = 'The load dock/forklift acknowledgement must be checked as approved to move forward.';
				var errMsg2 = 'The shipping quote must be checked as approved to move forward.';
				
				if(chkcp1 == 1 ){
					//$('#errMsg1').html(errMsg1).hide(10000);
				}else{	
					document.getElementById("errMsg1").style.display = 'block';
					$('#errMsg1').html(errMsg1);
				}
				
				if(chkcp2 == 1){
				
				}else{	
					document.getElementById("errMsg2").style.display = 'block';
					$('#errMsg2').html(errMsg2);
				}
				if(chkcp1 != 1 && chkcp2 != 1 ){
					//$('#errMsg1').html(errMsg1).hide(10000);
					$('#errMsg1').html(errMsg1);
					$('#errMsg2').html(errMsg2);
					document.getElementById("errMsg1").style.display = 'block';
					document.getElementById("errMsg2").style.display = 'block';
					return false;
				}
				if( chkcp1 == 1 && chkcp2 == 1 ){ 
					$('#frmContinuePayment').submit();
					return true;
				}
			}	
		}else{
			var errMsg3 = 'Click here to move forward.';
			var chkcp3 = $("input[name='chkcp3']:checked").val();
			
			if(chkcp3 == 1 ){
				$('#frmContinuePayment').submit();
				return true;
			}else{	
				$('#errMsg3').html(errMsg3);
				document.getElementById("errMsg3").style.display = 'block';
				return false;
			}
		}
	});
	
	/*report pg js start*/
	$('#myModalOne').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'fetch_record.php', //Here you will fetch records 
            data : { rowid: rowid, usefor: 'one' },
            success : function(data){
            $('.fetched-data').html(data);//Show fetched data from database
            }
        });
    });

    $('#myModalTwo').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'fetch_record.php', //Here you will fetch records 
            data : { rowid: rowid, usefor: 'two' },
            success : function(data){
            $('.fetched-data_two').html(data);//Show fetched data from database
            }
        });
    });

    $('#myModalThree').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'fetch_record.php', //Here you will fetch records 
            data : { rowid: rowid, usefor: 'three' },
            success : function(data){
            $('.fetched-data_three').html(data);//Show fetched data from database
            }
        });
    });

    $('#myModalFour').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'fetch_record.php', //Here you will fetch records 
            data : { rowid: rowid, usefor: 'four' },
            success : function(data){
            $('.fetched-data_four').html(data);//Show fetched data from database
            }
        });
    });
	/*report pg js ends*/	
});
/*shipping pg js ends*/