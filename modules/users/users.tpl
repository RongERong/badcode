<!--�û��б� ��ʼ-->
{if $_A.query_type == "list"}
<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if $magic.request.order==""}style="color:red"{/if}>{$MsgInfo.users_name_order_default}</a></li> 
<li><a href="{$_A.query_url_all}&order=last_time" {if $magic.request.order=="last_time"}style="color:red"{/if}>{$MsgInfo.users_name_order_last_time}</a></li> 
<li><a href="{$_A.query_url_all}&order=reg_time" {if $magic.request.order=="reg_time"}style="color:red"{/if}>{$MsgInfo.users_name_order_reg_time}</a></li>
</ul> 
<div class="module_add">
	<div class="module_title"><strong>�û��б�</strong><span style="float:right">
		{$MsgInfo.users_name_username}��<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>  {$MsgInfo.users_name_email}��<input type="text" name="email" id="email" value="{$magic.request.email}"/>   <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="sousuo()"></span></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">{$MsgInfo.users_name_id}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_username}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_email}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_logintime}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_reg_time}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_reg_ip}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_up_time}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_up_ip}</td>
		<th width="" class="main_td">{$MsgInfo.users_name_last_time}</th>
		<th width="" class="main_td">{$MsgInfo.users_name_last_ip}</th>
		<th width="" class="main_td">�޸�</th>
	</tr>
	{ list module="users" function="GetUsersList" var="loop" username=request email=request order="request" epage="20"}
	{foreach from=$loop.list item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.email}</td>
		<td class="main_td1" align="center">{$item.logintime|default:0}</td>
		<td class="main_td1" align="center" >{$item.reg_time|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{$item.reg_ip}</td>
		<td class="main_td1" align="center" >{$item.up_time|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{$item.up_ip}</td>
		<td class="main_td1" align="center" >{$item.last_time|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{$item.last_ip}</td>
		<td class="main_td1" align="center" ><a href="{$_A.query_url}/edit&user_id={$item.user_id}">�޸�</a></td>
	</tr>
	{/foreach}
	<tr>
			<td colspan="11" class="action">
			<div class="floatl">
			<script>
	  var url = '{$_A.query_url}';
	    {literal}
	  	function sousuo(){
			var username = $("#username").val();
			var email = $("#email").val();
			location.href=url+"&username="+username+"&email="+email;
		}
	  
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
			</div>
			</td>
		</tr>
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	
	{ /list}
</table>
<!--�û��б� ����-->
{elseif $_A.query_type == "invite_info"}
<div class="module_add">
	<div class="module_title"><strong>�û��Ƽ������</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">�û�id</td>
		<td width="*" class="main_td">�û���</td>
		<td width="*" class="main_td">�Ƿ�VIP</td>
		<td width="*" class="main_td">ͨ��ʱ��</td>
	</tr>
	{ list module="users" plugins="Friends" function="GetUsersInviteList" var="loop" 	user_id=$magic.request.user_id}
		{foreach from=$loop.list item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{if $item.vip_status == 1}��{else}��{/if}</td>
		<td class="main_td1" align="center">{if $item.verify_time!=''}{$item.verify_time|date_format}{else}-{/if}</td>
	</tr>
	{/foreach}
	<tr>
			<td colspan="11" class="action">
			<div class="floatl">
			<script>
	  var url = '{$_A.query_url}/info';
	    {literal}
	  	function sousuo(){
			var username = $("#username").val();
			location.href=url+"&username="+username;
		}
	  
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
				{$MsgInfo.users_name_username}��<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>     <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="sousuo()">
			</div>
			</td>
		</tr>
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	
	{ /list}
</table>


<!--�û���Ϣ�б� ��ʼ-->
{elseif $_A.query_type == "info"}
<div class="module_add">
	<div class="module_title"><strong>�û���Ϣ</strong><span style="float:right">
		{$MsgInfo.users_name_username}��<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>     <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="sousuo()"></span></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="*" class="main_td">�û���</td>
		<td width="*" class="main_td">����</td>
		<td width="*" class="main_td">����</td>
		<td width="*" class="main_td">�û�����</td>
		<td width="*" class="main_td">��������</td>
		<td width="*" class="main_td">�Ƽ���</td>
		<td width="*" class="main_td">����¼</td>
		<td width="*" class="main_td">������Ϣ</td>
		<td width="*" class="main_td">��ϸ��Ϣ</td>
		<td width="*" class="main_td">�������</td>
		<td width="*" class="main_td">�ʽ�����</td>
		<td width="*" class="main_td">��������</td>
	</tr>
	{ list module="users" function="GetUsersInfoList" var="loop" username=request email=request epage="20"}
	{foreach from=$loop.list item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{ $item.username}</td>
		<td class="main_td1" align="center">{ $item.email}</td>
		<td class="main_td1" align="center">{ $item.credit|credit:"borrow"}</td>
		<td class="main_td1" align="center">{$item.type_name}</td>
		<td class="main_td1" align="center" >{$item.in_num|default:0}[<a href="{$_A.query_url}/invite_info&user_id={$item.user_id}">�鿴</a>]</td>
		<td class="main_td1" align="center" >{$item.invite_username|default:-}</td>
		<td class="main_td1" align="center" >{$item.last_time|date_format}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/info_view&user_id={$item.user_id}">�鿴</a></td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/viewinfo&user_id={$item.user_id}">�༭</a></td>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/attestations/upload&user_id={$item.user_id}">�鿴</a></td>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/account/log&username={$item.username}">�鿴</a></td>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/credit/log&user_id={$item.user_id}">�鿴</a></td>
	</tr>
	{/foreach}
	<tr>
			<td colspan="11" class="action">
			<div class="floatl">
			<script>
	  var url = '{$_A.query_url}/info';
	    {literal}
	  	function sousuo(){
			var username = $("#username").val();
			var email = $("#email").val();
			location.href=url+"&username="+username+"&email="+email;
		}
	  
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
			</div>
			</td>
		</tr>
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	
	{ /list}
</table>
<!--�û���Ϣ�б� ����-->

{elseif $_A.query_type == "info_edit" }
<div class="module_add">
	
	<form  name="form_user" method="post" action="" >
	<div class="module_title"><strong>�޸��û���Ϣ</strong></div>
	
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			{$_A._user_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ǳƣ�</div>
		<div class="c">
		<input name="niname" type="text"  class="input_border" value="{$_A._user_result.niname}" /> <font color="#FF0000">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
		<select name="status">
			<option value="0" {if $_A._user_result.status=="0"} selected="selected"{/if}>����</option>
			<option value="1" {if $_A._user_result.status=="1"} selected="selected"{/if}>����</option>
			<option value="2" {if $_A._user_result.status=="2"} selected="selected"{/if}>�ر�</option>
		</select>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���գ�</div>
		<div class="c">
		<input name="birthday" type="text"  class="input_border" value="{$_A._user_result.birthday}" /> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">�Ա�</div>
		<div class="c">
		<input name="sex" type="radio"  class="input_border" value="��" {if $_A._user_result.sex=="��"} checked="checked"{/if} /> ��
		<input name="sex" type="radio"  class="input_border" value="Ů" {if $_A._user_result.sex=="Ů"} checked="checked"{/if} /> Ů
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ȫ���⣺</div>
		<div class="c">
		<input name="question" type="text"  class="input_border" value="{$_A._user_result.question}" /> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ȫ�𰸣�</div>
		<div class="c">
		<input name="answer" type="text"  class="input_border" value="{$_A._user_result.answer}" /> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ڵأ�</div>
		<div class="c">
		{areas type="p,c,a"  value='$_A._user_result.area'}
		</div>
	</div>
	
	
	<div class="module_submit border_b" >
	<input type="hidden" name="user_id" value="{ $_A._user_result.user_id }" />
	<input type="submit" name="submit" value="�ύ" />
	</div>
	</form>
</div>
{elseif $_A.query_type=="info_view"}

<div class="module_add">
	<div class="module_title"><strong>�û�����鿴</strong></div>
	<div style="margin-top:10px;">
		
	
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			{$_A._user_result.username}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���䣺</div>
		<div class="c">
			{$_A._user_result.email}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">ע��ʱ��/ע��ip��</div>
		<div class="c">
			{$_A._user_result.reg_time|date_format}/{$_A._user_result.reg_ip}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">����½ʱ��/����½ip��</div>
		<div class="c">
			{$_A._user_result.last_time|date_format}/{$_A._user_result.last_ip}
		</div>
	</div>
	
	{articles module="approve" function="GetRealnameOne" user_id="$magic.request.user_id" var="Evar"}
	<div class="module_title"><strong>��֤״̬</strong></div>
	<div class="module_border">
		<div class="l">��ʵ����/���֤��/�Ƿ���֤��</div>
		<div class="c">
			{$Evar.realname|default:-}/{$Evar.card_id|default:-}/{if $Evar.status==1}����֤{else}δ��֤{/if}
		</div>
	</div>
	
	{/articles}
	{articles module="approve" function="GetEduOne" user_id="$magic.request.user_id" var="Evar"}
	<div class="module_border">
		<div class="l">ѧ��/�Ƿ���֤��</div>
		<div class="c">
			{$Evar.degree}/{if $Evar.status==1}����֤{else}δ��֤{/if}
		</div>
	</div>
	{/articles}
	<div class="module_border">
		<div class="l">�ֻ�/�Ƿ���֤��</div>
		<div class="c">
			{$_A._user_result.phone|default:-}/{if $_A._user_result.phone_status==1}����֤{else}δ��֤{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ƶ/�Ƿ���֤��</div>
		<div class="c">
			{$_A._user_result.video}{if $_A._user_result.video_status==1}����֤{else}δ��֤{/if}
		</div>
	</div>
	{articles module="users" function="GetUsersVip" user_id="$magic.request.user_id" var="Vvar"}
	<div class="module_border">
		<div class="l">Vip��Чʱ��/�Ƿ���֤��</div>
		<div class="c">	{$Vvar.first_date|date_format:"Y-m-d"|default:-}~{$Vvar.end_date|date_format:"Y-m-d"|default:-}/{if $Vvar.status==1}����֤{else}δ��֤{/if}
		</div>
	</div>
{/articles}
{articles module="account"  function="GetOne" var="Avar" user_id="$magic.request.user_id"}
	
{articles module="borrow" plugins="count" function="GetUsersRecoverCount" var="recover_var" user_id="$magic.request.user_id"}	
</div>
<div class="module_title"><strong>�ʽ�����</strong></div>
  <table width="100%">
  <tr>
    <td width="15%" valign="top" >�˻��ܶ� </td>
    <td width="15%" valign="top" >{$Avar.total|default:0.00} </td>
    <td width="15%" valign="top" >������� </td>
    <td width="15%" valign="top"  > {$Avar.balance|default:0.00}</td>
    <td width="15%" valign="top"  >������ </td>
    <td width="15%" valign="top" > {$Avar.frost|default:0.00}</td>
  </tr>
  {articles module="account" function="GetRechargeCount" var="Rvar" user_id='$magic.request.user_id'}
   {list module="account" function="GetCashList" var="loop" user_id="$magic.request.user_id" epage=20}
  <tr>
    <td width="15%" valign="top" >Ͷ�궳���ܶ </td>
    <td width="15%" valign="top" >{ $recover_var.tender_now_account|default:0.00} </td>
    <td width="15%" valign="top" >��ֵ�ɹ��ܶ </td>
    <td width="15%" valign="top"  >{$Rvar.account_balance|default:0.00} </td>
    <td width="15%" valign="top"  >���ֳɹ��ܶ </td>
    <td width="15%" valign="top" > {$loop.credited_all|default:0.00}</td>
  </tr>
  <tr>
    <td width="15%" valign="top" >��ֵ�����ѣ�</td>
    <td width="15%" valign="top" >{$Rvar.account_fee|default:0.00} </td>
    <td width="15%" valign="top" > ���������ѣ�</td>
    <td width="15%" valign="top"  >{$loop.fee_all|default:0.00}</td>
    <td width="15%" valign="top"  > </td>
    <td width="15%" valign="top" > </td>
  </tr>
  {/list}
  {/articles}
  </table>
 {articles module="borrow" plugins="Amount" function="GetAmountUsers" user_id=$magic.request.user_id var="user_amount"}
<div class="module_title"><strong>��������</strong></div>
  <table width="100%">
  {if $user_amount.credit_status==1}
 <tr>
    <td width="15%" valign="top" >�����ܶ�ȣ� </td>
    <td width="15%" valign="top" >��{$user_amount.credit|default:"0.00"}</td>
    <td width="15%" valign="top" >�������ö��: </td>
    <td width="15%" valign="top" >��{$user_amount.credit_use|default:"0.00"}</td>
    <td width="15%" valign="top"  >��ֵ��ȣ� </td>
    <td width="15%" valign="top"  >��{$user_amount.worth|round:"2"|default:"0.00"}</td>
  </tr>
  {/if}
  {if $user_amount.vouch_status==1}
  <tr>
    <td width="15%" valign="top" >�����ܶ�� </td>
    <td width="15%" valign="top" > ��{$user_amount.vouch|round:"2"|default:"0.00"}</td>
    <td width="15%" valign="top" >���õ�����ȣ� </td>
    <td width="15%" valign="top" > ��{$user_amount.vouch_use|round:"2"|default:"0.00"}</td>
    <td width="15%" valign="top"  ></td>
    <td width="15%" valign="top"  > </td>
  </tr>
  {/if}
  {if $user_amount.pawn_status==1}
  <tr>
    <td width="15%" valign="top" >�����ܶ�ȣ� </td>
    <td width="15%" valign="top" >��{$user_amount.pawn|round:"2"|default:"0.00"}</td>
    <td width="15%" valign="top" >�������Ŷ�ȣ� </td>
    <td width="15%" valign="top" > ��{$user_amount.pawn_use|round:"2"|default:"0.00"}</td>
    <td width="15%" valign="top"  > </td>
    <td width="15%" valign="top"  > </td>
  </tr>
  {/if}
  {if $user_amount.vest_status==1}
  <tr>
	<td width="15%" valign="top" >��ת��ȣ� </td>
    <td width="15%" valign="top" >��{$user_amount.vest|round:"2"|default:"0.00"}</td>
    <td width="15%" valign="top" >������ת��ȣ� </td>
    <td width="15%" valign="top" > ��{$user_amount.vest_use|round:"2"|default:"0.00"}</td>
    <td width="15%" valign="top"  > </td>
    <td width="15%" valign="top"  > </td>
  </tr>
  {/if}
</table>
{/articles}
{/articles}
{articles module="borrow" plugins="count" function="GetUsersRepayCount" var="repay_var" user_id="$magic.request.user_id"}
{articles module="borrow" plugins="count" function="GetUsersRecoverCount" var="recover_var" user_id="$magic.request.user_id"}
<div class="module_title"><strong>���ͳ��</strong></div>
  <table width="100%">
  <tr>
    <td width="12%" valign="top" title="ע���������ۼƽ�����ܶ�">�����ܶ�:</td>
    <td width="12%" valign="top" >��{$repay_var.borrow_success_account|default:0.00}</td>
    <td width="12%" valign="top" > </td>
    <td width="12%" valign="top" > </td>
    <td width="12%" valign="top" > </td>
    <td width="12%" valign="top" > </td>
    <td width="12%" valign="top" ></td>
    <td width="12%" valign="top" > </td>
  </tr>
  <tr>
    <td width="12%" valign="top" >�����ܶ�:</td>
    <td width="12%" valign="top" >��{ $repay_var.repay_wait_account|default:0.00} </td>
    <td width="12%" valign="top" >��������:</td>
    <td width="12%" valign="top" > {$repay_var.repay_wait_num|default:0}��</td>
    <td width="12%" valign="top" >�ѻ��ܶ�:</td>
    <td width="12%" valign="top" >��{$repay_var.repay_yes_account|default:0.00}</td>
    <td width="12%" valign="top" >�ѻ�������:</td>
    <td width="16%" valign="top" >{$repay_var.repay_yes_num|default:0}��</td>
  </tr>
  <tr>
    <td width="12%" valign="top" >����������:</td>
    <td width="12%" valign="top" >{$repay_var.borrow_loan_num|default:0}�� </a></td>
    <td width="12%" valign="top" >����������</td>
    <td width="12%" valign="top" >{$repay_var.repay_wait_times|default:0}��</td>
    <td width="12%" valign="top" >�ѻ�������</td>
    <td width="12%" valign="top" > {$repay_var.repay_yes_times|default:0}��</td>
    <td width="12%" valign="top" >���ڴ���: </td>
    <td width="12%" valign="top" >{$repay_var.repay_late_num|default:0}�� </td>
  </tr>
    <tr>
    <td width="12%" valign="top" >���Ӧ�����</td>
	<td width="12%" valign="top" ><font>��{$repay_var.repay_wait_now_account|default:0.00}</font></td>
    <td width="12%" valign="top" >����������ڣ�</td>
	<td width="12%" valign="top" >{$repay_var.repay_wait_now_time|date_format:"Y/m/d"|default"/"}</td>
  </tr>
</table>


<div class="module_title"><strong>Ͷ��ͳ��</strong></div>
  <table width="100%">
  <tr >
    <td width="12%" valign="top" >��Ͷ�ʽ� </td>
    <td width="12%" valign="top" >��{ $recover_var.tender_success_account|default:0.00} </td>
    <td width="12%" valign="top" title="���������ʵļ�Ȩƽ��ֵ">Ͷ��ƽ�������ʣ� </td>
    <td width="12%" valign="top" > { $recover_var.tender_recover_scale|default:0.00}%</td>
    <td width="12%" valign="top" tltle="����Ͷ�ʽ��/�ۼ�Ͷ�ʽ��"> �����ʣ�</td>
    <td width="12%" valign="top" >{ $recover_var.tender_false_scale|default:0.00}% </td>
    <td width="12%" valign="top" >��վ�渶�ܶ�</td>
    <td width="12%" valign="top" >��{ $recover_var.recover_web_account|default:0.00}</td>
  </tr>
  <tr >
    <td width="12%" valign="top" >�ѻ����ܶ </td>
    <td width="12%" valign="top" > ��{ $recover_var.recover_yes_account|default:0.00}</td>
    <td width="12%" valign="top" >�ѻ��ձ��� </td>
    <td width="12%" valign="top" >��{ $recover_var.recover_yes_capital|default:0.00} </td>
    <td width="12%" valign="top" >�ѻ�����Ϣ </td>
    <td width="12%" valign="top" >��{ $recover_var.recover_yes_interest|default:0.00} </td>
    <td width="12%" valign="top" >�ѻ������� </td>
    <td width="12%" valign="top" > { $recover_var.recover_yes_num|default:0}��</td>
  </tr>
  <tr>
    <td width="12%" valign="top" >�������ܶ </td>
    <td width="12%" valign="top" > ��{ $recover_var.recover_wait_account|default:0.00}</td>
    <td width="12%" valign="top" >�����ձ���</td>
    <td width="12%" valign="top" > ��{ $recover_var.recover_wait_capital|default:0.00}</td>
    <td width="12%" valign="top" >��������Ϣ��</td>
    <td width="12%" valign="top" > ��{ $recover_var.recover_wait_interest|default:0.00}</td>
    <td width="12%" valign="top" >������������</td>
    <td width="16%" valign="top" > { $recover_var.recover_wait_num|default:0}��</td>
  </tr>
  <tr>
    <td width="12%" valign="top" >��׬������ </td>
    <td width="12%" valign="top" >��{ $recover_var.tender_award_fee|default:0.00}</td>
    <td width="12%" valign="top" >���ڷ������룺</td>
    <td width="12%" valign="top" > ��{ $recover_var.recover_fee_account|default:0.00}</td>
    <td width="12%" valign="top" >��ǰ��������룺</td>
    <td width="12%" valign="top" >��{ $recover_var.tender_advance_account|default:0.00}</td>
    <td width="12%" valign="top" >��ʧ��Ϣ�ܶ </td>
    <td width="12%" valign="top" > ��{ $recover_var.recover_loss_account|default:0.00}</td>
  </tr>

</table>
{/articles}
 {/articles}
<!--�û���¼�б� ��ʼ-->
{elseif $_A.query_type == "log"}
<div class="module_add">
	<div class="module_title"><strong>�û���¼</strong><span style="float:right">
		{$MsgInfo.users_name_username}��<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>  {$MsgInfo.users_name_email}��<input type="text" name="email" id="email" value="{$magic.request.email}"/>   <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="sousuo()"></span></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">{$MsgInfo.users_name_id}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_username}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_code}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_type}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_operating}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_operating_id}</td>
		<td width="*" class="main_td">{$MsgInfo.users_name_result}</td>
		<th width="" class="main_td">{$MsgInfo.users_name_content}</th>
		<th width="" class="main_td">{$MsgInfo.users_name_add_time}</th>
		<th width="" class="main_td">{$MsgInfo.users_name_add_ip}</th>
	</tr>
	{ list module="users" function="GetUserslogList" var="loop" username=request email=request epage="20" page="request"}
		{foreach from=$loop.list item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">{$item.username|default:-}</td>
		<td class="main_td1" align="center">{$item.code}</td>
		<td class="main_td1" align="center" >{$item.type}</td>
		<td class="main_td1" align="center" >{$item.operating}</td>
		<td class="main_td1" align="center" >{$item.article_id}</td>
		<td class="main_td1" align="center" >{if $item.result==1}<font color="#006600">{$MsgInfo.users_name_success}</font>{else}<font color="#FF0000">{$MsgInfo.users_name_false}</font>{/if}</td>
		<td class="main_td1" align="center" width="200" >{$item.content}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{$item.addip}</td>
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
			var email = $("#email").val();
			location.href=url+"&username="+username+"&email="+email;
		}
	  
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
			</div>
			</td>
		</tr>
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	{ /list}
</table>
 
<!--�û���¼�б� ����-->

{elseif $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	
	<form  name="form_user" method="post" action="" { if $_A.query_type == "new" }onsubmit="return check_user();"{/if} >
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }{$MsgInfo.users_name_edit}{else}{$MsgInfo.users_name_new}{/if}</strong></div>
	
	<div class="module_border">
		<div class="l">{$MsgInfo.users_name_username}��</div>
		<div class="c">
			{ if $_A.query_type != "edit" }<input name="username" type="text"  class="input_border" />{else}{ $_A.users_result.username}<input name="username" type="hidden"  class="input_border" value="{$_A.users_result.username}" />{/if} <font color="#FF0000">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���䣺</div>
		<div class="c">
		<input name="email" type="text"  class="input_border" value="{$_A.users_result.email}" /> <font color="#FF0000">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">{$MsgInfo.users_name_password}��</div>
		<div class="c">
			<input name="password" type="password" class="input_border" />{ if $_A.query_type == "edit" } {$MsgInfo.users_name_edit_not_empty}{/if} <font color="#FF0000">*</font>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">{$MsgInfo.users_name_password1}��</div>
		<div class="c">
			<input name="password1" type="password" class="input_border" />{ if $_A.query_type == "edit" } {$MsgInfo.users_name_edit_not_empty}{/if} <font color="#FF0000">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">֧�����룺</div>
		<div class="c">
			<input name="paypassword" type="password" class="input_border" />{ if $_A.query_type == "edit" } {$MsgInfo.users_name_edit_not_empty}{/if} <font color="#FF0000">*</font>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">ȷ��֧�����룺</div>
		<div class="c">
			<input name="paypassword1" type="password" class="input_border" />{ if $_A.query_type == "edit" } {$MsgInfo.users_name_edit_not_empty}{/if} <font color="#FF0000">*</font>
		</div>
	</div>
	
	
	<div class="module_submit border_b" >
	{ if $_A.query_type == "edit" }<input type="hidden" name="user_id" value="{ $_A.users_result.user_id }" />{/if}
	<input type="submit" value="{$MsgInfo.users_name_submit}" /><input type="hidden" name="status" value="1" />
	<input type="reset" name="reset" value="{$MsgInfo.users_name_reset}" />
	</div>
	</form>
</div>
{literal}
<script>
function check_user(){
	 var frm = document.forms['form_user'];
	 var username = frm.elements['username'].value;
	 var password = frm.elements['password'].value;
	  var password1 = frm.elements['password1'].value;
	   var email = frm.elements['email'].value;
	 var errorMsg = '';
	  if (username.length == 0 ) {
		errorMsg += '<? echo $this->magic_vars['MsgInfo']['users_username_empty']; ?> \n';
	  }
	   if (username.length<4) {
		errorMsg += '<? echo $this->magic_vars['MsgInfo']['users_username_long4']; ?> \n';
	  }
	  if (password.length==0) {
		errorMsg += '<? echo $this->magic_vars['MsgInfo']['users_password_empty']; ?> \n';
	  }
	  if (password.length<6) {
		errorMsg += '<? echo $this->magic_vars['MsgInfo']['users_password_long6']; ?> \n';
	  }
	   if (password.length!=password1.length) {
		errorMsg += '<? echo $this->magic_vars['MsgInfo']['users_password_error']; ?> \n';
	  }
	   if (email.length==0) {
		errorMsg += '<? echo $this->magic_vars['MsgInfo']['users_email_empty']; ?> \n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}
</script>
{/literal}




<!--��˼�¼�б� ��ʼ-->
{elseif $_A.query_type == "examine"}
<div class="module_add">
<div class="module_title"><strong>��˼�¼�б�</strong><span style="float:right">
	{$MsgInfo.users_name_username}��<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>    <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="sousuo()"></span></div>
</div> 
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">id</td>
		<td width="*" class="main_td">�����</td>
		<td width="*" class="main_td">ģ��</td>
		<td width="*" class="main_td">����</td>
		<td width="*" class="main_td">����</td>
		<th width="" class="main_td">���</th>
		<td width="*" class="main_td">��˱�ע</td>
		<td width="*" class="main_td">���ʱ��</td>
	</tr>
	{ list module="users" function="GetExamineList" var="loop" username=request  epage="20" page="request"}
		{foreach from=$loop.list item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">{$item.username|default:-}</td>
		<td class="main_td1" align="center">{$item.code}</td>
		<td class="main_td1" align="center" >{$item.type}</td>
		<td class="main_td1" align="center" >{$item.article_id}</td>
		<td class="main_td1" align="center" >{if $item.result==1}<font color="#006600">{$MsgInfo.users_name_success}</font>{else}<font color="#FF0000">{$MsgInfo.users_name_false}</font>{/if}(result={$item.result})</td>
		<td class="main_td1" align="center" >{$item.remark}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
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
			var email = $("#email").val();
			location.href=url+"&username="+username+"&email="+email;
		}
	  
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
			</div>
			</td>
		</tr>
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	{ /list}
</table>
<!--��˼�¼�б� ����-->



{elseif $_A.query_type=="type"}

<div class="module_add">
	<div class="module_title"><strong>�û�����</strong></div>
	<div style="margin-top:10px;">
	<div style="float:left; width:30%;">
		
	<div style="border:1px solid #CCCCCC; ">
	
	<form action="{$_A.query_url_all}" method="post">
	<div class="module_title"><strong>{if $magic.request.edit!=""}<input type="hidden" name="id" value="{$_A.users_type_result.id}" />�޸��û����� ��<a href="{$_A.query_url_all}">���</a>��{else}����û�����{/if}</strong></div>
	
	
	<div class="module_border">
		<div class="l">�������ƣ�</div>
		<div class="c">
			<input type="text" name="name" value="{$_A.users_type_result.name}"/>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ʶ����</div>
		<div class="c">
			<input type="text" name="nid" value="{$_A.users_type_result.nid}" onkeyup="value=value.replace(/[^a-z0-9_]/g,'')"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">Ĭ&nbsp;&nbsp;�ϣ�</div>
		<div class="c">
			{input type="radio" name="checked" value="0|��,1|��" checked="$_A.users_type_result.checked"}��ע���ʱ���û�Ĭ�ϵ����ͣ�
		</div>
	</div>
	<div class="module_border">
		<div class="l">������</div>
		<div class="c">
			<textarea name="remark" rows="5" cols="30">{$_A.users_type_result.remark}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">����</div>
		<div class="c">
			<input type="text" name="order" value="{$_A.users_type_result.order|default:10}" size="8"/>
		</div>
	</div>
	
	<div class="module_border" >
		<div class="l">��֤�룺</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4"  onClick="$('#valicode').attr('src','/?plugins&q=imgcode&t=' + Math.random())"/>
		
			<img src="/?plugins&q=imgcode" id="valicode" alt="���ˢ��" onClick="this.src='/?plugins&q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	
	
	<div class="module_submit"><input type="submit" value="ȷ���ύ" class="submit_button" /></div>
		</form>
	</div>
	</div>
		</div>
	<div style="float:right; width:67%; text-align:left">
	<div class="module_add">
	
	
	
	<div class="module_title"><strong>�û������б�</strong></div>
	</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">����</td>
		<td width="" class="main_td">��ʶ��</td>
		<td width="*" class="main_td">���ʱ��</td>
		<td width="*" class="main_td">�Ƿ�Ĭ��</td>
		<td width="*" class="main_td">����</td>
		<td width="*" class="main_td">����</td>
	</tr>
	{ list module="users" function="GetUsersTypeList" var="loop" username=request epage=20}
	{foreach from="$loop.list" item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">{$item.name}</td>
		<td class="main_td1" align="center">{$item.nid}</td>
		<td class="main_td1" align="center">{$item.addtime|date_format}</td>
		<td class="main_td1" align="center">{if $item.checked==1}��{else}<a href="{$_A.query_url_all}&checked={$item.id}" title="��ΪĬ��">��</a>{/if}</td>
		<td class="main_td1" align="center">{$item.order}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url_all}&edit={$item.id}">�޸�</a>/<a href="#" onClick="javascript:if(confirm('ȷ��Ҫɾ����?ɾ���󽫲��ɻָ�')) location.href='{$_A.query_url_all}&del={$item.id}'">ɾ��</a></td>
	</tr>
	{/foreach}
	
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	{/list}
	
</table>

<!--�˵��б� ����-->
</div>
</div>

{elseif $_A.query_type == "rebut" }

<div class="module_add">
	<div class="module_title"><strong>�û���¼</strong><span style="float:right">
		���ٱ��û���<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>     <input type="button" value="{$MsgInfo.users_name_sousuo}"  onclick="sousuo()"/></span></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="*" class="main_td">�ٱ��û�</td>
		<td width="*" class="main_td">���ٱ��û�</td>
		<td width="*" class="main_td">����</td>
		<td width="*" class="main_td">�ٱ�����</td>
		<td width="*" class="main_td">�ٱ�ʱ��</td>		
	</tr>
	{ list module="users" function="GetUsersRebutList" var="loop" username=request epage="20"}
	{foreach from=$loop.list item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">{ $item.username}</td>
		<td class="main_td1" align="center">{ $item.rebut_username}</td>
		<td class="main_td1" align="center">{if $item.type_id==1}��թ{else}��в{/if}</td>
		<td class="main_td1" align="center">{$item.contents}</td>		
		<td class="main_td1" align="center">{$item.addtime|date_format}</td>		
	</tr>
	{/foreach}
	<tr>
			<td colspan="11" class="action">
			<div class="floatl">
			<script>
	  var url = '{$_A.query_url}/rebut';
	    {literal}
	  	function sousuo(){
			var username = $("#username").val();			
			location.href=url+"&username="+username;
		}
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
			</div>
			</td>
		</tr>
	<tr align="center">
		<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	
	{ /list}
</table>
{elseif $_A.query_type == "manage" }
	{if $magic.request.check==''}
	<ul class="nav3"> 
	<li><a href="{$_A.query_url_all}" {if $magic.request.type==""}style="color:red"{/if}>���ʦ</a></li> 
	<li><a href="{$_A.query_url_all}&type=award" {if $magic.request.type=="award"}style="color:red"{/if}>�ƹ㽱��</a></li> 
	</ul>
	{/if}
	{if $magic.request.check!=''}
	
	<div  >
	<form name="form1" method="post" action="{$_A.query_url_all}" >
	<div class="module_border_ajax">
		<div class="l">�û���:</div>
		<div class="c">
		{$_A.user_manage.username}
		</div>
	</div>	
	<div class="module_border_ajax" >
		<div class="l">��ʵ����:</div>
		<div class="c">
		{$_A.user_manage.realname}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">���֤��:</div>
		<div class="c">
		{$_A.user_manage.card_id}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">�� ��:</div>
		<div class="c">
		{$_A.user_manage.sex|linkages:"rating_sex"}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">��������:</div>
		<div class="c">
	{$_A.user_manage.rating_birthday_year|linkages:"rating_birthday_year"}
	{$_A.user_manage.rating_birthday_mouth|linkages:"rating_birthday_mouth"}
	{$_A.user_manage.rating_birthday_day|linkages:"rating_birthday_day"}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">ѧ ��:</div>
		<div class="c">
		{$_A.user_manage.edu|linkages:"rating_education"}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">��ϵ��ַ:</div>
		<div class="c">
			{$_A.user_manage.address}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">����:</div>
		<div class="c">
			{$_A.user_manage.email}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">������ϵ��:</div>
		<div class="c">
			{$_A.user_manage.linkman}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">������ϵ�˵绰:</div>
		<div class="c">
			{$_A.user_manage.linktel}
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">���˼���:</div>
		<div class="c">
			{$_A.user_manage.resume}
		</div>
	</div>
		{if $_A.user_manage.status==0}
		<div class="module_border_ajax" >
			<div class="l">���״̬:</div>
			<div class="c">
			<input type="radio" name="status" checked="checked" value="1">ͨ��	
			<input type="radio" name="status" value="2">��ͨ��	
			</div>
		</div>
		<div class="module_border_ajax" >
			<div class="l">��˱�ע:</div>
			<div class="c">
			<input type="text" name="verify_remark" >
			</div>
		</div>	
		<!-- <div class="module_border_ajax" >
			<div class="l">��֤��:</div>
			<div class="c">
				<input name="valicode" type="text" size="11" maxlength="4"  tabindex="3" />
			</div>
			<div class="c">
				<img src="/?plugins&q=imgcode" id="valicode" onClick="$('#valicode').attr('src','/?plugins&q=imgcode&t=' + Math.random())" alt="���ˢ��"  align="absmiddle" style="cursor:pointer" />
			</div>
		</div> -->	
		<div class="module_submit_ajax" >
		<input type="hidden" name="user_id" value="{$_A.user_manage.user_id}" />
		<input type="submit" name="submit" class="submit_button" value="�ύ" />
		</div>
		{else}
		<div class="module_border_ajax" >
			<div class="l">���״̬:</div>
			<div class="c">
			{if $_A.user_manage.status==1}ͨ��{else}��ͨ��{/if}			
			</div>
		</div>
		<div class="module_border_ajax" >
			<div class="l">��˱�ע:</div>
			<div class="c">
			{$_A.user_manage.verify_remark}			
			</div>
		</div>	
		{/if}
	</form>
	</div>
	
	{elseif $magic.request.type=='award'}
	
	<div class="module_add">
		<div class="module_title"><strong>�ƹ㽱��</strong><span style="float:right">
			�Ƽ���:<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>     
			�������ڣ�<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()"/>
			<input type="button" value="{$MsgInfo.users_name_sousuo}"  onclick="sousuo()"/></span></div>
	</div>
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">�Ƽ���</td>
			<td width="*" class="main_td">������</td>
			<td width="*" class="main_td">��������</td>
			<td width="*" class="main_td">Ͷ�ʽ��</td>
			<td width="*" class="main_td">Ͷ������</td>			
			<td width="*" class="main_td">�껯����</td>			
			<td width="*" class="main_td">������</td>			
		</tr>
		{list  module="users" function="GetManageAccountList" var="loop" dotime1=request username=request dotime2=request  showpage="3"}
		{foreach from=$loop.list item="item"}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1" align="center">{ $item.id}</td>
			<td class="main_td1" align="center">{ $item.username}</td>
			<td class="main_td1" align="center">{ $item.tender_username}</td>
			<td class="main_td1" align="center">{$item.addtime|date_format:"Y-m-d"}</td>
			<td class="main_td1" align="center">{ $item.tender_account}Ԫ</td>
			<td class="main_td1" align="center">{ $item.tender_period}����</td>
			<td class="main_td1" align="center">{ $item.tender_apr}%</td>
			<td class="main_td1" align="center">{ $item.award}Ԫ</td>
			
		</tr>
		{/foreach}
		<tr>
		    <td colspan="11" class="action">
			<div class="floatl">
			<script>
		  var url = '{$_A.query_url}/manage&type=award';
			{literal}
			var _url='';
			function sousuo(){
				var username = $("#username").val();
				var dotime1 = $("#dotime1").val();
				var dotime2 = $("#dotime2").val();
				if(username!=''){
					_url+="&username="+username;
				}
				if(dotime1!=''){
					_url+="&dotime1="+dotime1;
				}
				if(dotime2!=''){
					_url+="&dotime2="+dotime2;
				}
				
				location.href=url+_url;
			}
		  </script>
		  {/literal}
				</div>
				<div class="floatr">
				</div>
				</td>
			</tr>
		<tr align="center">
			<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
		</tr>
		
		{ /list}
	</table>	
	
	{else}
	
	<div class="module_add">
		<div class="module_title"><strong>���ʦ</strong><span style="float:right">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>     <input type="button" value="{$MsgInfo.users_name_sousuo}"  onclick="sousuo()"/></span></div>
	</div>
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">�û���</td>
			<td width="*" class="main_td">����ʱ��</td>
			<td width="*" class="main_td">״̬</td>
			<td width="*" class="main_td">����(��˲鿴��</td>			
		</tr>
		{ list module="users" function="GetUserManageList" var="loop" username=request epage="20"}
		{foreach from=$loop.list item="item"}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1" align="center">{ $item.id}</td>
			<td class="main_td1" align="center">{ $item.username}</td>
			<td class="main_td1" align="center">{ $item.addtime|date_format:"Y-m-d"}</td>		
			<td class="main_td1" align="center">{if $item.status==0}�����{elseif  $item.status==1}��˳ɹ�{elseif  $item.status==2}���ʧ��{/if}</td>		
			<td class="main_td1" align="center"><a href="javascript:void(0)" onclick='tipsWindown("���","url:get?{$_A.query_url_all}&check={$item.user_id}",500,700,"true","","false","text");' />{if $item.status==0}���{else}�鿴{/if}</a></td>		
		</tr>
		{/foreach}
		<tr>
		    <td colspan="11" class="action">
			<div class="floatl">
			<script>
		  var url = '{$_A.query_url}/manage';
			{literal}
			function sousuo(){
				var username = $("#username").val();			
				location.href=url+"&username="+username;
			}
		  </script>
		  {/literal}
				</div>
				<div class="floatr">
				</div>
				</td>
			</tr>
		<tr align="center">
			<td colspan="10" align="center"><div align="center">{$loop.pages|showpage}</div></td>
		</tr>
		
		{ /list}
	</table>
	{/if}
{elseif $_A.query_type == "vip" ||  $_A.query_type == "vipview" }

	{include file="users.vip.tpl" template_dir = "modules/users"}
	
{elseif $_A.query_type == "viewinfo" ||  $_A.query_type == "viewinfo" }

	{include file="users.viewinfo.tpl" template_dir = "modules/users"}


{elseif $_A.query_type == "admin" ||  $_A.query_type == "admin_log"  ||  $_A.query_type == "admin_type" }

	{include file="users.admin.tpl" template_dir = "modules/users"}

{/if}