function account_follow(uid, followid, id){
  if(uid.length == 0){
    document.getElementById(id).innerHTML = '';
    return;
  }
  getRequest('introduce/account_follow.php?UID='+uid+'&&FOLLOWID='+followid,function(response){
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
