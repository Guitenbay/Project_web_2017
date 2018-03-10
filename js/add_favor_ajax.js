function add_favor(uid, imageid, path, id){
  if(uid.length == 0){
    document.getElementById(id).innerHTML = '';
    return;
  }
  getRequest('introduce/addFavor.php?UID='+uid+'&&ImgID='+imageid+'&&Path='+path,function(response){
    document.getElementById(id).innerHTML = response;
  });
}
function delete_favor(uid, imageid, path, id){
  if(uid.length == 0){
    document.getElementById(id).innerHTML = '';
    return;
  }
  getRequest('introduce/addFavor.php?delete=true'+'&&UID='+uid+'&&ImgID='+imageid+'&&Path='+path,function(response){
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
