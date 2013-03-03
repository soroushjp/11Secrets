function setCookie(c_name,value,exdays, path, domain)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()) + ((path==null) ? "" : "; path="+path) + ((domain==null) ? "" : "; domain="+domain);
document.cookie=c_name + "=" + c_value;
}

//JS function that is equivalent to PHP's urlencode() function
function urlencode(str){  
  return escape(str).replace(/\+/g,'%2B').replace(/%20/g, '+').replace(/\*/g, '%2A').replace(/\//g, '%2F').replace(/@/g, '%40');  
}

function deleteCookie(key)
{
  // Delete a cookie by setting the date of expiry to yesterday
  date = new Date();
  date.setDate(date.getDate() -1);
  document.cookie = escape(key) + '=;expires=' + date;
}