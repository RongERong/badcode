<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if $magic.request.status==""}id="c_so"{/if}>ȫ��</a></li> 
<li><a href="{$_A.query_url_all}&status=-1" {if $magic.request.status=="-1"}id="c_so"{/if}>�����</a></li> 
<li><a href="{$_A.query_url_all}&status=1" {if $magic.request.status=="1"}id="c_so"{/if}>����</a></li>
<li><a href="{$_A.query_url_all}&status=2" {if $magic.request.status=="2"}id="c_so"{/if}>���ʧ��</a></li>
<li><a href="javascript:addvip();" title="����VIP��Ա">����VIP��Ա</a></li>
</ul> 
{if $magic.request.action == ""  }
<div class="module_add">
	<div class="module_title"><strong>VIP��Ա�б�</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="*" class="main_td">�û���</td>
        {if $magic.request.status==1}
		<td width="*" class="main_td">�ͷ�����</td>
        {/if}
		<td width="*" class="main_td">vip����</td>
		<td width="*" class="main_td">��ʼʱ��</td>
		<th width="" class="main_td">����ʱ��</th>
		<th width="" class="main_td">״̬</th>
		<th width="" class="main_td">�Ƿ�ɷ�</th>
		<td width="" class="main_td">����</td>
	</tr>
	{list module="users" plugins="vip" function="GetUsersVipList" var="loop" status='request'  username='request'  adminname='request' }
	{ foreach  from=$loop.list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
        {if $magic.request.status==1}
		<td class="main_td1" align="center">{$item.adminname}</td>
        {/if}
		<td class="main_td1" align="center">{$item.years}��</td>
		<td class="main_td1" align="center" >{$item.first_date|date_format:"Y-m-d"|default:"-"}</td>
		<td class="main_td1" align="center" >{$item.end_date|date_format:"Y-m-d"|default:"-"}</td>
		<td class="main_td1" align="center">{if $item.status==-1}�����{elseif $item.status==2}��ͨ��{elseif $item.status==0}δ����{else}VIP��Ա{/if}</td>
		<td class="main_td1" align="center">{if $item.money>0}{$item.money}Ԫ{else}��{/if}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/vip&action=view&user_id={$item.user_id}{$_A.site_url}">��˲鿴</a> </td>
	</tr>
	{ /foreach}
	<tr>
			<td colspan="10" class="action">
			<div class="floatl">
			<script>
	  var url = '{$_A.query_url}/vip&type={$magic.request.type}';
	    {literal}
	  	function sousuo(){
			var username = $("#username").val();
			var adminname = $("#adminname").val();
			var status = $("#status").val();
			location.href=url+"&username="+username+"&adminname="+adminname+"&status="+status;
		}
	  
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username}"/> 	�ͷ��û�����<input type="text" name="adminname" id="adminname" value="{$magic.request.adminname|urldecode}"/>	״̬��<select name="status" id="status"><option value="">ȫ��</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>δ����</option><option value="1"  {if $magic.request.status==1} selected="selected"{/if}>���ͨ��</option><option value="2"  {if $magic.request.status==2} selected="selected"{/if}>��˲�ͨ��</option><option value="-1"  {if $magic.request.status==-1} selected="selected"{/if}>�����</option></select><input type="button" value="����" / onclick="sousuo()">
			</div>
			</td>
		</tr>
	<tr>
		<td colspan="10" class="page">
		{$loop.pages|showpage}
		</td>
	</tr>
	{/list}
</table>
<div  style="height:205px; overflow:hidden;display:none;" id="addvip">
	<div class="module_border_ajax">
		<div class="l">�û���:</div>
		<div class="c">
		<input type="text" id="uname" name="uname">
	</div>
	<div class="module_border_ajax" >
		<div class="l">��֤��:</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4"  tabindex="3" id="valicode" />
		</div>
		<div class="c">
			<img src="/?plugins&q=imgcode" id="valicode1" alt="���ˢ��" onClick="this.src='/?plugins&q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>

	<div class="module_submit_ajax" >
		<input type="button"  name="reset" class="submit_button" value="ȷ�����" onclick="doaddvip()"/>
	</div>
</div>
{literal}
<script>
function addvip(){
	tipsWindown('����vip','id:addvip',"280","120","true","","1","");
}
function doaddvip(){
	var uname = $('#windown-content #uname').val();
	if(!uname){
		alert('�������û���');
		return false;
	}
	var valicode = $('#windown-content #valicode').val();
	if(!valicode){
		alert('��������֤��');
		return false;
	}
	$.post('/?dyryr&q=code/users/vip&action=add',{uname:uname,valicode:valicode},function(data){
		alert(data);
	});
}
</script>
{/literal}
{ elseif $magic.request.action == "view"  }
<div class="module_add">
	
	<form enctype="multipart/form-data" name="form1" method="post" action=""  >
	<div class="module_title"><strong>VIP��˲鿴</strong></div>
	
	<div class="module_border">
		<div class="l">�û���:</div>
		<div class="c">
			{$_A.vip_result.username}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���:</div>
		<div class="c">{if $_A.vip_result.status=="1"}
		��ͨ��<input type="hidden" value="1" name="status" />
		{else}
			<input type="radio" value="1" name="status" {if $_A.vip_result.status=="1"} checked="checked"{/if} />���ͨ�� <input type="radio" value="2" name="status"  {if $_A.vip_result.status=="2" || $_A.vip_result.status==""} checked="checked"{/if}/>��˲�ͨ�� 
			{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�ͷ�:</div>
		<div class="c">
			<select name="kefu_userid" id="kefu_userid">
				<option value="">��ѡ��</option>
				{loop module="users" function="GetUsersAdminList" limit="all" type_id="9"}
				<option value="{$var.user_id}" {if $var.user_id==$_A.vip_result.kefu_userid} selected="selected"{/if}>{$var.adminname}</option>
				{/loop}
				</select>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��������:</div>
		<div class="c">
			1��
			<input type="hidden" value="1" name="years" value="{$_A.vip_result.years}" />
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="verify_remark" cols="55" rows="6" >{$_A.vip_result.verify_remark}</textarea>
		</div>
	</div>
	
	<div class="module_submit" >
	<input type="hidden" name="user_id" value="{$_A.vip_result.user_id}" />
		<input type="submit" value="ȷ���ύ" />
		<input type="reset" name="reset" value="���ñ�" />
	</div>
	</form>
{/if}