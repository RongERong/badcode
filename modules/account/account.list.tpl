
<div class="module_add">
	<div class="module_title"><strong>�˺���Ϣ����</strong><div style="float:right">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> <input type="button" value="����" / onclick="sousuo()"> </div></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td class="main_td">ID</td>
			<td class="main_td">�û���</td>
			<td class="main_td" title="���ý��+������+���ս��">�ʲ��ܶ� </td>
			<td class="main_td">���ý��</td>
			<td class="main_td">������</td>
			<td class="main_td">���ս��</td>
			<td class="main_td">�������</td>
			<td class="main_td">����</td>
		</tr>
		{list module="account" function="GetList"  var="loop" username=request epage="20"}
		{foreach from=$loop.list item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>
			<td ><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}" title="�鿴">{$item.username}</a></td>
			<td >��{$item.total}</td>
			<td >��{$item.balance}</td>
			<td >��{$item.frost}</td>
			<td >��{$item.await}</td>
			<td >��{$item.repay}</td>
			<td ><a href="{$_A.query_url}/recharge&username={$item.username}" >��ֵ��¼</a> <a href="{$_A.query_url}/cash&username={$item.username}" >���ּ�¼</a> <a href="{$_A.query_url}/log&username={$item.username}" >�ʽ��¼</a></td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="12" class="action">
			<span>�ܿ��ý��:{$loop.total_balance} </span>
			<span>�ܶ�����:{$loop.total_frost} </span>
		<div class="floatr">
			<a href="{$_A.query_url_all}&type=excel&page={$magic.request.page|default:1}&username={$magic.request.username}&epage=20">������ǰ</a> <a href="{$_A.query_url_all}&type=excel&username={$magic.request.username}">����ȫ��</a>&nbsp;&nbsp;&nbsp;
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="12" class="page">
			{$loop.pages|showpage} 
			</td>
		</tr>
		{/list}
	</form>	
</table>

