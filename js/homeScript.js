// 登陆菜单
function dropdownIn() {
	document.getElementById("dropdown").style.display="block";
}
function dropdownOut() {
	document.getElementById("dropdown").style.display="none";
}
//顶部按钮
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
//图片自动轮换
var arr = new Array();
arr[0] = "#medium0";
arr[1] = "#medium1";
arr[2] = "#medium2";
arr[3] = "#medium3";
var arrPoint = new Array();
arrPoint[0] = "#slide0";
arrPoint[1] = "#slide1";
arrPoint[2] = "#slide2";
arrPoint[3] = "#slide3";
var now = 0;
var timer;
$(document).ready(function(){
	for(var i=1;i<arr.length;i++){
		$(arr[i]).hide();
	}
	$(arrPoint[now]).css("background-color", "rgb(236, 154, 62)");
	timer = setInterval("mediumNext()",6000);
});
function mediumChange(single, index){
	$("#back").attr("disabled", "disabled");
	$("#pre").attr("disabled", "disabled");
	var late = now;
	if (index == late){
		return;
	}
	$(arr[late]).animate({width:"toggle"},"slow",function(){
		$(arr[index]).animate({width:"toggle"},"slow",function(){
			$("#back").removeAttr("disabled");
			$("#pre").removeAttr("disabled");
		});
	});
	$(arrPoint[late]).css("background-color", "#f5f5f5");
	$(arrPoint[index]).css("background-color", "rgb(236, 154, 62)");
	now = index;
}
function mediumNext() {
	var n = (arr.length + now + 1)%arr.length;
	mediumChange(false, n);
}
function pre() {
	clearInterval(timer);
	mediumNext();
	timer = setInterval("mediumNext()",6000);
}
function back() {
	clearInterval(timer);
	mediumChange(true, (arr.length + now - 1)%arr.length);
	timer = setInterval("mediumNext()",6000);
}
function pointChange(i) {
	clearInterval(timer);
	mediumChange(true, (arr.length + i)%arr.length);
	timer = setInterval("mediumNext()",6000);
}
