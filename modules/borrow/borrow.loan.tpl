<div class="module_add">
<form action="{$_A.query_url}/loan" method="post" enctype="multipart/form-data">	
	<div class="module_title"><strong>�������</strong><font style="color:red">����ܰ��ʾ���������ʱ�����Ȳ鿴�˽�����Ƿ��н���ȣ���û�У���������<a href="/?dyryr&q=code/borrow/amount">���</a>����Ѻ��=��Ѻ��ȣ���ת��=���Ŷ�ȣ�</font></div>
	<div class="module_border">
		<div class="l">����ˣ�</div>
		<div class="c">
		<input type="text" name="username" id="username" onblur="checkusername()" style="width:200px;  height:18px;">
		<input type="hidden" name="borrow_type" value="{$magic.request.type_nid}" />	<span class="warning" style="color:#FF0000" id="username_notice"></span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����⣺</div>
		<div class="c">
		<input type="text" name="name" id="name" style="width:200px;  height:18px;">
		</div>
	</div>	
	<div class="module_border">
		<div class="l">����</div>
		<div class="c">
		<input type="text" name="account" id="account" style="width:100px;" onkeyup="value=value.replace(/[^0-9.]/g,'')" />Ԫ�������{$_A.borrow_type_result.amount_first}Ԫ-{$_A.borrow_type_result.amount_end}Ԫ{if $_A.borrow_type_result.account_multiple>0}����Ϊ{$_A.borrow_type_result.account_multiple}�ı���{/if}�� 
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ޣ�</div>
		<div class="c">
		{if $_A.borrow_type_result.period_first==$_A.borrow_type_result.period_end}
		<input type="hidden" name="borrow_period"  id="borrow_period"  value="{$_A.borrow_type_result.period_first}" />{$_A.borrow_type_result.period_first}����
		{else}
		<select name="borrow_period">
		{foreach from=$_A.borrow_type_result.period_result key=key item=item}<option value='{$item.value}'>{$item.name}</option>{/foreach}
		</select>
		{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ʣ�</div>
		<div class="c">
		<input type="text" name="borrow_apr" id="borrow_apr" style="width:50px; border:#BFBFBF solid 1px; height:18px;" onkeyup="value=value.replace(/[^0-9.]/g,'')" />% 
	
		�����ʾ�ȷ��С�������λ����Χ{$_A.borrow_type_result.apr_first}%-{$_A.borrow_type_result.apr_end}%֮�䣩<font style="color:#999999">һ����˵�������Խ�ߣ�����ٶ�Խ�졣</font>
		</div>
	</div>	
	<div class="module_border">
		<div class="l">���ʽ��</div>
		<div class="c">
		<select name='borrow_style' >{foreach from=$_A.borrow_type_result.style_result key=key item=item}<option value='{$item.nid}'>{$item.name}</option>{/foreach}</select>
		</div>
	</div>	
	{if $magic.request.type_nid!="roam"}
	<div class="module_border" >
		<div class="l">����������</div>
		<div class="c">
	<input type="text" name="pawnins"  id="pawnins" value=""/>
	</div>	
	<div class="module_border">
		<div class="l">��Ч�ڣ�</div>
		<div class="c">
		<select name='borrow_valid_time' >{foreach from=$_A.borrow_type_result.validate_result key=key item=item}<option value='{$item.value}'>{$item.name}</option>{/foreach}</select>
		</div>
	</div>	
	<div class="module_border">
		<div class="l">���Ͷ���</div>
		<div class="c">
		<select name='tender_account_min' >{foreach from=$_A.borrow_type_result.tender_account_min_result key=key item=item}<option value='{$item}'>{if $item==0}����{else}{$item}Ԫ{/if}</option>{/foreach}</select>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���Ͷ���ܶ</div>
		<div class="c">
		<select name='tender_account_max' >{foreach from=$_A.borrow_type_result.tender_account_max_result key=key item=item}<option value='{$item}'>{if $item==0}����{else}{$item}Ԫ{/if}</option>{/foreach}</select>
		</div>
	</div>
	
	{/if}	
	
	{if $magic.request.type_nid=="roam"}
	<div class="module_border" >
		<div class="l">��С��ת��λ��</div>
		<div class="c">
	<input type="text" name="account_min" onblur="roamnum()"  id="account_min" value="" onkeyup="value=value.replace(/[^0-9]/g,'')" />Ԫ����ת����: <span id=roam_num>0</span> �ݣ�
	</div>
	<div class="module_border" >
		<div class="l">����������</div>
		<div class="c">
	<input type="text" name="voucher"  id="voucher" value=""/>
	</div>
	<div class="module_border" >
		<div class="l">��������ʽ��</div>
		<div class="c">
	<input type="text" name="vouch_style"  id="vouch_style" value=""/>
	</div>
	<div class="module_border" >
		<div class="l">����ҵ������</div>
		<div class="c">
	<textarea id="borrow_contents" name="borrow_contents"  style="width:530px;height:200px;"></textarea>
	</div>
	<div class="module_border" >
		<div class="l">���ʲ������</div>
		<div class="c">
	<textarea id="borrow_account" name="borrow_account"  style="width:530px;height:200px;"></textarea>	
	</div>
	<div class="module_border" >
		<div class="l">���ʽ���;��</div>
		<div class="c">
	<textarea id="borrow_account_use" name="borrow_account_use"  style="width:530px;height:200px;"></textarea>	
	</div>
	<div class="module_border" >
		<div class="l">���տ��ƴ�ʩ��</div>
		<div class="c">
	 <textarea id="risk" name="risk"  style="width:530px;height:200px;"></textarea>	
	</div>
	{else}
	<div class="module_border" >
		<div class="l">������飺</div>
		<div class="c">
	 <textarea rows="5" cols="70" name="borrow_contents"></textarea>
	</div>
	{/if}
	<div class="module_border" >
		<div class="l">��֤�룺</div>
		<div class="c">
	 <input name="valicode" type="text" class="in_text" style="width:50px;"/><img src="/?plugins&q=imgcode" id="valicode" alt="���ˢ��" onClick="this.src='/?plugins&q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" /><em>�����壿<a href="javascript:void(0)" onClick="$('#valicode').attr('src','/?plugins&q=imgcode&t=' + Math.random());">��һ�ţ�</a>
	</div>
	<div class="module_border" >
		<div class="l">&nbsp;</div>
		<div class="c">
	 <input type="submit" value="�ύ" /><input type="hidden" name="status" value="1" />
	</div>	
	</form>	
	</div>
	{literal}
<script>

function checkusername(){
	var username = $("#username").val();
	if(username!=''){
		$.ajax({
				type:"get",
				url:'/?user&q=login',
				data:'&q=check_username&username='+username,
				success:function(result){
					if (result=="1"){
						msg = "";	
					}else{
						msg = "<font color='red'>�û���������</font>";	
					}
					$("#username_notice").html(msg);
				},
				cache:false
			});
		
	}else{
		$("#username_notice").html();
	}
}

function roamnum(){
	var account = $("#account").val();
	var account_min = $("#account_min").val();
	if(account_min!=''){
		var num = account/account_min;	
		$("#roam_num").html(Math.floor(num));
	}else{
		$("#roam_num").html(0);
	}
}
</script>
{/literal}
	