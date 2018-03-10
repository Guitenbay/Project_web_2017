function make_options(str, id){
  if(str.length == 0){
    document.getElementById(id).innerHTML = '';
    return;
  }
  getRequest('introduce/selector.php?'+str,function(response){
    document.getElementById(id).innerHTML = response;
  });
}
function getRequest(url,commander){
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
      commander(xmlhttp.responseText);
    }
  }
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
}
function postRequst(url, str, commander){
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
      commander(xmlhttp.responseText);
    }
  }
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.open('POST', url, true);
  xmlhttp.send(str);
}
