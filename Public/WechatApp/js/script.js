//当页面加载完毕以后，自动执行的动作
window.onload = function() {

	//1. 校验邀请码是否符合要求
	document.getElementById('InvitationCode').onblur = function() {
		//1.1 先获得用户输入的数据
	    var user = this.value;  
	    
	    //1.2 校验邀请码是否符合规范
	    var userExp = /^[a-zA-Z]\w{5,}$/; //邀请码正则匹配规则
	    if (false == userExp.test(user)) {
	    	//1.2.1 动态写入错误提示
	        document.getElementById('invitationCodeTip').innerHTML = '邀请码不符合要求';
	        //1.2.2 用户名控件重新获得焦点
	        this.focus();
	    } else { //校验通过
	    	document.getElementById('invitationCodeTip').innerHTML = '';
	    }
	};

	
	//5. 更换验证码操作的实现
	document.getElementById('captchaImg').onclick = function() {
		//修改验证码图片
		this.src = "Index/captcha/atype/register/" + Math.random();
	};
};