// js function file
function show_moreinfo(){
	var checkopen = document.getElementById("extra-info-main").style.display;
	if (checkopen == "none"){
		document.getElementById("extra-info-main").style.display = "block";
	} else {
		document.getElementById("extra-info-main").style.display = "none";
	}
}

function productinfo(e){
	var eval = e.value;
	switch(eval){
		case '1': 
			document.getElementById("productQntype").value = document.getElementById("productQntype1").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt1").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice1").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal1").innerHTML;
			break;
		case '2': 
			document.getElementById("productQntype").value = document.getElementById("productQntype2").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt2").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice2").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal2").innerHTML;
			break;
		case '3': 
			document.getElementById("productQntype").value = document.getElementById("productQntype3").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt3").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice3").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal3").innerHTML;
			break;
		case '4': 
			document.getElementById("productQntype").value = document.getElementById("productQntype4").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt4").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice4").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal4").innerHTML;
			break;
	}
	
}
function formvalidate(){

        var proradio = document.getElementsByName('radio');
        var proValue = false;

        for(var i=0; i<proradio.length;i++){
            if(proradio[i].checked == true){
                proValue = true;    
            }
        }
        if(!proValue){
            alert("Please Choose the product type");
            return false;
        } else {
			//document.getElementById("orderfrm").submit(); 
			return true;
		}

    }
