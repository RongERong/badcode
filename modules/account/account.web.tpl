{if $magic.request.action=="" && $_A.query_type=="web"}
<div class="module_add">
	<div class="module_title"><strong>{$MsgInfo.account_name_balances}</strong><div style="float:right">
			���ͣ�
            <select name="type" id="type">
            <option value="">ȫ��</option>
            {loop module="account" function="GetAccountType" account_type="web" var="type_var"}
            <option value="{$type_var.type}"  {if $type_var.type==$magic.request.type } selected=""{/if}>{$type_var.type|linkages:"account_type"}</option>
            {/loop}
            </select>
			����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/> <input type="button" value="����" / onclick="sousuo('')">&nbsp;&nbsp;&nbsp;</div></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td class="main_td">{$MsgInfo.account_name_id}</td>
			<td class="main_td">����</td>
			<td class="main_td">������</td>
			<td class="main_td">{$MsgInfo.account_name_money}</td>
			<td class="main_td">{$MsgInfo.account_name_income}</td>
			<td class="main_td">{$MsgInfo.account_name_expend}</td>
			<td class="main_td">{$MsgInfo.account_name_remark}</td>
			<td class="main_td">{$MsgInfo.account_name_addtime}</td>
			<td class="main_td">{$MsgInfo.account_name_addip}</td>
		</tr>
		{ list module="account" function="GetWebList"  var="loop" username="request" type="request" dotime1="request" dotime2="request" epage=20 }
		{foreach from=$loop.list item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td >{ $item.id}</td>
			<td >{ $item.type|linkages:"account_type"}</td>
			<td >{$item.username}</td>
			<td >��{$item.money}</td>
			<td >��{$item.expend}</td>
			<td >��{$item.income}</td>
			<td >{$item.remark}</td>
			<td >{$item.addtime|date_format}</td>
			<td >{$item.addip}</td>
		</tr>
		{ /foreach}
		<tr>
		<td colspan="9" class="action">
		<div class="floatl">
			<span title="�ܶ�=����-֧��">�ܶ</span>{$loop.account_all|default:0}Ԫ | �����룺{$loop.account_income|default:0} | ��֧����{$loop.account_expend|default:0}
		</div>
		<div class="floatr">
			 <a href="{$_A.query_url_all}&type=excel&page={$magic.request.page|default:1}&username={$magic.request.username}&status={$magic.request.status}&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}&_type={$magic.request.type}&epage=20">������ǰ</a> <a href="{$_A.query_url_all}&type=excel&username={$magic.request.username}&status={$magic.request.status}&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}&_type={$magic.request.type}">����ȫ��</a>
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
{elseif $magic.request.action=="repay"}

<div class="module_add">
	<div class="module_title"><strong>��վӦ����ϸ��</strong><div style="float:right">
			Ӧ��ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/> ״̬��<select name="recover_status" id="recover_status"><option value="" {if $magic.request.recover_status==""}selected="selected"{/if}>����</option><option value="1" {if $magic.request.recover_status==1}selected="selected"{/if}>�ѻ�</option><option value="2" {if $magic.request.recover_status==2}selected="selected"{/if}>δ��</option></select> <input type="button" value="����" / onclick="sousuo('{$_A.query_url}/web&action=repay')">&nbsp;&nbsp;&nbsp;</div></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr class="ytit1" >
			<td  >������</td>
			<td  >Ӧ������</td>
			<td  >�����</td>
			<td  >�ڼ���/������</td>
			<td  >�渶���</td>
			<td  >Ӧ�ձ���</td>
			<td  >Ӧ����Ϣ</td>
			<td  >���ڷ�Ϣ</td>
			<td  >��������</td>
			<td  >״̬</td>
		</tr>
		{list module="borrow" var="loop" function ="GetBorrowRepayList" showpage="3" keywords="request" dotime1="request" dotime2="request" borrow_status=3 type="web" order="recover_status" recover_status=request epage="20" showtype="web"}
		{foreach from="$loop.list" item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td  ><a href="/invest/a{$item.borrow_nid}.html" target="_blank" title="{$item.borrow_name}">{$item.borrow_name|truncate:8}</a></td>
			<td  >{$item.repay_time|date_format:"Y-m-d"}</td>
			<td  ><a href="/u/{$item.borrow_userid}" target="_blank">{$item.borrow_username}</a></td>
			<td  >{$item.repay_period+1}/{$item.borrow_period}</td>
			<td  >��{$item.repay_account_yes}</td>
			<td  >��{$item.repay_capital  }</td>
			<td  >��{$item.repay_interest  }</td>
			<td  >��{$item.late_interest|default:0  }</td>
			<td  >{$item.late_days|default:0  }��</td>
			<td  >{if $item.repay_status==1}<font color="#666666">�ѻ�</font>{else}<font color="#FF0000">δ��</font>{/if}</td>			
		</tr>
		{/foreach}
		<tr>
		<td colspan="14" class="action">
		<div class="floatl">
			Ӧ���ܶ{$loop.all_capital|default:0.00}Ԫ
		</div>
		<div class="floatr">
			 <a href="{$_A.query_url_all}&action=repay&_type=excel&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}&borrow_status=3&type=web&order=recover_status&recover_status={$magic.request.recover_status}&epage=15&show_type=web&page={$magic.request.page|default:1}">������ǰ</a> <a href="{$_A.query_url_all}&action=repay&_type=excel&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}&borrow_status=3&type=web&order=recover_status&recover_status={$magic.request.recover_status}&epage=15&show_type=web">����ȫ��</a>
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

{elseif $magic.request.action=="account"}

<div class="module_add">
	<div class="module_title"><strong>��վ�渶����</strong><div style="float:right">
			���ͣ�{linkages name="type" nid="account_web_type" type="value" value="$magic.request.type" default="����"}
			����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>
			<input type="button" value="����" / onclick="sousuo('{$_A.query_url}/web&action=account')">&nbsp;&nbsp;&nbsp;</div></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td class="main_td">ID</td>
			<td class="main_td">�û���</td>
			<td class="main_td">����</td>
			<td class="main_td">��վ�渶���</td>
			<td class="main_td">��ע</td>
			<td class="main_td">���ʱ��</td>
			<td class="main_td">���IP</td>
		</tr>
		{list module="account" function="GetWebList" var="loop" type="request" dotime1="request" dotime2="request" epage="20"}
		{foreach from=$loop.list item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>
			<td >{$item.username|default:"��վ"}</td>
			<td >{$item.type|linkages:"account_web_type"}</td>
			<td >��{$item.money|round:"2,3"}</td>
			<td >{$item.remark}</td>
			<td >{$item.addtime|date_format}</td>
			<td >{$item.addip}</td>
		</tr>
		{ /foreach}
		<tr>
		<td colspan="7" class="action">
		<div class="floatl">
			�渶�ܼƣ�{$loop.all_money}Ԫ
		</div>
		<div class="floatr">
			 <a href="{$_A.query_url_all}&action=account&type={$magic.request.type}&_type=excel&page={$magic.request.page|default:1}&username={$magic.request.username}&status={$magic.request.status}&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}">������ǰ</a> <a href="{$_A.query_url_all}&action=account&type={$magic.request.type}&_type=excel&username={$magic.request.username}&status={$magic.request.status}&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}">����ȫ��</a>
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="7" class="page">
			{$loop.pages|showpage} 
			</td>
		</tr>
		{/list}
	</form>	
</table>

{elseif $_A.query_type=="web_count"}
{if $magic.request.action==""}
<div class="module_add" >
	<div class="module_title"><strong>��վ�ʽ�ͳ��</strong></div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">��ֵ�ܶ�</td>
			<td width="*" class="main_td">��ֵ������</td>
			<td width="*" class="main_td">�����ܶ�</td>
			<td width="" class="main_td">����������</td>
			<td width="" class="main_td" title="�ɹ��Ľ���ܶ�">����ܶ�</td>
			<td width="" class="main_td" title="�ѻ�����ܶ�">�����ܶ�</td>
			<td width="" class="main_td" title="��ֵ�����ֳ���">����������</td>
			<td width="" class="main_td" title="">��վ�渶�ܶ�</td>
			<td width="" class="main_td" title="Ӧ���Ǳ��ѻ����">�������ܶ�</td>
			<td width="" class="main_td" title="Ӧ���Ǳ�δ�����">δ�����ܶ�</td>
			<td width="" class="main_td" >��ϸ</td>
		</tr>
		{list module="account" plugins="Tongji" function="GetList" var="loop"  dotime1="request" dotime2="request"}
		{ foreach  from=$loop.list key=key item=item }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td >{ $item.user_id}</td>
			<td >{ $item.username}</td>
			<td >{ $item.realname}</td>
			<td >{ $item.recharge_account}</td>
			<td >{ $item.recharge_fee}</td>
			<td >{ $item.borrow_success}</td>
			<td >{ $item.recover_capital}</td>
			<td >{ $item.recover_interest}</td>
			<td >{ $item.borrow_fee}</td>
			<td >{ $item.tender}</td>
			<td ><a href="{$_A.query_url}/users&username={$item.username}&dotime1={$magic.request.dotime1}&dotime2={$magic.request.dotime2}">�鿴��ϸ</a></td>
			
			</tr>
		{ /foreach}
		<tr>
			<td colspan="15" class="action">
			<div class="floatl">
				
			</div>
			<div class="floatr">
			
		 ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>
				 <input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/full&type={$magic.request.type}&status={$magic.request.status}')">
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="15" class="page">
			{$loop.pages|showpage} 
			</td>
		</tr>
	</form>	
</table>
{else}
<div class="module_add" >
	<div class="module_title"><strong>��վ��ϸ</strong></div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">����</td>
			<td width="*" class="main_td">��ֵ��</td>
			<td width="*" class="main_td">��ֵ������</td>
			<td width="" class="main_td">���ֶ�</td>
			<td width="" class="main_td">����������</td>
			<td width="" class="main_td">�ɹ�����</td>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">����������</td>
			<td width="" class="main_td">��վ�渶</td>
			<td width="" class="main_td">����Ӧ�����</td>
			<td width="" class="main_td">δ���˽��</td>
		</tr>
		{list module="account" plugins="Tongji" function="GetList" var="loop"  dotime1="request" dotime2="request"}
		{ foreach  from=$loop.list key=key item=item }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td >{ $item.user_id}</td>
			<td >{ $item.username}</td>
			<td >{ $item.realname}</td>
			<td >{ $item.recharge_account}</td>
			<td >{ $item.recharge_fee}</td>
			<td >{ $item.borrow_success}</td>
			<td >{ $item.recover_capital}</td>
			<td >{ $item.recover_interest}</td>
			<td >{ $item.borrow_fee}</td>
			<td >{ $item.tender}</td>
			<td >{ $item.tender}</td>
			</tr>
		{ /foreach}
		<tr>
			<td colspan="15" class="action">
			<div class="floatl">
				
			</div>
			<div class="floatr">
			
		 ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>
				 <input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/full&type={$magic.request.type}&status={$magic.request.status}')">
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="15" class="page">
			{$loop.pages|showpage} 
			</td>
		</tr>
	</form>	
</table>
{/if}
{/if}