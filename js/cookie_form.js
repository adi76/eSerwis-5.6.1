// Cookie Functions  ////////////////////  (:)
// Set the cookie.
// SetCookie('your_cookie_name', 'your_cookie_value', exp);
// Get the cookie.
// var someVariable = GetCookie('your_cookie_name');

var expDays = 100;
var exp = new Date(); 
exp.setTime(exp.getTime() + (expDays*24*60*60*1000));

function getCookieVal (offset) {  
	var endstr = document.cookie.indexOf (";", offset);  
	if (endstr == -1) { endstr = document.cookie.length; }
	return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie (name) {  
	var arg = name + "=";  
	var alen = arg.length;  
	var clen = document.cookie.length;  
	var i = 0;  
	while (i < clen) {    
		var j = i + alen;    
		if (document.cookie.substring(i, j) == arg) return getCookieVal (j);    
		i = document.cookie.indexOf(" ", i) + 1;    
		if (i == 0) break;   
	}  
	return null;
}

function SetCookie (name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}

function ClearCookie (name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : (";expires=Thu, 01-Jan-1970 00:00:01 GMT")) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}

// cookieForms saves form content of a page.
// use the following code to call it:
//  <body onLoad="cookieForms('open', 'form_1', 'form_2', 'form_n')" onUnLoad="cookieForms('save', 'form_1', 'form_2', 'form_n')">
// It works on text fields and dropdowns in IE 5+
// It only works on text fields in Netscape 4.5

function cookieForms() {  
	var mode = cookieForms.arguments[0];
	for(f=1; f<cookieForms.arguments.length; f++) {
		formName = cookieForms.arguments[f];

		if (mode == 'open') {	
			cookieValue = GetCookie('saved_'+formName);
			if(cookieValue != null) {
				var cookieArray = cookieValue.split('#cf#');
				if(cookieArray.length == document[formName].elements.length) {
					for(i=0; i<document[formName].elements.length; i++) {
						if(cookieArray[i].substring(0,6) == 'select') { document[formName].elements[i].options.selectedIndex = cookieArray[i].substring(7, cookieArray[i].length-1); }
						else if((cookieArray[i] == 'cbtrue') || (cookieArray[i] == 'rbtrue')) { document[formName].elements[i].checked = true; }
						else if((cookieArray[i] == 'cbfalse') || (cookieArray[i] == 'rbfalse')) { document[formName].elements[i].checked = false; }
						else { document[formName].elements[i].value = (cookieArray[i]) ? cookieArray[i] : ''; }
					}
				}
			}
		}

		if (mode == 'save') {	
			cookieValue = '';
			for(i=0; i<document[formName].elements.length; i++) {
				fieldType = document[formName].elements[i].type;
				if(fieldType == 'password') { passValue = ''; }
				else if(fieldType == 'checkbox') { passValue = 'cb'+document[formName].elements[i].checked; }
				else if(fieldType == 'radio') { passValue = 'rb'+document[formName].elements[i].checked; }
				else if(fieldType == 'select-one') { passValue = 'select'+document[formName].elements[i].options.selectedIndex; }
				else { passValue = document[formName].elements[i].value; }
				cookieValue = cookieValue + passValue + '#cf#';
			}
			cookieValue = cookieValue.substring(0, cookieValue.length-4); // Remove last delimiter
			SetCookie('saved_'+formName, cookieValue, exp);		
		}	
	}
}
//  End -->