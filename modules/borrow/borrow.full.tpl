{if $magic.request.p=="verify"}

	{articles module="borrow"  function="GetView" borrow_nid='$magic.request.borrow_nid' var="var"}
<div class="module_add">
	<div class="module_title"><strong>ȷ���Ƿ���˴˽���</strong></div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><strong>��ȷ��֮ǰ�뿴�ô����̣���ֹ��̫������ִ���</strong></div>
	</div>
    <div class="module_border">
		<div class="l"><strong>������</strong></div>
		<div class="c">{$var.name}</div>
	</div>
	<div class="module_border">
		<div class="l">��һ����</div>
		<div class="c">���½���˵Ľ����Ϣ��</div>
	</div>
	<div class="module_border">
		<div class="l">�ڶ�����</div>
		<div class="c">����Ͷ���˵���Ϣ������Ͷ���˵��ʽ𶳽�ͷ���Ͷ���˵Ĵ��ս��</div>
	</div>
	
	<div class="module_border">
		<div class="l">��������</div>
		<div class="c">������ʽ����Ӻͻ��ֵĹ���</div>
	</div>
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">�����û��ִ�е������ӵ�һ������ִ�С�</div>
	</div>
<form name="form1" method="post" action="{$_A.query_url}/loan&p=reverify&borrow_nid={$magic.request.borrow_nid}" onsubmit="return confirm('��ȷ��Ҫ��˴˽����');">
    {if $_A.borrow_result.status>1}
    <div class="module_border">
		<div class="l">״̬:</div>
		<div class="c"><strong><input type="hidden" name="status" value="{$_A.borrow_result.status}"/>{if $_A.borrow_result.status==3}����ͨ��{else}����ͨ��{/if}</strong></div>
	</div>
    {else}

	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
		<input type="radio" name="status" value="3"  {if $var.reverify_status==3} checked=""{/if}/>����ͨ�� <input type="radio" name="status" value="4"   {if $var.reverify_status==4 ||  $var.reverify_status==""} checked=""{/if}/>����ͨ�� </div>
	</div>
	{/if}
	<div class="module_border" >
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="remark" cols="45" rows="5">{ $var.reverify_remark}</textarea>
		</div>
	</div>
	<div class="module_border" >
		<div class="l">����ע:</div>
		<div class="c">
			<textarea name="contents" cols="45" rows="5">{ $var.reverify_contents}</textarea>
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{ $var.id }" />
		<input type="hidden" name="borrow_nid" value="{ $var.borrow_nid}" />
		
		<input type="submit"  name="reset" value="��˴˽���" class="submit" />
	</div>
	
</form>
{/articles}
</div>	
{elseif $magic.request.fullcheck==""}
<ul class="nav3"> 
<li><a href="{$_A.query_url_all}&status_nid=" {if  $magic.request.status_nid=="" || $magic.request.status_nid==""} id="c_so"{/if}>�������</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=full_false" title="ָ���겻ͨ����Ͷ�ʶ��ѷ�����"{if  $magic.request.status_nid=="full_false"} id="c_so"{/if}>�������ʧ��</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=repay" title="ָ���ڻ���"{if  $magic.request.status_nid=="repay"} id="c_so"{/if}>���ڻ�����</a></li> 
<li><a href="{$_A.query_url_all}&status_nid=repay_yes" title="ָ��ȫ������"{if  $magic.request.status_nid=="repay_yes"} id="c_so"{/if}>�ѻ�����</a></li> 
</ul> 
<form action="" method="post"> 
<div class="module_add">
	<div class="module_title"><strong>����б�</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr >
			<td width="*" class="main_td">�����</td>
			<td width="*" class="main_td">�û�����</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">�ѽ���</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">���ʽ</td>
			<td width="" class="main_td">�������</td>
			<!--<td width="" class="main_td">����</td>-->
			<td width="" class="main_td">Ͷ�ʴ���</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">�鿴</td>
			
		</tr>
		{ list  module="borrow" function="GetList" var="loop" borrow_name="request" type="request" borrow_type="request" borrow_nid="request" username="request"  query_type="full"  status_nid="request"  }
		{foreach from="$loop.list" item="item"}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.borrow_nid}</td>
			<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=code/users/info_view&user_id={$item.user_id}" title="�鿴">{$item.username}</a></td>
			<td title="{$item.name}"><a href="{$_A.query_url}/view&borrow_nid={$item.borrow_nid}" title="�鿴">{$item.name|truncate:10}</a></td>
			<td>{$item.account}Ԫ</td>
			<td>{$item.borrow_account_yes}Ԫ</td>
			<td>{$item.borrow_apr}</td>
			<td>{$item.borrow_period_name}</td>
			<td>{$item.style_title}</td>		
			
			<td>{$item.type_name}</td>
			<!--<td>{$item.borrow_flag|linkages:"borrow_flag"|default:"-"}</td>-->
			<td width="" class="main_td">{$item.tender_times}��</td>
			<td>{$item.borrow_status_nid|linkages:borrow_status}
			<!-- {$item.borrow_type_nid|linkages:"borrow_type_nid"|default:"$item.borrow_type_nid"} --></td>
			<td title="���/�鿴"><a href="{$_A.query_url}/view&borrow_nid={$item.borrow_nid}">�鿴</a></td>
			
		</tr>
		{ /foreach}
		<tr>
		<td colspan="14" class="action">
		<div class="floatl">
			
		</div>
		<div class="floatr">
			 ���⣺<input type="text" name="borrow_name" id="borrow_name" value="{$magic.request.borrow_name|urldecode}" size="8"/> �û�����<input type="text" name="username" id="username" value="{$magic.request.username}" size="8"/>����ţ�<input type="text" name="borrow_nid" id="borrow_nid" value="{$magic.request.borrow_nid}" size="8"/> 
			 <!--
			 <select id="is_vouch" ><option value="">ȫ��</option><option value="1" {if $magic.request.is_vouch==1} selected="selected"{/if}>������</option><option value="0" {if $magic.request.is_vouch=="0"} selected="selected"{/if}>��ͨ��</option></select> 
			 -->
			 {linkages name="borrow_type" nid="borrow_all_type" type="value" default="ȫ��" value="$magic.request.borrow_type"}
			 <input type="button" value="����" class="submit" onclick="sousuo('{$_A.query_url}/full&type={$magic.request.type}&status_nid={$magic.request.status_nid}')">
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="14" class="page">
			{$loop.pages|showpage}
			</td>
		</tr>
		{/list}
</table>
</form>
<script>

var urls = '{$_A.query_url}/full&type={$magic.request.type}&status_nid={$magic.request.status_nid}';
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
	
		location.href=urls+sou;
	
}
</script>
{/literal}
</div>
{/if}