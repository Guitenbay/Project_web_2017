function make_section(str, id, user, userBeFollow="", userImgid=""){
  if(str.length == 0){
    document.getElementById(id).innerHTML = '';
    return;
  }
  getRequest('introduce/account-section.php?section='+str+'&&user='+user+'&&userBeFollow='+userBeFollow+'&&userImgid='+userImgid,function(response){
    document.getElementById(id).innerHTML = response;
  });
}

function unfollow(user, userBeFollow){
  make_section('Followed', 'myphoto', user, userBeFollow);
}

function deleteFavor(user, imageid){
  make_section('Favorites', 'myphoto', user, "", imageid);
}

function deletePhoto(user, imageid){
  make_section('Photos', 'myphoto', user, "", imageid);
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
