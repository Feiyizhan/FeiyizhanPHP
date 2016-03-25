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
	        document.getElementById('userTip').innerHTML = '邀请码不符合要求';
	        //1.2.2 用户名控件重新获得焦点
	        this.focus();
	    } else { //校验通过
	    	document.getElementById('userTip').innerHTML = '';
	    }
	};
	
	//2. 密码控件校验
	document.getElementById('password').onblur = function() {
		//2.1 先获得用户输入的数据
	    var pswd = this.value;  
	    
	    //2.2 校验密码是否符合规范
	    var pswdExp = /^\w{8,}$/; //密码正则匹配规则
	    if (false == pswdExp.test(pswd)) {
	    	//2.2.1 动态写入错误提示
	        document.getElementById('pswdTip').innerHTML = '密码不符合要求';
	    } else { //校验通过
	    	document.getElementById('pswdTip').innerHTML = '';
	    }
	};
	
	//3. 确认密码控件校验
	document.getElementById('password2').onblur = function() {
		//3.1 先获得用户输入的数据
	    var pswd2 = this.value;
	    var pswd1 = document.getElementById('password').value;
	    
	    //3.2 校验确认密码是否与密码相同
	    if (pswd2 != pswd1) {
	    	//3.2.1 动态写入错误提示
	        document.getElementById('pswd2Tip').innerHTML = '确认密码必须与密码相同';
	    } else { //校验通过
	    	document.getElementById('pswd2Tip').innerHTML = '';
	    }
	};
	
	//4. 电子邮件控件的校验
//	document.getElementById('email').onblur = function() {
//		//4.1 先获得用户输入的数据
//	    var email = this.value;
//	    
//	    //4.2 校验电子邮件是否符合规则
//	    var emailExp = /^\w+@\w+\.\w+$/; //密码正则匹配规则
//	    if (false == emailExp.test(email)) {
//	    	//4.2.1 动态写入错误提示
//	        document.getElementById('emailTip').innerHTML = '电子邮件不符合规则';
//	    } else { //校验通过
//	    	document.getElementById('emailTip').innerHTML = '';
//	    }
//	};
	
	//5. 更换验证码操作的实现
	document.getElementById('captchaImg').onclick = function() {
		//修改验证码图片
		this.src = "../captcha/atype/register/" + Math.random();
	};
};