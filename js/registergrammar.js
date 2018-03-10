window.onload = function(){
  justify_name('firstname');
  justify_name('lastname');
  justify_email('email');
  justify_password('password');
  justify_password('passwordagain');
}
function justify_email(str){
  var div = document.getElementById(str+'-div');
  var element = document.getElementById(str);
  var help = document.getElementById(str+'-warn');
  element.addEventListener('input', function(){
    var v = element.value;
    if (v == '') {
      div.classList.remove("has-error");
    } else {
      if (v.match(/^[a-z0-9\.\_]+@([a-z0-9]+\.)+[a-z]{2,}$/i)){
        div.classList.add("has-success");
        div.classList.remove("has-error");
      } else {
        div.classList.add("has-error");
        div.classList.remove("has-success");
      }
    }
  });
}
function justify_name(str){
  var div = document.getElementById(str+'-div');
  var element = document.getElementById(str);
  var help = document.getElementById(str+'-warn');
  element.addEventListener('input', function(){
    var v = element.value;
    if (v == '') {
      div.classList.remove("has-error");
      help.innerHTML = "";
    } else {
      if (v.match(/\s/)) {
        div.classList.add("has-error");
        div.classList.remove("has-success");
        help.innerHTML = "SPACE.";
      } else {
        if (v.match(/^[A-Z]/)) {
          div.classList.remove("has-error");
          help.innerHTML = "";
          var arrAZ = v.match(/[A-Z]/g)
          if (arrAZ != null) {
            if (arrAZ.length > 1) {
              div.classList.add("has-error");
              div.classList.remove("has-success");
              help.innerHTML = "Only one CAPITAL.";
            } else {
              if (v.match(/^[A-Z][a-z]{2,}$/)) {
                div.classList.add("has-success");
                div.classList.remove("has-error");
                help.innerHTML = "";
              } else {
                div.classList.add("has-error");
                div.classList.remove("has-success");
                help.innerHTML = "3 charectors at least";
              }
            }
          }
        } else {
          div.classList.add("has-error");
          div.classList.remove("has-success");
          help.innerHTML = "initail is CAPITAL.";
        }

      }
    }
  });
}
function justify_password(str){
  var passwordDiv = document.getElementById(str + "-div");
  var passwordElement = document.getElementById(str);
  var passwordHelp = document.getElementById(str + "-warn");
  passwordElement.addEventListener("input", function(){
    var password = passwordElement.value;
    var patt = new RegExp(' ');
    if (patt.test(password)) {
      passwordDiv.classList.add("has-error");
      passwordDiv.classList.remove("has-success");
      passwordHelp.innerHTML = "Shouldn't input SPACE charector.";
    } else {
      passwordDiv.classList.remove("has-error");
      passwordHelp.innerHTML = "";
      if (password.length >= 6) {
        passwordDiv.classList.add("has-success");
        passwordDiv.classList.remove("has-error");
      } else {
        passwordDiv.classList.remove("has-success");
      }
    }
  });
}
