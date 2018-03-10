//登陆菜单
function dropdownIn() {
	document.getElementById("dropdown").style.display="block";
}

function dropdownOut() {
	document.getElementById("dropdown").style.display="none";
}
// 顶部按钮
function topButton(n) {
	if (n >= 600){
		$("#top").stop();
		$("#top").fadeIn();
	} else {
		$("#top").stop();
		$("#top").fadeOut();
	}
}
//导航栏动画
function navbarTop(){
	var h = window.pageYOffset;
	if(window.innerWidth <= 1024){
		if(h >= 30){
		$("#pageName").stop();
		$("#photosearch").stop();
		$("#pageName").fadeIn(220);
	} else {
		$("#pageName").stop();
		$("#photosearch").stop();
		$("#pageName").fadeOut(500);
	}
	} else {
	if(h >= 30){
		$("#pageName").stop();
		$("#photosearch").stop();
		$("#pageName").fadeIn(220);
		$("#photosearch").fadeIn(220);
	} else {
		$("#pageName").stop();
		$("#photosearch").stop();
		$("#photosearch").fadeOut(500);
		$("#pageName").fadeOut(500);
	}
	}
	topButton(h);
}

//开始动画
$(document).ready(function(){
	if (window.innerWidth <= 1024){
		$("#photosearch").css("display","none");
		$("#pageName").css({
			"left":"10%",
			"margin-left": "0px"
		});
		if (window.innerWidth <= 700) {
			$(".head_right").css("display","none");
		}
	}
	navbarTop();
});
//媒体查询
$(window).resize(function(){
	if(window.innerWidth <= 1024){
		$("#photosearch").css("display","none");
		$("#pageName").css({
			"left":"10%",
			"margin-left": "0px"
		});
		if (window.innerWidth <= 700) {
			$(".head_right").css("display","none");
		} else {
			$(".head_right").css("display","block");
		}
	} else {
		if (window.pageYOffset <= 30) {
			$("#photosearch").css("display","inline-block");
			$("#photosearch").fadeOut(500);
			$("#pageName").css({
				"left":"50%",
				"margin-left": "-50px"
			});
		} else {
			$("#photosearch").css("display","inline-block");
			$("#pageName").css({
				"left":"50%",
				"margin-left": "-50px"
			});
		}
	}
});
//图片页面切换
function footChange(index) {
	$("#page_foot li").attr("id","");
	$("#page_foot li").get(index).id="thisPage";
}
