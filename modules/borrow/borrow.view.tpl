{if $_A.borrow_result.borrow_type=="roam"}
    {include file="borrow.view_roam.tpl" template_dir="modules/borrow"}
{else}
<div class="module_add" >
	
	<div class="module_title"><strong>���������Ϣ</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="r">
		<a href="{$_A.admin_url}&q=code/users/info_view&user_id={$_A.borrow_result.user_id}">	{$_A.borrow_result.username}</a>
		</div>
		<div class="s">���⣺</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.name}
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">״̬��
        	 {if $_A.borrow_result.borrow_status_nid=="first" }
             <input type="button"  src="{$_A.tpldir}/images/button.gif" align="absmiddle" value="������" class="submit_button" onclick='tipsWindown("������","url:get?{$_A.query_url}/first&check={$_A.borrow_result.borrow_nid}",500,300,"true","","false","text");'/>
        	{elseif $_A.borrow_result.borrow_full_status!=1 && ($_A.borrow_result.borrow_status_nid=="full" || $_A.borrow_result.type_part_status==1)}�������<input type="button"  src="{$_A.tpldir}/images/button.gif" align="absmiddle" value="�������" class="submit_button" onclick='location.href="{$_A.query_url}/full&p=verify&borrow_nid={$_A.borrow_result.borrow_nid}"'/>          
             {else}
             {$_A.borrow_result.borrow_status_nid|linkages:"borrow_status"}
             {/if}</div>
	</div>	
	
	
	<div class="module_border">
		<div class="l">����ţ�</div>
		<div class="r">
			{$_A.borrow_result.borrow_nid}
		</div>
		<!-- <div class="s">�����;��</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.borrow_use|linkages:"borrow_use"}
		</div> -->
		<div class="s">���۴�����</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.comment_count}��
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">��Чʱ�䣺{$_A.borrow_result.borrow_valid_time}�� </div>
	</div>
	
	<div class="module_border">
		<div class="l">������ͣ�</div>
		<div class="r">
			{$_A.borrow_result.type_title}
		</div>
		<div class="s">���ʽ��</div>
		<div class="c" style="width:220px">
        {$_A.borrow_result.style_name}
			</div>
		<div style="float:left;padding:4px 5px 0 0px;">����ܽ���{$_A.borrow_result.account}<input type="hidden" name="account" value="{$_A.borrow_result.account}" /> </div>
	</div>
	
	<div class="module_border">
		<div class="l">�����ʣ�</div>
		<div class="r">
			{$_A.borrow_result.borrow_apr} %
		</div>
		<div class="s">������ޣ�</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.borrow_period_name}
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">�Ƿ񲿷ֽ�
			{$_A.borrow_result.borrow_part_status|linkages:"borrow_part_status"|default:-}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���Ͷ���</div>
		<div class="r">
			{$_A.borrow_result.tender_account_min}
		</div>
		<div class="s">���Ͷ���ܶ</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.tender_account_max}
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">���������
			{$_A.borrow_result.hits|default:0}��
		</div>
	</div>
	
	<!-- <div class="module_border">
		<div class="l">�������ͣ�</div>
		<div class="r">
			 {$_A.borrow_result.award_status|linkages:"borrow_award_status"}{if $_A.borrow_result.award_false==1}(���ʧ��Ҳ����){/if}
		</div>
		<div class="s">������ʽ��</div>
		<div class="c" style="width:220px">
			{if $_A.borrow_result.award_status==1}
				��{$_A.borrow_result.award_account}
			{elseif $_A.borrow_result.award_status==2}
				{$_A.borrow_result.award_scale}%
                {else}
                ������
			{/if}
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">���۴�����{$_A.borrow_result.comment_count}��</div>
	</div> -->
	
	<div class="module_border">
		<div class="l">���ʱ�䣺</div>
		<div class="r">
			{$_A.borrow_result.addtime|date_format}
		</div>
		<div class="s">���IP��</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.addip}
		</div>
		<div style="float:left;padding:4px 5px 0 0px;"></div>
	</div>
	
	<div class="module_title"><strong>��������</strong></div>
	<table width="100%" >
  <tr >
    <td width="" class="main_td">������� </td>
    <td width="" class="main_td">��˽�� </td>
    <td width="" class="main_td">���ʱ�� </td>
    <td width="" class="main_td">�����Ա </td>
    <td width="" class="main_td">��˱�ע </td>
    <td width="" class="main_td">����ע </td>
  </tr>
  {loop module="borrow" plugins="loan" function="GetVerifyList" limit="all" borrow_nid='$_A.borrow_result.borrow_nid'}
  <tr >
    <td>{$var.type_name}</td>
    <td>{$var.status_name} </td>
    <td>{$var.addtime|date_format} </td>
    <td>{$var.username|default:ϵͳ} </td>
    <td>{$var.remark} </td>
    <td>{$var.contents} </td>
  </tr>
  {/loop}
  </table>
	
	{if $_A.borrow_result.status>0}
	<div class="module_title"><strong>���״̬</strong> <!--(<a href="{$_A.query_url}/tender&borrow_nid={$_A.borrow_result.borrow_nid}">�鿴Ͷ����Ϣ</a>)--></div>
	
	
	<div class="module_border">
		<div class="l">�ѽ赽�Ľ�</div>
		<div class="r">
			<font color="#009900">��{$_A.borrow_result.borrow_account_yes}</font>
		</div>
		<div class="s">δ�赽�Ľ�</div>
		<div class="c" style="width:220px">
			<font color="#FF0000">��{$_A.borrow_result.borrow_account_wait}</font>
		</div>
	</div>
	

	{if $_A.borrow_result.status>=1}
	
	<div class="module_title"><strong>Ͷ�����飺</strong></div>
  <table width="100%">
  <tr >
    <td colspan="2" >��Ͷ��Ľ�<font style="color:#009900">��{$_A.borrow_result.borrow_account_yes}</font></td>
    <td colspan="2" >��Ͷ��Ľ�<font style="color:#FF0000">��{$_A.borrow_result.borrow_account_wait}</font></td>
    <td colspan="2" >Ͷ�������{$_A.borrow_result.tender_times}��</td>
  </tr>
  <tr >
    <td width="" class="main_td" >ID </td>
    <td width="" class="main_td" >Ͷ���� </td>
    <td width="" class="main_td" >Ͷ�ʽ�� </td>
    <td width="" class="main_td" >��ЧͶ�ʽ�� </td>
    <td width="" class="main_td" >Ͷ��ʱ�� </td>
    <td width="" class="main_td" >Ͷ������ </td>
  </tr>
	{ loop module="borrow" function="GetTenderList" plugins="Tender" limit="all" borrow_nid='$_A.borrow_result.borrow_nid' var="item"}
	<tr  {if $key%2==1} class="tr2"{/if}>
		<td>{ $item.id}<input type="hidden" name="id[]" value="{ $item.id}" /></td>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}" title="�鿴">{$item.username}</a></td>
		<td>{$item.account_tender}Ԫ</td>
		<td>{$item.account}Ԫ</td>
		<td>{$item.addtime|date_format}</td>
		<td>{$item.contents}</td>
	</tr>
	{ /loop}
  </table>
	
	{/if}
	
	{/if}
	
		{if $_A.borrow_result.status>1}
  <div class="module_title"><strong>�������飺</strong></div>
  <table width="100%">
  <tr >
    <td colspan="2">����ܶ��{$_A.borrow_result.account}</td>
    <td colspan="2">Ӧ���ܶ��{$_A.borrow_result.repay_account_all}</td>
    <td colspan="2">Ӧ����Ϣ����{$_A.borrow_result.repay_account_interest}</td>
   </tr>
  <tr >
    <td colspan="2">�ѻ��ܶ<font style="color:#009900">��{$_A.borrow_result.repay_account_yes}</font></td>
    <td colspan="2">�ѻ����𣺣�{$_A.borrow_result.repay_account_capital_yes}</td>
    <td colspan="2">�ѻ���Ϣ����{$_A.borrow_result.repay_account_interest_yes}</td>
  </tr>
  {if $_A.borrow_result.repay_advance_status==1}
  <tr >
    <td colspan="2">δ���ܶ0</td>
    <td colspan="2">δ�����𣺣�{$_A.borrow_result.repay_account_capital_wait}</td>
    <td colspan="2">��ʧ��Ϣ����{$_A.borrow_result.repay_account_interest_lost}</td>
  </tr>
  {else}
  <tr >
    <td colspan="2">δ���ܶ<font style="color:#FF0000">��{$_A.borrow_result.repay_account_wait}</font></td>
    <td colspan="2">δ�����𣺣�{$_A.borrow_result.repay_account_capital_wait}</td>
    <td colspan="2">δ����Ϣ����{$_A.borrow_result.repay_account_interest_wait}</td>
  </tr>
  {/if}
  <tr >
    <td colspan="2">�ѻ�����������ã���{$_A.borrow_result.repay_fee_normal}</td>
    <td colspan="2">�ѻ���ǰ������ã���{$_A.borrow_result.repay_fee_advance}</td>
    <td colspan="2">�ѻ����ڻ�����ã���{$_A.borrow_result.repay_fee_late}</td>
  </tr>
  <tr >
    <td valign="center" class="main_td">���� </td>
    <td valign="center" class="main_td">Ӧ��������+��Ϣ�� </td>
    <td valign="center" class="main_td">Ӧ��ʱ�� </td>
    <td valign="center" class="main_td">ʵ��ʱ�� </td>
    <td valign="center" class="main_td">ʵ������ </td>
    <td valign="center" class="main_td">ʵ����Ϣ </td>
    <td valign="center" class="main_td">�������� </td>
    <td valign="center" class="main_td">״̬ </td>
    <td valign="center" class="main_td">��վ�Ƿ�渶 </td>
    <td valign="center" class="main_td">�渶ʱ�� </td>
    <td valign="center" class="main_td">�渶��� </td>
  </tr>
  {loop module="borrow" plugins="loan" function="GetRepayList" limit="all" borrow_nid='$_A.borrow_result.borrow_nid' var="item"}
  <tr >
    <td>{$item.repay_period}</td>
    <td>{$item.repay_account}</td>
    <td>{$item.repay_time|date_format:"Y-m-d"}</td>
    <td>{$item.repay_yestime|date_format:"Y-m-d"|default:'-'}</td>
    <td>{$item.repay_capital_yes}</td>
    <td>{$item.repay_interest_yes}</td>
    <td>{$item.late_days}</td>
    <td>{$item.repay_type_name}</td>
    <td>{if $item.repay_web==1}��{else}��{/if}</td>
	<td >{$item.repay_web_time|date_format:"Y-m-d"}</td>
	<td >��{$item.repay_web_account}</td>

  </tr>
  {/loop}
  </table>
  
  	{/if}
  

	
	
	<div class="module_title"><strong>�������</strong></div>
	<div class="module_border" >
		{$_A.borrow_result.borrow_contents}	
	</div>
	
	<!--div class="module_title"><strong>��Ѻ������</strong></div>
	<div class="module_border" >
		<textarea id="diya_contents" name="diya_contents" rows="22" cols="200" style="width: 80%">{$_A.borrow_result.diya_contents}</textarea>
	</div-->
	
	{if $_A.borrow_result.vouch_status==1}
	<div class="module_title"><strong>��������</strong></div>
	<div class="module_border">
		<div class="l">�Ƿ���н�����</div>
		<div class="c" style="width:220px">
			{if $_A.borrow_result.vouch_award_status==1}��{else}��{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�Ƿ�̶���Ҫ�����ˣ�</div>
		<div class="r">
			{ $_A.borrow_result.vouch_user_status|linkages:"borrow_vouch_user_status"}
		</div>
		<div class="s">�̶������ˣ�</div>
		<div class="c" style="width:220px">
			{if $_A.borrow_result.vouch_user_status==0}-{else}{ $_A.borrow_result.vouch_users|deault:-}{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�ܵ�����</div>
		<div class="r">
			��{$_A.borrow_result.vouch_account}
		</div>
		<div class="s">�ѵ���������</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.vouch_account_scale }%
		</div>
	</div>
	<div class="module_border">
		
		<div class="l">�ѵ�����</div>
		<div class="r">
			��{$_A.borrow_result.vouch_account_yes }
		</div>
		<div class="s">δ������</div>
		<div class="c" style="width:220px">
			��{$_A.borrow_result.vouch_account_wait }
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">�Ƿ񵣱�������</div>
		<div class="r">
			{$_A.borrow_result.vouch_award_status|linkages:"borrow_vouch_award_status" }
		</div>
		<div class="s">����������ʽ��</div>
		<div class="c" style="width:220px">
			{if $_A.borrow_result.vouch_award_status==2}
			 ��{$_A.borrow_result.vouch_award_account}
			 {else}
			 {$_A.borrow_result.vouch_award_scale}%
			 {/if}
		</div>
	</div>

	
	<div class="module_title"><strong>�����б�</strong></div>
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">������</td>
			<td width="*" class="main_td">�������</td>
			<td width="" class="main_td">��Ч���</td>
			<td width="" class="main_td">����ʱ��</td>
			<td width="" class="main_td">��������</td>
		</tr>
		{ loop module="borrow" function="GetVouchList" limit="all" borrow_nid='$_A.borrow_result.borrow_nid' var="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.id}<input type="hidden" name="id[]" value="{ $item.id}" /></td>
			<td class="main_td1" align="center"><a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/users/view&user_id={$item.user_id}",500,230,"true","","true","text");'>{$item.username}</a></td>
			<td>{$item.account_vouch}Ԫ</td>
			<td>{$item.account}Ԫ</td>
			<td>{$item.addtime|date_format}</td>
			<td>{$item.contents}</td>
		</tr>
		{ /loop}
</table>
	{/if}
</div>
{/if}