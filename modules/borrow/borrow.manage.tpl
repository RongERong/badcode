<form action="" method="post"> 
<div class="module_add">
	<div class="module_title"><strong>������</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >

			<td width="*" class="main_td">�����</td>
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">���ʽ</td>
			<td width="" class="main_td">����ʱ��</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">�鿴</td>
		</tr>
		{ list  module="borrow" function="GetList" var="loop" borrow_name="request"  borrow_nid="request" username="request"   status="request" status_nid="request" borrow_type="request" dotime1="request" dotime2="request" }
		{foreach from="$loop.list" item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.borrow_nid}</td>
			<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}" title="�鿴">{$item.username}</a></td>
			<td title="{$item.name}"><a href="{$_A.query_url}/view&borrow_nid={$item.borrow_nid}" title="�鿴">{$item.name|truncate:10}</a></td>
			<td>{$item.account}Ԫ</td>
			<td>{$item.borrow_apr}%</td>
			<td>{$item.borrow_period}{if $item.borrow_type=="day"}��{else}����{/if}</td>
			<td>{$item.type_name}</td>
			<td>{$item.style_title}</td>
			<td>{$item.addtime|date_format:"Y/m/d H:i"}</td>
			<td>{$item.borrow_status_nid|linkages:"borrow_status"}</td>
			<td title="{$item.name}"><a href="{$_A.query_url}/view&borrow_nid={$item.borrow_nid}">�鿴</a></td>
		</tr>
		{ /foreach}
		<tr>
		<td colspan="15" class="action">
		<div class="floatl">
			
		</div>
		<div class="floatr">
			 ���⣺<input type="text" name="borrow_name" id="borrow_name" value="{$magic.request.borrow_name|urldecode}" size="8"/> ����ţ�<input type="text" name="borrow_nid" id="borrow_nid" value="{$magic.request.borrow_nid}" size="8"/> 
             �û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" size="8"/>
			 ���֣�<select name="borrow_type" id="borrow_type">
             <option value="">ȫ��</option>
			 {loop module="borrow" plugins="Type" function="GetTypeList" limit="all" var="Tvar"}
			 <option value="{$Tvar.nid}" {if $Tvar.nid==$magic.request.borrow_type} selected=""{/if}>{$Tvar.name}</option>
		     {/loop}
			 </select>
			ʱ��Σ�<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>
			״̬��{linkages nid="borrow_status" plugins="module" type="value" name="status_nid" default="ȫ��" value="$magic.request.status_nid" }
			<input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/manage')">
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
</form>
<script>

var urls = '{$_A.query_url}/manage';
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
	var status_nid = $("#status_nid").val();
	if (status_nid!="" && status_nid!=null){
		sou += "&status_nid="+status_nid;
	}
	var is_vouch = $("#is_vouch").val();
	if (is_vouch!="" && is_vouch!=null){
		sou += "&is_vouch="+is_vouch;
	}
	location.href=url+sou;
}
</script>
{/literal}