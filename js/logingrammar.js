window.onload = function(){
  justify_username('username');
  justify_password();
}
function justify_username(str){
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
// function justify_username(){
//   var div = document.getElementById('username-div');
//   var element = document.getElementById('username');
//   var help = document.getElementById('username-warn');
//   element.addEventListener('input', function(){
//     var v = element.value;
//     var space = v.match(/[^ ]\s/g);
//     var arrInitail = v.match(/[A-Z]/g);
//     var arrname = v.match(/[A-Z][a-z]{2,}/g);
//     if (v != ''){
//       if (space != null){
//         if (space.length == 1){
//           div.classList.remove("has-error");
//           help.innerHTML = "";
//           if (arrInitail != null){
//             div.classList.remove("has-error");
//             help.innerHTML = "";
//             if (arrInitail.length == 2) {
//               div.classList.remove("has-error");
//               help.innerHTML = "";
//               if (arrname != null){
//                 if (arrname.length == 2){
//                   div.classList.add("has-success");
//                   div.classList.remove("has-error");
//                   help.innerHTML = "";
//                 } else if (arrname.length > 2){
//                   div.classList.add("has-error");
//                   div.classList.remove("has-success");
//                   help.innerHTML = "To much names.";
//                 } else {
//                   div.classList.add("has-error");
//                   div.classList.remove("has-success");
//                   help.innerHTML = "Every name must be 3 charectors.";
//                 }
//               } else {
//                 div.classList.add("has-error");
//                 div.classList.remove("has-success");
//                 help.innerHTML = "Every name must be 3 charectors.";
//               }
//             } else if (arrInitail.length > 2) {
//               div.classList.add("has-error");
//               div.classList.remove("has-success");
//               help.innerHTML = "Only input 1 CAPITAL charectors in each name.";
//             } else {
//               if (arrname != null){
//                 if (arrname.length == 1){
//                   div.classList.remove("has-error");
//                   help.innerHTML = "";
//                   if(v.match(/\s[a-z]+/g)){
//                     div.classList.add("has-error");
//                     div.classList.remove("has-success");
//                     help.innerHTML = "The initail must be CAPITAL charectors.";
//                   }
//                 } else {
//                   div.classList.add("has-error");
//                   div.classList.remove("has-success");
//                   help.innerHTML = "Every name must be 3 charectors.";
//                 }
//               } else {
//                 div.classList.add("has-error");
//                 div.classList.remove("has-success");
//                 help.innerHTML = "Every name must be 3 charectors.";
//               }
//             }
//           }else {
//             div.classList.add("has-error");
//             div.classList.remove("has-success");
//             help.innerHTML = "The initail must be CAPITAL charectors.";
//           }
//
//         } else if (space.length > 1){
//           div.classList.add("has-error");
//           div.classList.remove("has-success");
//           help.innerHTML = "SPACE charector must be only one.";
//         }
//       } else {
//         if (v.match(/\s/)) {
//           div.classList.add("has-error");
//           div.classList.remove("has-success");
//           help.innerHTML = "First one shouldn't be SPACE.";
//         } else {
//           div.classList.add("has-error");
//           div.classList.remove("has-success");
//           help.innerHTML = "Input Firstname and Lastname.";
//         }
//       }
//     }else{
//       div.classList.remove("has-error");
//       help.innerHTML = "";
//     }
//   });
// }
function justify_password(){
  var passwordDiv = document.getElementById("password-div");
  var passwordElement = document.getElementById("password");
  var passwordHelp = document.getElementById("password-warn");
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
