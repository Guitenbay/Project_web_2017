function change_img(num){
  if(num.length == 0){
    document.getElementById(id).innerHTML = '';
    return;
  }
  getRequest('introduce/change.php?change='+num,function(response){
    document.getElementById('most').innerHTML = response;
  })
  return (num + 6)%18;
}
window.onload=function(){
  changebtn = document.getElementById("changebtn");
  var i = 6;
  changebtn.addEventListener("click", function(){
    i = change_img(i);
  });
  newbtn = document.getElementById("newbtn");
  newbtn.addEventListener("click", function(){
    getRequest('introduce/change.php?new=true',function(response){
      document.getElementById('newWall').innerHTML = response;
    })
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
