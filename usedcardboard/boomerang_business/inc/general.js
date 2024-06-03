function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57) )
	{
		if (charCode == 46 || charCode == 44)
		{return true;}
		else{return false;}
	}else
		{
			return true;
		}
}


function SetFocus() {
  if (document.forms.length > 0) {
    var field = document.forms[0];
    for (i=0; i<field.length; i++) {
      if ( (field.elements[i].type != "image") &&
           (field.elements[i].type != "hidden") &&
           (field.elements[i].type != "reset") &&
           (field.elements[i].type != "submit") ) {

        document.forms[0].elements[i].focus();

        if ( (field.elements[i].type == "text") ||
             (field.elements[i].type == "password") )
          document.forms[0].elements[i].select();

        break;
      }
    }
  }
}

function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}
function strTrim(tmpStr)
{
	tmpStr = tmpStr.replace(/^\s+/,"");//remove leading
	tmpStr = tmpStr.replace(/\s+$/,"");//remove trailing
	return tmpStr;
}
function chkEmail(tmpStr)
{
	var i;
	var posAt = 0;
	var posDot = 0
	var count = 0;
	for(i=0;i<tmpStr.length;++i)
	{
		if(tmpStr.charAt(i) == "@")
		{
			posAt = i;
			count++;
		}
		if(tmpStr.charAt(i) == ".")
		{
			posDot = i;
		}
		if (!((tmpStr.charAt(i) >= "0" && tmpStr.charAt(i) <= "9")
				||(tmpStr.charAt(i) >= "a" && tmpStr.charAt(i) <= "z")
				|| (tmpStr.charAt(i)>= "A" && tmpStr.charAt(i) <= "Z")
				|| (tmpStr.charAt(i) == "-")
				|| (tmpStr.charAt(i) == "_")
				|| (tmpStr.charAt(i) == "@")
				|| (tmpStr.charAt(i) == ".")
			)) return false;
	}
	if(count>1) return false;
	if(eval(posAt) > 1 && posAt != tmpStr.length-1 && posDot > posAt && posDot != tmpStr.length-1) return true;
	return false;
}
