<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if  $magic.request.recover_status==""} id="c_so"{/if}>ȫ���տ�</a></li> 
<li><a href="{$_A.query_url_all}&recover_status=1" {if  $magic.request.recover_status=="1"} id="c_so"{/if}>���տ�</a></li> 
</ul> 
<div class="module_add">
	<div class="module_title"><strong>�տ��б�</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="*" class="main_td">�տ���</td>
			<td width="*" class="main_td">�����</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">���Ϣ</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">Ӧ��ʱ��</td>
            {if $magic.request.recover_status==1}
			<td width="" class="main_td">ʵ��ʱ��</td>
			<td width="" class="main_td" title="ʵ�ձ���+ʵ����Ϣ+��ǰ���Ϣ+���ڷ�Ϣ">ʵ���ܶ�</td>
            {/if}
			<td width="" class="main_td">״̬</td>
		</tr>
		{list module="borrow" function="GetRecoverList" plugins="recover" var="loop" borrow_name="request" username="request" borrow_nid="request" recover_status="request" dotime1=request dotime2=request}
		{foreach from="$loop.list" item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}" title="�鿴">{$item.username}</a></td>
			<td>{$item.borrow_nid}</td>
			<td title="{$item.name}"><a href="{$_A.query_url}/view&borrow_nid={$item.borrow_nid}" title="�鿴">{$item.borrow_name}</a>(��{$item.repay_period+1}��)</td>
			<td>{$item.borrow_type|linkages:"borrow_all_type"|default:"$item.borrow_type"}</td>
			<td>{$item.recover_account}Ԫ</td>
			<td>{$item.late_days}��</td>
			<td>{$item.recover_time|date_format:"Y-m-d"}</td>
            {if $magic.request.recover_status==1}
			<td width="" class="main_td">{$item.recover_yestime|date_format:"Y-m-d"}</td>
			<td width="" class="main_td" >��{$item.recover_account_yes}</td>
            {/if}
			<td>{if $item.recover_status==1}<font color="#ff0000">���տ�</font>{else}���տ�{/if}</td>
		</tr>
		{ /foreach}
		<tr>
		<td colspan="14" class="action">
		<div class="floatl">
			<a href="{$_A.query_url_all}&export=excel&page={$magic.request.page|default:1}&username={$magic.request.username|urldecode}&borrow_name={$magic.request.borrow_name|urldecode}&borrow_nid={$magic.request.borrow_nid}&borrow_type={$magic.request.borrow_type}&recover_status={$magic.request.recover_status}&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}">������ǰ</a>
            <a href="{$_A.query_url_all}&export=excel&username={$magic.request.username|urldecode}&borrow_name={$magic.request.borrow_name|urldecode}&borrow_nid={$magic.request.borrow_nid}&borrow_type={$magic.request.borrow_type}&recover_status={$magic.request.recover_status}&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}">����ȫ��</a>
		</div>
		<div class="floatr">
			 ���⣺<input type="text" name="borrow_name" id="borrow_name" value="{$magic.request.borrow_name|urldecode}" size="8"/> �û�����<input type="text" name="username" id="username" value="{$magic.request.username}" size="8"/>����ţ�<input type="text" name="borrow_nid" id="borrow_nid" value="{$magic.request.borrow_nid}" size="8"/> 
			
			{linkages name="borrow_type" nid="borrow_all_type" type="value" default="ȫ��" value="$magic.request.borrow_type"}
            <select name="recover_status" id="recover_status">
            <option value="">ȫ��</option>
            <option value="1" {if $magic.request.recover_status==1} selected=""{/if}>����</option>
            <option value="2" {if $magic.request.recover_status==2} selected=""{/if}>δ��</option>
            </select>
            Ӧ��ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>
			 <input type="button" value="����" class="submit" onclick="sousuo('')">
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

var urls = '{$_A.query_url}/recover';
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
	var borrow_type = $("#borrow_type").val();
	if (borrow_type!="" && borrow_type!=null){
		sou += "&borrow_type="+borrow_type;
	}
	var dotime1 = $("#dotime1").val();
	if (dotime1!="" && dotime1!=null){
		sou += "&dotime1="+dotime1;
	}
	var dotime2 = $("#dotime2").val();
	if (dotime2!="" && dotime2!=null){
		sou += "&dotime2="+dotime2;
	}
	var status = $("#recover_status").val();
	if (status!="" && status!=null){
		sou += "&recover_status="+status;
	}
	var is_vouch = $("#is_vouch").val();
	if (is_vouch!="" && is_vouch!=null){
		sou += "&is_vouch="+is_vouch;
	}

		location.href=urls+sou;
	
}
</script>
{/literal}