{if $magic.request.p=="webpay"}

	{articles module="borrow" plugins="loan"  function="GetRepayView" id='$magic.request.id' var="var"}
<div class="module_add">
	<div class="module_title"><strong>ȷ�϶Դ˽�����е渶</strong></div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><!-- <strong>��ȷ��֮ǰ�뿴�ô����̣���ֹ��̫������ִ���</strong> --></div>
	</div>
    <div class="module_border">
		<div class="l"><strong>�����⣺</strong></div>
		<div class="c">{$var.borrow_name}</div>
	</div> 
    <div class="module_border">
		<div class="l"><strong>Ӧ��ʱ�䣺</strong></div>
		<div class="c">{$var.repay_time|date_format:"Y-m-d"}</div>
	</div> 
    <div class="module_border">
		<div class="l"><strong>����������</strong></div>
		<div class="c">{$var.days}��</div>
	</div> 
    {loop module="borrow" plugins="loan" function="GetRepayLate" repay_id='$magic.request.id' var="rvar"}
	<div class="module_border">
		<div class="l"><strong>Ͷ����[{$rvar.username}]��</strong></div>
		<div class="c">Ӧ�ձ�Ϣ��{$rvar.recover_account}��Ӧ�ձ���{$rvar.recover_capital}��{if $rvar.vip_status==1}vip,{else}��ͨ��Ա��{/if}�渶��{$rvar.recover_late_account}</div>
	</div>
    {/loop}
<form name="form1" method="post" action="{$_A.query_url}/loan&p=webpay&id={$magic.request.id}" onsubmit="return confirm('��ȷ��Ҫ�渶�˽����');">
	<div class="module_border" >
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="remark" cols="45" rows="5"></textarea>
		</div>
	</div>
	<div class="module_border" >
		<div class="l">����ע:</div>
		<div class="c">
			<textarea name="contents" cols="45" rows="5"></textarea>
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="borrow_nid" value="{ $var.borrow_nid}" />
		
		<input type="submit"  name="reset" value="�����渶" class="submit" />
	</div>
	
</form>
{/articles}
</div>	
{else}

<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if  $magic.request.late_type==""} id="c_so"{/if}>���ڽ��</a></li> 
<li><a href="{$_A.query_url_all}&late_type=repay" {if  $magic.request.late_type=="repay"} id="c_so"{/if}>��վ�渶</a></li> 
<li><a href="{$_A.query_url_all}&late_type=recover" {if  $magic.request.late_type=="recover"} id="c_so"{/if}>��վӦ����ϸ��</a></li> 
</ul> 
{if $magic.request.late_type==""}

<div class="module_add">
	<div class="module_title"><strong>���ڽ���б�</strong><div style="float:right">
				 �����⣺<input type="text" name="borrow_name" id="borrow_name" value="{$magic.request.borrow_name|urldecode}" size="8"/> ����ˣ�<input type="text" name="username" id="username" value="{$magic.request.username}" size="8"/>
				 ���ͣ�{linkages name="borrow_type" nid="borrow_all_type" type="value" default="ȫ��" value="$magic.request.borrow_type"}Ӧ��ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>
				 <input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/late')"></div></div>
</div>

<!--���� ��ʼ-->
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="*" class="main_td">�����</td>
			<td width="*" class="main_td">�����</td>
			<td width="*" class="main_td">������</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">Ӧ��ʱ��</td>
			<td width="" class="main_td">Ӧ����Ϣ</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">��վ�Ƿ�渶</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">ʵ�ʻ���ʱ��</td>
			<!-- <td width="" class="main_td">����</td> -->
		</tr>
		{list module="borrow" plugins="loan" function="GetRepayList" var="loop" late_days=request repay_status=0 borrow_name="request" username="request" status_nid="late" dotime1="request" dotime2="request" order="late" borrow_type=request}
		{ foreach  from=$loop.list key=key item=item }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.borrow_nid}</td>
			<td><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}" title="�鿴">{ $item.borrow_username}</a></td>
			<td><a href="{$_A.query_url}/view&borrow_nid={$item.borrow_nid}" title="�鿴">{$item.borrow_name}</a></td>
			<td>{$item.repay_period}/{$item.borrow_period}</td>
			<td>{$item.type_title}</td>
			<td >{$item.repay_time|date_format:"Y-m-d"}</td>
			<td >��{$item.repay_account}</td>
			<td >{$item.late_days}��</td>
			<td >{if $item.repay_web==1}�ѵ渶{else}δ�渶{/if}</td>
			<td >{if $item.repay_status==1}�ѻ�{else}δ��{/if}</td>
			<td >{$item.repay_yestime|default:-}</td>
            <!-- 
			<td >{if $item.repay_web==0}<a href="{$_A.query_url_all}&p=webpay&id={$item.id}">�渶</a>{else}-{/if}</td> -->
		</tr>
		{ /foreach}
		<tr>
			<td colspan="15" class="page">
			{$loop.pages|showpage} 
			</td>
		</tr>
	</form>	
</table>
<!--���� ����-->
{elseif $magic.request.late_type=="recover"}
<div class="module_add">
	<div class="module_title"><strong>��վӦ����ϸ��</strong><div style="float:right">
			Ӧ��ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/> ״̬��<select name="recover_status" id="recover_status"><option value="" {if $magic.request.recover_status==""}selected="selected"{/if}>����</option><option value="1" {if $magic.request.recover_status==1}selected="selected"{/if}>�ѻ�</option><option value="2" {if $magic.request.recover_status==2}selected="selected"{/if}>δ��</option></select> <input type="button" value="����" / onclick="sousuo('{$_A.query_url}/late&late_type=recover')">&nbsp;&nbsp;&nbsp;</div></div>
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
			<td  >��������</td>
			<td  >ʵ��ʱ��</td>
			<td  >ʵ���ܶ�</td>
			<td  >״̬</td>
		</tr>
		{list module="borrow" var="loop" function ="GetRepayList" plugins="Loan" showpage="3" keywords="request" dotime1="request" dotime2="request" borrow_status=3 type="web" order="recover_status" recover_status=request epage="20" showtype="web"}
		{foreach from="$loop.list" item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td  ><a href="/invest/a{$item.borrow_nid}.html" target="_blank" title="{$item.borrow_name}">{$item.borrow_name|truncate:8}</a></td>
			<td  >{$item.repay_time|date_format:"Y-m-d"}</td>
			<td  ><a href="/u/{$item.borrow_userid}" target="_blank">{$item.borrow_username}</a></td>
			<td  >{$item.repay_period}/{$item.borrow_period}</td>
			<td  >��{$item.repay_web_account}</td>
			<td  >��{$item.repay_capital  }</td>
			<td  >��{$item.repay_interest  }</td>
			<td  >{$item.late_days|default:0  }��</td>
			<td  >{$item.repay_yestime|date_format:"Y-m-d"}</td>
			<td title="ʵ������[{$item.repay_capital_yes}]+ʵ����Ϣ[{$item.repay_interest_yes}]+�������[{$item.repay_fee}]">��{$item.repay_account_yes+$item.repay_fee}</td>
			<td  >{if $item.repay_status==1}<font color="#666666">�ѻ�</font>{else}<font color="#FF0000">δ��</font>{/if}</td>			
		</tr>
		{/foreach}
		<tr>
		<td colspan="14" class="action">
		<div class="floatl">
			Ӧ���ܶ{$loop.repay_all|default:0.00}Ԫ,�����ܶ{$loop.repay_yes_all|default:0.00}Ԫ
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
{elseif $magic.request.late_type=="repay"}

<div class="module_add">
	<div class="module_title">
		<strong>��վ�渶</strong>
		<div style="float:right">
		�����⣺<input type="text" name="borrow_name" id="borrow_name" value="{$magic.request.borrow_name|urldecode}" size="8"/> ����ˣ�<input type="text" name="username" id="username" value="{$magic.request.username}" size="8"/>
		���ͣ�{linkages name="borrow_type" nid="borrow_all_type" type="value" default="ȫ��" value="$magic.request.borrow_type"}����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>
		<input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/late&late_type=repay')">
		</div>
	</div>
</div>

<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">�����</td>
			<td width="*" class="main_td">������</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">Ӧ��ʱ��</td>
			<td width="" class="main_td">Ӧ�����</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">��վ�渶���</td>
			<td width="" class="main_td">�渶ʱ��</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">����</td>
		</tr>
		{list module="borrow" function="GetRepayList" plugins="Loan" var="loop" late_days=request repay_status=0 borrow_name="request" username="request" borrow_type="request" dotime1="request" dotime2="request" order="late" status_nid="late"}
		{ foreach  from=$loop.list key=key item=item }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td >{ $item.id}</td>
			<td >{ $item.borrow_username}</td>
			<td><a href="/invest/a{$item.borrow_nid}.html" target="_blank">{$item.borrow_name}</a></td>
			<td>{$item.repay_period}/{$item.borrow_period}</td>
			<td>{$item.borrow_type|linkages:"borrow_all_type"|default:"$item.borrow_type"}</td>
			<td >{$item.repay_time|date_format:"Y-m-d"}</td>
			<td >��{$item.repay_account }</td>
			<td >{$item.late_days}��</td>
			<td >��{$item.repay_web_account}</td>
			<td >{$item.repay_web_time|date_format:"Y-m-d"|default:-}</td>
			<td >{if $item.repay_web==1}��վ�ѵ渶{else}δ�渶{/if}</td>
			<td >
			{if $item.webpay_status==1  &&  $item.repay_web==0 }
				<a href="{$_A.query_url_all}&p=webpay&id={$item.id}&borrow_nid={$item.borrow_nid}">�渶</a>
			{else}
				-
			{/if}
			</td>
		</tr>
		{ /foreach}
		<tr>
			<td colspan="15" class="page">
			{$loop.pages|showpage} 
			</td>
		</tr>
	</form>	
</table>
{/if}
{/if}

<script>

var urls = '{$_A.query_url}/late&late_days={$magic.request.late_days}';
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
	var status = $("#status").val();
	if (status!="" && status!=null){
		sou += "&status="+status;
	}
	var is_vouch = $("#is_vouch").val();
	if (is_vouch!="" && is_vouch!=null){
		sou += "&is_vouch="+is_vouch;
	}
	if(url==""){
		location.href=urls+sou;
	}else{
		location.href=url+sou;
	}
	
}
</script>
{/literal}