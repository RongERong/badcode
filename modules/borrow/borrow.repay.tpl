<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if  $magic.request.repay_status==""} id="c_so"{/if}>ȫ������</a></li> 
<li><a href="{$_A.query_url_all}&repay_status=1" {if  $magic.request.repay_status=="1"} id="c_so"{/if}>�ѻ���</a></li> 
<li><a href="{$_A.query_url_all}&repay_status=0" {if  $magic.request.repay_status=="0"} id="c_so"{/if}>δ����</a></li> 
</ul> 
<div class="module_add">
	<div class="module_title"><strong>�����б�</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
		    <td width="*" class="main_td">�����</td>
			<td width="*" class="main_td">�����</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">Ӧ��ʱ��</td>
			<td width="" class="main_td">Ӧ����Ϣ</td>
			{if $magic.request.repay_status==1}
			<td width="" class="main_td">ʵ��ʱ��</td>
			<td width="" class="main_td">ʵ���ܶ�</td>
			{/if}
            <!--
			{if $magic.request.type=="yes" || $magic.request.type==""}
			<td width="" class="main_td">Ӧ�����ڷ�Ϣ</td>
			<td width="" class="main_td">Ӧ�ɹ����</td>
			<td width="" class="main_td">��ǰ�����</td>
			<td width="" class="main_td">ʵ�����</td>
			<td width="" class="main_td">����ʱ��</td>
			{elseif $magic.request.type=="wait"}
			<td width="" class="main_td">���ڷ�Ϣ</td>
			<td width="" class="main_td">Ӧ�������</td>
			<td width="" class="main_td">Ӧ���ܶ�</td>
			{/if}
            -->
			<td width="" class="main_td">״̬</td>
		</tr>
		{list module="borrow" plugins="loan" function="GetRepayList" var="loop" borrow_name="request" username="request" borrow_nid="request" is_vouch="request" borrow_type="request" order="status" repay_status="request" repay_type="request"}
		{foreach from="$loop.list" item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.borrow_nid}</td>
			<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}" title="�鿴">{$item.borrow_username}</a></td>
			<td title="{$item.name}"><a href="{$_A.query_url}/view&borrow_nid={$item.borrow_nid}" title="�鿴">{$item.borrow_name}</a></td>
			<td>��{$item.repay_period}��</td>
			<td>{$item.type_title}</td>
			<td>{$item.repay_time|date_format:"Y-m-d"}</td>
			<td>{$item.repay_account}Ԫ</td>
			{if $magic.request.repay_status==1}
			<td>{$item.repay_yestime|date_format:"Y-m-d"}</td>
			<td title="ʵ������[{$item.repay_capital_yes}]+ʵ����Ϣ[{$item.repay_interest_yes}]+�������[{$item.repay_fee}]">��{$item.repay_account_yes+$item.repay_fee}</td>
			{/if}
			<td>{$item.repay_type_name}</td>
		</tr>
		{ /foreach}
		<tr>
		<td colspan="20" class="action">
		<div class="floatl">
			
		</div>
		<div class="floatr">
			���⣺<input type="text" name="borrow_name" id="borrow_name" value="{$magic.request.borrow_name|urldecode}" size="8"/> �û�����<input type="text" name="username" id="username" value="{$magic.request.username}" size="8"/>����ţ�<input type="text" name="borrow_nid" id="borrow_nid" value="{$magic.request.borrow_nid}" size="8"/> 
			<!--
			<select id="is_vouch" ><option value="">ȫ��</option><option value="1" {if $magic.request.is_vouch==1} selected="selected"{/if}>������</option><option value="0" {if $magic.request.is_vouch=="0"} selected="selected"{/if}>��ͨ��</option></select> 
			-->
			{linkages name="borrow_type" nid="borrow_all_type" type="value" default="ȫ��" value="$magic.request.borrow_type"}
			{if $magic.request.repay_status==1}
			״̬��<select name="repay_type" id="repay_type"><option value="" {if $magic.request.repay_type==""}selected="selected"{/if}>����</option><option value="yes" {if $magic.request.repay_type=="yes"}selected="selected"{/if}>��������</option><option value="advance" {if $magic.request.repay_type=="advance"}selected="selected"{/if}>��ǰ����</option><option value="late" {if $magic.request.repay_type=="late"}selected="selected"{/if}>���ڻ���</option></select>
			{else}
			״̬��<select name="repay_status" id="repay_status"><option value="" {if $magic.request.repay_status==""}selected="selected"{/if}>����</option><option value="1" {if $magic.request.repay_status==1}selected="selected"{/if}>�ѻ�</option><option value="0" {if $magic.request.repay_status=="0"}selected="selected"{/if}>δ��</option></select>
			{/if}
			<input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/repay&repay_status={$magic.request.repay_status}')">
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="14" class="page">
			{$loop.pages|showpage}
			</td>
		</tr>
		{/list}
	</form>	
</table>


<script>

var urls = '{$_A.query_url}/repay';
{literal}
function sousuo(url){

	var sou = "";
	var username = $("#username").val();
	if (username!="" && username!=null){
		sou += "&username="+username;
	}
	var keywords = $("#keywords").val();
	if (keywords!="" && keywords!=null){
		sou += "&keywords="+keywords;
	}
	var borrow_name = $("#borrow_name").val();
	if (borrow_name!="" && borrow_name!=null){
		sou += "&borrow_name="+borrow_name;
	}
	var borrow_nid = $("#borrow_nid").val();
	if (borrow_nid!="" && borrow_nid!=null){
		sou += "&borrow_nid="+borrow_nid;
	}
	var dotime1 = $("#dotime1").val();
	if (dotime1!="" && dotime1!=null){
		sou += "&dotime1="+dotime1;
	}
	var repay_type = $("#repay_type").val();
	if (repay_type!="" && repay_type!=null){
		sou += "&repay_type="+repay_type;
	}
	var borrow_type = $("#borrow_type").val();
	if (borrow_type!="" && borrow_type!=null){
		sou += "&borrow_type="+borrow_type;
	}
	var dotime2 = $("#dotime2").val();
	if (dotime2!="" && dotime2!=null){
		sou += "&dotime2="+dotime2;
	}
	var status = $("#status").val();
	if (status!="" && status!=null){
		sou += "&status="+status;
	}
	var repay_status = $("#repay_status").val();
	if (repay_status!="" && repay_status!=null){
		sou += "&repay_status="+repay_status;
	}
	var is_vouch = $("#is_vouch").val();
	if (is_vouch!="" && is_vouch!=null){
		sou += "&is_vouch="+is_vouch;
	}
	if (url==""){
		location.href=urls+sou;
	}else{
		location.href=url+sou;
	}
	
}
</script>
{/literal}