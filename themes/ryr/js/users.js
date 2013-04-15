var is_rejest=0;
define(function(require, exports, module) {
	
	exports.check_email = function(email,re){
		var reg1 = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
		var msg = "";
		email = $("#"+email).val();
		email = trim(email);
		if (email == ''){
			msg = "���䲻��Ϊ��";
			$("#"+re).html(msg);
		}
		else if (!reg1.test(email)){
			msg = "�����ʽ����";
			$("#"+re).html(msg);
		}else{
			$.ajax({
				type:"get",
				url:'/?user',
				data:'&q=check_email&email='+email,
				success:function(result){
					if (result=="1"){
						msg = "�����Ѿ�����";	
						status = "1";
					}else{
						msg = "����ע��";	
						status = "0";
					}
					$("#"+re).html(msg);
				},
				cache:false
			});
		}
	}
 
 
	exports.check_username = function(username,re){
		var msg = "";
		var _username = trim($("#"+username).val());
		msg = test_username(_username);
		if(msg!=true){
			$("#"+re).html(msg);
			return false;
		}
		
		if (exports.get_length(_username) <4){
			msg = "�û�������С��2����";
			$("#"+re).html(msg);
		}
		else if (exports.get_length(_username)>15){
			msg = "�û������ܴ���15����";
			$("#"+re).html(msg);
		}else{
			$.ajax({
				type:"get",
				url:'/?user',
				data:'&q=check_username&username='+_username,
				success:function(result){
					if (result=="1"){
						msg = "�û����Ѿ�����";	
					}else{
						//msg = "<font color='red'>����ע��</font>";	
						msg = "<img  src='/themes/ryr/images/answer_success.jpg'>";	
					}
					$("#"+re).html(msg);
				},
				cache:false
			});
		}
	}
	exports.get_length= function (str){
		var len = str.length;
		var reLen = 0;
		for (var i = 0; i < len; i++) {        
			if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) {
				// ȫ��    
				reLen += 2;
			} else {
				reLen++;
			}
		}
		return reLen;    
	}
	exports.check_password = function(password,re){
		var s = trim($("#"+password).val());
		s = s.length;
		if (s<6 || s>15){
			$("#"+re).html("���벻��С��6λ����15λ");
		}else{
			$("#"+re).html("<img  src='/themes/ryr/images/answer_success.jpg'>");
		}
	}
	
	exports.check_confirm = function(password,re){
		if (trim($("#password").val())==''){
			$("#"+re).html("ȷ�����벻��Ϊ��");
		}else if(trim($("#password").val())!=$("#"+password).val()){
			$("#"+re).html("�������벻һ��");
		}else{
			$("#"+re).html("<img  src='/themes/ryr/images/answer_success.jpg'>");
		}
		
	}
	
	exports.check_phone = function(phone,re){
		if(isMobile(phone)){
			if(!re){
				return true;
			}
			$.ajax({
				type:"get",
				url:'/?user&q=check_phone',
				data:'&phone='+phone,
				success:function(result){
					if (result=="0"){
						msg = "�ֻ������Ѿ�����";
					}else{
						//msg = "<font color='red'>����ע��</font>";	
						msg = "<img  src='/themes/ryr/images/answer_success.jpg'>";	
					}
					$("#"+re).html(msg);
				},
				cache:false
			});
		}else{
			if(re){
				$("#"+re).html("�ֻ����벻��ȷ");
			}else{
				return false;
			}
			
		}
		return isMobile(phone);
	}	
	 exports.reg = function (){
		$('#username').live("blur",function(){
			exports.check_username("username","username_notice");	
		})
		$('#password').live("blur",function(){
			exports.check_password("password","password_notice");	
		}) 
		$('#confirm_password').live("blur",function(){
			exports.check_confirm("confirm_password","conform_password_notice");	
		})
		$('#phone').live("blur",function(){
			exports.check_phone($('#phone').val(),"phone_notice");	
		});
		$('#email').live("blur",function(){
			exports.check_email('email','email_notice');
		});
		$("#reg_form").live("submit",function(){
			var mail=trim($("#email").val()).split('@');
			require("submitform");			
			var msg = '';
			var alt = test_username($("#username").val());
			if(alt!=true){
				msg+=alt+'\n';
			}
			if(empty(trim($("#password").val()))){
				msg+='���벻��Ϊ��'+'\n';
			}
			if(empty(trim($("#confirm_password").val()))){
				msg+='ȷ�����벻��Ϊ��'+'\n';
			}	
			if(trim($("#password").val())!=trim($("#confirm_password").val())){
				msg+='�������벻һ��'+'\n';
			}

			if(!isMobile($("#phone").val())){
				msg+='��������ȷ���ֻ�����'+'\n';
			}
			var mail = trim($("#email").val());
			if(empty(mail)){
				msg+='�������������'+'\n';
			}
			var reg1 = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
			if(!reg1.test(mail)){
				msg+='��������ȷ�ĵ�������'+'\n';
			}
			if(empty(trim($("#phone_code").val()))){
				msg+='�������ֻ�������'+'\n';
			}
			if(msg!=''){
				alert(msg);
				return false;
			}
			if(is_rejest ==1){
				alert('���������ύ�У����Ժ�');
				return false;
			}
			is_rejest = 1;
			$('#submit').attr("disabled", true);
   			$("#reg_form").ajaxSubmit({
				 success: function (result, status) {
						
					 if(parseInt(result)==1){
						 alert("�𾴵��û�����ϲ������û�ע�ᣡ\n������ɰ�ȫ��Ϣ��֤�󣬼���Ͷ�ʡ�");
						 mail=mail.split('@');
						 is_rejest = 0;
						 $('#submit').attr("disabled", false);
						 window.location.href='http://mail.'+mail[1];
					 }else{
						is_rejest = 0;
						$('#submit').attr("disabled", false);
						alert(result);
					 }
					return false;
				}

			 });
			 return false; // cancel conventional submit
			
		 })
		 //������֤��
		 $("#phone_send").click(function(){
			var phone = $("#phone").val();
			phone = trim(phone);
			if (phone==""){
				alert('�ֻ����벻��Ϊ��');				
			}else{
				var phone_status = exports.check_phone(phone);
				if (phone_status){
					$.get("/?user&q=send_code",{ phone: phone},
						function (result){
							if (result==1){
								alert("�����Ѿ����͵�����ֻ�");
							}else{
								alert(result);								
							}
					})
				}else{					
					alert('�ֻ�������д����ȷ');					
				}
			}
		})
		
	 }
	
	
	 exports.info_vip = function (){
				$(".user_info_vip").live("click",function(){
													var con = "";
													var vip = "";
				 con = $(this).attr("data-account");
				text = eval("({"+con+"})");
					var balance = text.balance;
					 vip = text.vip;
					var account = text.account;
					if (account>balance){
						deayou.use("header",function(e){e.ajaxConfirm("�������㣬�Ƿ����Ͻ��г�ֵ","false","/?user&q=code/account/recharge_new");});
					}else{
						deayou.use("header",function(e){e.ajaxDialog("��ΪVIP��Ա","/?user&q=code/users/vip_new&vip="+vip+"&_time="+Math.random(1,9));});
					}
									  
				})
	 }
	 
	 
	exports.info_vip_new = function (){
		$("#user_info_vip_form").die();
		$("#user_info_vip_form").live("submit",function(){
			require("submitform");	
   			$("#user_info_vip_form").ajaxSubmit({
				 success: function (result, status) {
					 if(parseInt(result)>0){
						 deayou.use("header",function(e){e.ajaxYes("����VIP�ɹ�","/?user&q=code/users/vip_log");});
						
					 }else{
						 alert(result);
					 }
				}

			 });
			 return false; // cancel conventional submit
		 })
	
	}
});

function test_username(d){
	d = trim(d);
	if(empty(d)){
		return '����д�û���';
	}else{
		if(!/^[_a-z0-9]{3,16}$/i.test(d)){
			return '�û���ֻ����3-16λ��ĸ�����ֻ��»��߹���';
		} else {
			if(!/^[a-z]/i.test(d)){
				return  '�û���ֻ������ĸ��ͷ';
			} else {
				if(/_$/.test(d)){
					return 'Ϊ���������ס�û�����ĩβ��Ҫ���»���';
				}else{
					if(- 1 != d.indexOf("xx")){
						return '�û������ܰ���xx';
					}else{
						if(- 1 != d.indexOf("admin")){
							return '�û������ܰ���admin';
						}else{
							if(- 1 != d.indexOf("kf")){
								return '�û������ܰ���kf';
							}else{
								if(- 1 != d.indexOf("kefu")){
									return '�û������ܰ���kefu';
								}else{
									return true;
								}
							}
						}
					}
				}
			}
		}
	}
}