
<div class="module_add">
	<div class="module_title"><strong>�û��ʽ�ͳ��</strong>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td class="main_td">id</td>
			<td class="main_td">�û���</td>
			<td class="main_td">������� </td>
			<td class="main_td">��ֵ���</td>
			<td class="main_td">�ѻ��ս��</td>
			<td class="main_td">�ѽ���</td>
			<td class="main_td">���ս��</td>
			<td class="main_td">���ֽ��</td>
			<td class="main_td">������</td>
			<td class="main_td">��Ͷ���</td>
			<td class="main_td">�ѻ����</td>
			<td class="main_td">�������</td>
			<td class="main_td">����</td>
		</tr>
		{ list module="account" plugins="count" function="GetUsersCounts" var="loop" username=request email=request status=request order=request dotime1=request dotime2=request type=request epage="20"}
		{foreach from=$loop.list item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td >{ $item.id}</td>
			<td >{$item.username}</td>
			<td >��{$item.balance}</td>
			<td >��{$item.rechare_success|default:0}</td>
			<td >��{$item.recover_yes|default:0}</td>
			<td >��{$item.borrow_success|default:0}</td>
			<td >��{$item.await}</td>
			<td >��{$item.cash_success|default:0}</td>
			<td >��{$item.frost}</td>
			<td >��{$item.tender_success|default:0}</td>
			<td >��{$item.repay_yes|default:0}</td>
			<td >��{$item.repay_wait|default:0}</td>
			<td >�鿴��ϸ</td>
		</tr>
		{ /foreach}
		<tr>
		<td colspan="12" class="action">
		<div class="floatl">
        	
		</div>
		<div class="floatr">
        <div style="float:right">
			���ͣ�<select name="type" id="type">
            <option value="">ȫ��</option>
            {loop module="account" function="GetAccountType" account_type="user" var="type_var"}
            <option value="{$type_var.type}"  {if $type_var.type==$magic.request.type } selected=""{/if}>{$type_var.type|linkages:"account_type"}</option>
            {/loop}
            </select> �û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> ����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/> <input type="button" value="����" / onclick="sousuo('{$_A.query_url_all}')">&nbsp;&nbsp;&nbsp;</div></div>
		
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