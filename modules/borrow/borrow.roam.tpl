<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if $magic.request.status_nid==""}  style="color:red"{/if}>ȫ����ת��</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=first" title="ָ�ύ��������" {if $magic.request.status_nid=="first"}  style="color:red"{/if}>����</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=now" title="ָ������ת" {if $magic.request.status_nid=="now"}  style="color:red"{/if}>������ת</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=recover" title="ָ��ת�굫��δ�ع���" {if $magic.request.status_nid=="recover"}  style="color:red"{/if}>�ع���</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=over" title="ָ�ѻع���" {if $magic.request.status_nid=="over"}  style="color:red"{/if}>�ѻع����</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=false" title="ָ�ύ���벻��ͨ��" {if $magic.request.status_nid=="false"}  style="color:red"{/if}>ʧ�ܽ��</a></li> 
</ul>
<form action="" method="post"> 
<div class="module_add">
	<div class="module_title"><strong>����б�</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">�����</td>
			<td width="*" class="main_td">�û���</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">��ת���</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">�ع�����</td>
			<td width="" class="main_td">��С��λ</td>
			<td width="" class="main_td">����ת����</td>
			{if $magic.request.status!="1"}
			<td width="" class="main_td">����ת����</td>
			{/if}
			<td width="" class="main_td">����ת����</td>
			<td width="" class="main_td">�ѻع�����</td>
			<td width="" class="main_td">���ʱ��</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">�鿴</td>
		</tr>
		{list module="borrow" plugins="roam" function="GetList" dotime1="request" dotime2="request" username="request" name="$magic.request.borrow_name" status_nid="request" borrow_nid="request" var="loop"}
		{foreach from="$loop.list" item="item"}
		<tr>
			<td>{$item.borrow_nid}</td>
			<td title="�鿴"><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}">{$item.username}</a></td>
			<td><a href="{$_A.admin_url}&q=code/borrow/view&borrow_nid={$item.borrow_nid}">{$item.name}</td>
			<td>��{$item.account}</td>
			<td>{$item.borrow_apr}%</td>
			<td>{$item.borrow_period}����</td>
			<td>��{$item.account_min}</td>
			<td>{$item.portion_total}��</td>
			{if $magic.request.status!="1"}
			<td>{$item.portion_yes}��</td>
			{/if}
			<td>{$item.portion_wait}��</td>
			<td>{$item.recover_yes}��</td>
			<td>{$item.addtime|date_format}</td>
			<td>{$item.status_name}</td>
			<td><a href="{$_A.query_url}/view&type=roam&borrow_nid={$item.borrow_nid}">�鿴</a> {if $magic.request.status_nid=="first"} <a href="{$_A.query_url}/first&first_edit={$item.borrow_nid}">�޸�</a>{/if}
			 {if $item.portion_yes==0} - <a href="javascript:void(0)" onclick='tipsWindown("ȷ���Ƿ񳷱�","url:get?{$_A.query_url}/first&cancel_nid={$item.borrow_nid}",500,200,"true","","false","text");' title="�����Ͷ�����ʽ𽫷������˱�������"/>����</a>            
            {/if}
			</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="20" class="action">
		<div class="floatl">
			
		</div>
		<div class="floatr">
			 ���⣺<input type="text" name="borrow_name" id="borrow_name" value="{$magic.request.borrow_name}" size="8"/>
			 ����ţ�<input type="text" name="borrow_nid" id="borrow_nid" value="{$magic.request.borrow_nid}" size="8"/>�û�����<input type="text" name="username" id="username" value="{$magic.request.username}" size="8"/>
			 ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime(this.value)"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime(this.value)"/>
			
			<input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/roam&status_nid={$magic.request.status_nid}')">
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

var urls = '{$_A.query_url}/roam';
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