<div class="module_add">
	<div class="module_title"><strong>�������</strong></div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">�û���</td>
		<td width="*" class="main_td">ʵ����֤</td>
		<td width="*" class="main_td">ѧ����֤</td>
		<td width="*" class="main_td">�ֻ���֤</td>
		<td width="*" class="main_td">��Ƶ��֤</td>	
	</tr>
	{ list module="approve" function="GetAllList" var="loop" epage=20 page=request username=request phone=request status=request }
	{foreach from="$loop.list" item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.user_id}</td>
		<td class="main_td1" align="center">{$item.username|default:"-"}</td>
		<td class="main_td1" align="center">{if $item.realname_status.realname!=""}{$item.realname_status.status|linkages:"approve_status"|default:"�����"}{if $item.realname_status.status!=1}[<a href="?dyjsd&q=code/approve/realname&username={$item.username}"/>���</a>]{/if}{else}-{/if}</td>
		<td class="main_td1" align="center">{if $item.edu_status.graduate!=""}{$item.edu_status.status|linkages:"approve_status"|default:"�����"}{else}-{/if}</td>
		<td class="main_td1" align="center">{if $item.sms_status.status==1}{$item.sms_status.phone}{else}{$item.sms_status.status|linkages:"approve_status"|default:"�����"}{/if}</td>
		<td class="main_td1" align="center">{if $item.video_status.addtime!=""}{$item.video_status.status|linkages:"approve_status"|default:"�����"}{else}-{/if}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="11" class="action">
			<div class="floatl">
			<script>
				var url = '{$_A.query_url_all}';
				{literal}
				function sousuo(){
				var username = $("#username").val();
				location.href=url+"&username="+username;
				}
				</script>
				{/literal}
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}">
				<input type="button" value="����" onclick="sousuo()">
			</div>
		</td>
	</tr>
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	{/list}
	
</table>
</div>