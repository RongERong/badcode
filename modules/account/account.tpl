
{if $_A.sub_dir!=""}
	{include file="$_A.sub_dir" template_dir = "modules/account"}
{elseif $_A.query_type=="list"}
	{include file="account.list.tpl" template_dir = "modules/account"}
{elseif $_A.query_type=="log"}
	{include file="account.log.tpl" template_dir = "modules/account"}
{elseif $_A.query_type=="recharge"}
	{include file="account.recharge.tpl" template_dir = "modules/account"}
{elseif $_A.query_type=="cash"}
	{include file="account.cash.tpl" template_dir = "modules/account"}
{elseif $_A.query_type=="web" || $_A.query_type=="web_count"}
	{include file="account.web.tpl" template_dir = "modules/account"}
{elseif $_A.query_type=="users" || $_A.query_type=="users_count"}
	{include file="account.users.tpl" template_dir = "modules/account"}




{elseif $_A.query_type=="bank"}
<ul class="nav3"> 
<li><a href="{$_A.query_url}/bank" style="color:red">�û��˻���Ϣ</a></li> 
<!--<li><a href="{$_A.query_url}/bank&action=bank">�����˻��б�</a></li> 
<li><a href="{$_A.query_url}/bank&action=new">��������˻�</a></li>-->
</ul> 


{if $magic.request.action==""}

<div class="module_add">
	<div class="module_title"><strong>�û��˻���Ϣ</strong></div>
	<div style="margin-top:10px;">
	<div style="float:left; width:30%;">
		
	<div style="border:1px solid #CCCCCC; ">
	
	{if $magic.request.user_id==""}
	<form action="{$_A.query_url_all}" method="post">
	<div class="module_title"><strong>�����û�</strong>(����˳���������)<input type="hidden" name="type" value="user_id" /></div>
	
	
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username"  value="{$_A.user_result.username}"/>
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">�û�ID��</div>
		<div class="c">
			<input type="text" name="user_id" />
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">���䣺</div>
		<div class="c">
			<input type="text" name="email" />
		</div>
	</div>
	
	<div class="module_submit"><input type="submit" value="ȷ���ύ" class="submit_button" /></div>
		</form>
	</div>
	{else}
	
	<form action="{$_A.query_url_all}&user_id={$maigc.request.user_id}" method="post">
	<div class="module_title"><strong>�޸��û������˻�</strong></div>
	
	
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			{$_A.account_bank_result.username}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ʵ������</div>
		<div class="c">
			<a href="{$_A.admin_url}&q=code/approve/realname&user_id={$_A.account_bank_result.user_id}">{$_A.account_bank_result.realname|default:"δ��"}</a>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���ڵأ�</div>
		<div class="c">
			{areas type="p,c" value="$_A.account_bank_result.city"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�������У�</div>
		<div class="c">
		{linkages nid="account_bank" name="bank" value="$_A.account_bank_result.bank" type="value"}
			
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">֧�У�</div>
		<div class="c">
			<input type="text" name="branch" value="{$_A.account_bank_result.branch}" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����˻���</div>
		<div class="c">
			<input type="text" name="account"   value="{$_A.account_bank_result.account}"/>
		</div>
	</div>
	
	<div class="module_submit"><input type="hidden" name="type" value="update" />
	<input type="hidden" name="user_id" value="{$magic.request.user_id}" />
	<input type="hidden" name="id" value="{if $magic.request.id!=''}{$magic.request.id}{else}{$_A.account_bank_result.id}{/if}" />
	<input type="submit" value="ȷ���ύ" class="submit_button" /></div>
		</form>
	</div>
	
	{/if}
	</div>
		</div>
	<div style="float:right; width:67%; text-align:left">
	
	<div class="module_add">
		<div class="module_title"><strong>�û������˻��б�</strong></div>
	</div>
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		  <form action="" method="post">
			<tr >
				<td class="main_td">ID</td>
				<td class="main_td">�û���</td>
				<td class="main_td">��ʵ����</td>
				<td class="main_td">��������</td>
				<td class="main_td">���ڵ�</td>
				<td class="main_td">֧��</td>
				<td class="main_td">�����˻�</td>
				<td class="main_td">����</td>
			</tr>
			{ list module="account" function="GetUsersBankList" var="loop" username="request" realname="request"}
			{foreach from=$loop.list item="item"}
			<tr  {if $key%2==1} class="tr2"{/if}>
				<td >{ $item.id}</td>
				<td >{$item.username}</td>
				<td >{$item.realname}</td>
				<td >{$item.bank|linkages:"account_bank"|default:"$item.bank"}</td>
				<td >{$item.province|areas} {$item.city|areas}</td>
				<td >{$item.branch}</td>
				<td >{$item.account}</td>
				<td ><a href="{$_A.query_url}/bank&user_id={$item.user_id}&id={$item.id}">�޸�</a></td>
			</tr>
			{ /foreach}
			<tr>
			<td colspan="12" class="action">
			<div class="floatl">			
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>��ʵ����:<input type="text" name="realname" id="realname" value="{$magic.request.realname|urldecode}"/>
				<input type="button" value="����"  onclick="sousuo('{$_A.query_url}/bank')"/>
			</div>
			</td>
		</tr>
			<tr>
				<td colspan="9" class="page">
				{$loop.pages|showpage} 
				</td>
			</tr>
			{/list}
		</form>	
	</table>
</div>
{elseif $magic.request.action=="bank"}
	<div class="module_add">
		<div class="module_title"><strong>{$MsgInfo.account_name_bank_list}</strong></div>
	</div>
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		  <form action="" method="post">
			<tr >
				<td class="main_td">ID</td>
				<td class="main_td">{$MsgInfo.account_name_bank_name}</td>
				<td class="main_td">{$MsgInfo.account_name_bank_status}</td>
				<td class="main_td">{$MsgInfo.account_name_bank_nid}</td>
				<td class="main_td">{$MsgInfo.account_name_bank_litpic}</td>
				<td class="main_td">{$MsgInfo.account_name_bank_cash_money}</td>
				<td class="main_td">{$MsgInfo.account_name_bank_reach_day}</td>
				<td class="main_td">{$MsgInfo.account_name_bank_manage}</td>
			</tr>
			{ list module="account" function="GetBankList" var="loop" keywords="request"}
			{foreach from=$loop.list item="item"}
			<tr  {if $key%2==1} class="tr2"{/if}>
				<td >{ $item.id}</td>
				<td >{$item.name}</td>
				<td >{$item.status|linkages:"account_bank_status"}</td>
				<td >{$item.nid}</td>
				<td >{$item.litpic}</td>
				<td >{$item.cash_money}</td>
				<td >{$item.reach_day}</td>
				<td ><a href="{$_A.query_url}/bank&action=edit&id={$item.id}">{$MsgInfo.linkages_name_edit}</a>  <a href="#" onClick="javascript:if(confirm('{$MsgInfo.account_name_bank_del_msg}')) location.href='{$_A.query_url}/bank&action=del&id={$item.id}'">{$MsgInfo.linkages_name_del}</a></td>
			</tr>
			{ /foreach}
			<tr>
			<td colspan="12" class="action">
			<div class="floatl">
			
			</div>
			<div class="floatr">
				���ƣ�<input type="text" name="keywords" id="keywords" value="{$magic.request.keywords}"/> <input type="button" value="����" / onclick="sousuo()">
			</div>
			</td>
		</tr>
			<tr>
				<td colspan="9" class="page">
				{$loop.pages|showpage} 
				</td>
			</tr>
			{/list}
		</form>	
	</table>

<!--��ӳ�ֵ��¼ ��ʼ-->
{elseif $magic.request.action == "new" || $magic.request.action == "edit"}

<div class="module_add">
	
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>{$MsgInfo.account_name_bank_new}</strong></div>

	<div class="module_border">
		<div class="l">{$MsgInfo.account_name_bank_name}��</div>
		<div class="c">
			<input type="text" name="name" value="{$_A.account_bank_result.name}" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">{$MsgInfo.account_name_bank_status}��</div>
		<div class="c">
			{input name="status" type="radio" value="1|����,0|�ر�" checked="$_A.account_bank_result.status"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">{$MsgInfo.account_name_bank_nid}��</div>
		<div class="c">
			<input type="text" name="nid"  value="{$_A.account_bank_result.nid}"/>
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">{$MsgInfo.account_name_bank_litpic}��</div>
		<div class="c">
			<input type="text" name="litpic"  value="{$_A.account_bank_result.litpic}"/>
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">{$MsgInfo.account_name_bank_cash_money}��</div>
		<div class="c">
			<input type="text" name="cash_money"  value="{$_A.account_bank_result.cash_money}"/>
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">{$MsgInfo.account_name_bank_reach_day}��</div>
		<div class="c">
			<input type="text" name="reach_day"  value="{$_A.account_bank_result.reach_day}"/>
		</div>
	</div>
	
	<div class="module_submit" >
		
		<input type="submit"  name="reset" value="{$MsgInfo.account_name_submit}" />
	</div>
</form>
</div>
{/if}
<!--��ӳ�ֵ��¼ ����-->

<!--���ּ�¼�б� ��ʼ-->
<!--��ӳ�ֵ��¼ ��ʼ-->
{elseif $_A.query_type == "recharge_new"}

<div class="module_add">
	
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>��ӳ�ֵ</strong></div>

	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
			���³�ֵ<input type="hidden" name="type" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��</div>
		<div class="c">
			<input type="text" name="money" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
			<input type="text" name="remark" />
		</div>
	</div>
	
	<div class="module_submit" >
		
		<input type="submit"  name="reset" value="ȷ�ϳ�ֵ" />
	</div>
</form>
	<form name="form1" method="post" action="?dyryr&q=code/account/batch_recharge_new" enctype="multipart/form-data" >
	<div class="module_title"><strong>������ӳ�ֵ</strong></div>
	<div class="module_border">������ӣ�����ʹ�õ��ӱ��</div>
	<input type="file" name="file">
	<div class="module_submit" >
		<input type="submit"  name="reset" value="��ʼ������" />
	</div>
</form>
</div>

<!--��ӳ�ֵ��¼ ����-->




<!--��ӳ�ֵ��¼ ��ʼ-->
{elseif $_A.query_type == "deduct"}

<div class="module_add">
	
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>���ÿ۳�</strong></div>

	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
			{linkages name="type" type="value" nid="account_deduct_type"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��</div>
		<div class="c">
			<input type="text" name="money" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
			<input type="text" name="remark" />���磬�ֳ����ÿ۳�200Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">��֤�룺</div>
		<div class="c"><input  class="user_aciton_input"  name="valicode" type="text" size="8" maxlength="4" style=" padding-top:4px; height:16px; width:70px;"/>&nbsp;<img src="/?plugins&q=imgcode" alt="���ˢ��" onClick="this.src='/?plugins&q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	<div class="module_submit" >
		
		<input type="submit"  name="reset" value="ȷ���۳�" />
	</div>
</form>
</div>

<!--��ӳ�ֵ��¼ ����-->
{elseif $_A.query_type == "payment"}
	{include file="account.payment.tpl" template_dir="modules/account"}
{/if}
<script>
var url = '{$_A.query_url}/{$_A.query_type}';
{literal}
function sousuo(){
	var sou = "";
	
	
	 if ($("#email")[0]){
		var email = $("#email").val();
		if (email!=""){
			sou += "&email="+email;
		}
	}
	if ($("#status")[0]){
		var status = $("#status").val();
		if (status!="" && status!=null){
			sou += "&status="+status;
		}
	}
	var dotime1 = $("#dotime1").val();
	var keywords = $("#keywords").val();
	var dotime2 = $("#dotime2").val();
	var type = $("#type").val();
	var username = $("#username").val();
	var realname = $("#realname").val();
	var nid = $("#nid").val();
	if (username!=null){
		sou += "&username="+username;
	}
	if (realname!=null){
		sou += "&realname="+realname;
	}
	
	if (keywords!=null){
		 sou += "&keywords="+keywords;
	}
	if (dotime1!=null){
		 sou += "&dotime1="+dotime1;
	}
	if (dotime2!=null){
		 sou += "&dotime2="+dotime2;
	}
	if (type!=null){
		 sou += "&type="+type;
	}
	if (nid!=null){
		 sou += "&nid="+nid;
	}
	if (sou!=""){
	location.href=url+sou;
	}
}

</script>
{/literal}