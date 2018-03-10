//登陆菜单
function dropdownIn() {
	document.getElementById("dropdown").style.display="block";
}

function dropdownOut() {
	document.getElementById("dropdown").style.display="none";
}
// 顶部按钮
function topButton() {
	var h = window.pageYOffset;
	if (h >= 600){
		$("#top").stop();
		$("#top").fadeIn();
	} else {
		$("#top").stop();
		$("#top").fadeOut();
	}
}