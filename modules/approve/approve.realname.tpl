{literal}
<script>
function CheckExamine(){
	if ($("#verify_remark").val()==""){
		alert("��ע����Ϊ��");
		return false;
	}
}
</script>
{/literal}
{if $magic.request.id5!=""}
<div  style="height:205px; overflow:hidden">
	<form name="form1" method="post" action="{$_A.query_url_all}&id5={$magic.request.id5}" onsubmit="return CheckExamine()" >
	<div class="module_border_ajax">
		<div class="c">
		<strong>˵����</strong>���Ҫ����ID5��֤������ȷ�����ID5�Ѿ�ǩ����Э�飬ͬʱ��̨��ߵ�id5��֤�����۳��û����κη��á�</div>
	</div>
	
	<div class="module_border_ajax" >
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5" id="verify_remark"></textarea>
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">��֤��:</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4"  tabindex="3" />
		</div>
		<div class="c">
			<img src="/?plugins&q=imgcode" id="valicode1" alt="���ˢ��" onClick="this.src='/?plugins&q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>

	<div class="module_submit_ajax" >
		<input type="hidden" name="borrow_nid" value="{ $magic.request.check}" />{literal}
		<input type="submit"  name="submit" class="submit_button" value="ȷ�����"  />{/literal}
	</div>
	
</form>
</div>
{elseif $magic.request.examine!=""}
<div  style="height:205px; overflow:hidden">
	<form name="form1" method="post" action="{$_A.query_url_all}&examine={$magic.request.examine}" onsubmit="return CheckExamine()">
	<div class="module_border_ajax">
		<div class="l">���:</div>
		<div class="c">
		{input type="radio" value="1|ͨ��,2|��ͨ��,0|���" name="status" checked="$_A.approve_result.status"}
	</div>
	
	<div class="module_border_ajax" >
		<div class="l">��˱�ע2:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="7">{$_A.approve_result.verify_remark}</textarea>
		</div>
	</div>
	<div class="module_border_ajax" >
		<div class="l">��֤��:</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4"  tabindex="3" />
		</div>
		<div class="c">
			<img src="/?plugins&q=imgcode" id="valicode1" alt="���ˢ��" onClick="this.src='/?plugins&q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>

	<div class="module_submit_ajax" >
		<input type="hidden" name="borrow_nid" value="{ $magic.request.check}" />
		<input type="submit"  name="reset" class="submit_button" value="ȷ�����" />
	</div>
	
</form>
</div>
{else}
<ul class="nav3">
<li><a href="{$_A.query_url}/realname" {if $magic.request.action==""}style="color:red"{/if} >ʵ����֤</a></li> 
<li><a href="{$_A.query_url}/realname&action=id5list" {if $magic.request.action=="id5list"}style="color:red"{/if}>ID5��֤</a></li>
</ul> 

{if $magic.request.action=="id5list"}
<div class="module_add">
	<div class="module_title"><strong>ID5��֤�б�</strong><span style="float:right">
		�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> ��ʵ������<input type="text" name="realname" id="realname" value="{$magic.request.realname|urldecode}"/>  ���֤�ţ�<input type="text" name="card_id" id="card_id" value="{$magic.request.card_id}"/>   <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="sousuo()"></span></div>
	</div>
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">�û�</td>
		<td width="*" class="main_td">��ʵ����</td>
		<td width="*" class="main_td">���֤����</td>
		<td width="*" class="main_td">״̬</td>
		<td width="*" class="main_td">���</td>
		<td width="*" class="main_td">ͷ��</td>
		<td width="*" class="main_td">ʱ��</td>
	</tr>
	
	{ list module="approve" function="GetId5List" var="loop" username=request  realname=request  card_id=request status=request  epage=20 }
	{foreach from="$loop.list" item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.realname}</td>
		<td class="main_td1" align="center">{$item.card_id}</td>
		<td class="main_td1" align="center">{$item.status|linkages:"approve_cardid_status"}</td>
		<td class="main_td1" align="center">{$item.value}</td>
		<td class="main_td1" align="center">{if $item.status==3}<a href="{$item.user_id|idcard}" target="_blank"><img src="{$item.user_id|idcard}" width="40" /></a>{else}-{/if}</td>
		<td class="main_td1" align="center">{$item.addtime|date_format}</td>
	</tr>
	{/foreach}
	<tr>
			<td colspan="15" class="action">
			<div class="floatl">
			<script>
			  var url = '{$_A.query_url_all}&action=id5list';
				{literal}
				function sousuo(){
					var username = $("#username").val();
					var realname = $("#realname").val();
					var card_id = $("#card_id").val();
					location.href=url+"&username="+username+"&realname="+realname+"&card_id="+card_id;
				}
			  </script>
			  {/literal}
			</div>
			<div class="floatr">
			</div>
			</td>
		</tr>
		
	<tr align="center">
		<td colspan="14" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	{/list}
</table>


{else}

<div class="module_add">
	<div class="module_title"><strong>�����֤</strong></div>
	<div style="margin-top:10px;">
	<div style="float:left; width:30%;">
		
	<div style="border:1px solid #CCCCCC; ">
	
	{if $magic.request.user_id==""}
	<form action="{$_A.query_url_all}" method="post">
	<div class="module_title"><strong>�����û�</strong>(����˳���������)<input type="hidden" name="type" value="user_id" /></div>
	
	
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username" />
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">�û�ID��</div>
		<div class="c">
			<input type="text" name="user_id" />
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">���䣺</div>
		<div class="c">
			<input type="text" name="email" />
		</div>
	</div>
	
	<div class="module_submit"><input type="submit" value="ȷ���ύ" class="submit_button" /></div>
		</form>
	</div>
	{else}
	<form action="" method="post" enctype="multipart/form-data">
	<div class="module_title"><strong>�޸������֤��Ϣ</strong><input type="hidden" name="user_id" value="{$magic.request.user_id}" /></div>
	
	<div class="module_border">
	<div class="l">�� �� �� ��</div>
		<div class="c">
			{$_A.approve_result.username}
		</div>
	</div>
	
	
	<div class="module_border">
	<div class="l">״&nbsp;&nbsp;̬��</div>
		<div class="c">
			{input value="1|ͨ��,2|��ͨ��,0|���" name="status" checked="$_A.approve_result.status"}
		</div>
	</div>
	
	{if $_A.approve_result.verify_remark!=""}
	<div class="module_border">
	<div class="l"><font color="#FF0000">��˱�ע��</font></div>
		<div class="c">
			{$_A.approve_result.verify_remark}
		</div>
	</div>
	{/if}
	
	<div class="module_border">
	<div class="l">��ʵ������</div>
		<div class="c">
			<input type="text" name="realname" value="{$_A.approve_result.realname}"/>
		</div>
	</div>
	
	<div class="module_border">
	<div class="l">�Ա�</div>
		<div class="c">
			<input type="radio" name="sex"  id="sex" value="1" {if $_A.approve_result.sex=="1" || $_A.approve_result.sex==""}checked="checked" {/if} />�� <input type="radio"  id="sex" name="sex" value="2" {if $_A.approve_result.sex=="2"}checked="checked" {/if} />Ů 			
		</div>
	</div>
	
	<div class="module_border">
	<div class="l">���֤�ţ�</div>
		<div class="c">
			<input type="text" name="card_id" value="{$_A.approve_result.card_id_admin}"/>
		</div>
	</div>
	
	
	
	<div class="module_border">
	<div class="l">���֤���棺</div>
		<div class="c">
			<input type="file" name="card_pic1" style=" width:200px" value="{ $_A.approve_result.card_pic1}"/>{if $_A.approve_result.card_pic1!=""}<a href="./{ $_A.approve_result.card_pic1_url}" target="_blank" title="��ͼƬ"><img src="{ $_A.tpldir  }/images/ico_yes.gif" border="0"  /></a>{/if}
		</div>
	</div>
	
	
	
	<div class="module_border">
	<div class="l">���֤���棺</div>
		<div class="c">
			<input type="file" name="card_pic2" size="6"  style=" width:200px" value="{ $_A.approve_result.card_pic2}" />{if $_A.approve_result.card_pic2!=""}<a href="./{ $_A.approve_result.card_pic2_url}" target="_blank" title="��ͼƬ"><img src="{ $_A.tpldir }/images/ico_yes.gif" border="0"  /></a>{/if}
		</div>
	</div>
	
	<div class="module_border" >
	<div class="l">�� ֤ �� ��</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4"/>
		
			<img src="/?plugins&q=imgcode" id="valicode" alt="���ˢ��" onClick="this.src='/?plugins&q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	
	
	<div class="module_submit"><input type="submit" value="ȷ���ύ" class="submit_button" /></div>
		</form>
	</div>
	{/if}
	</div>
		</div>
	<div style="float:right; width:67%; text-align:left">
	<div class="module_add">
	<div class="module_title"><strong>ʵ����֤�б�</strong><span style="float:right">
	״̬��<select name="status" id="status">
			<option value="" {if $magic.request.status==""}selected="selected"{/if}>����</option>
			<option value="0" {if $magic.request.status=="0"}selected="selected"{/if}>δ���</option>
			<option value="1" {if $magic.request.status=="1"}selected="selected"{/if}>���ͨ��</option>
			<option value="2" {if $magic.request.status=="2"}selected="selected"{/if}>��˲�ͨ��</option>
		  </select>�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" size="7"/> ��ʵ������<input type="text" size="7" name="realname" id="realname" value="{$magic.request.realname|urldecode}"/>  ���֤�ţ�<input type="text" name="card_id" id="card_id" value="{$magic.request.card_id}" size="7"/>   <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="sousuo()"></span></div>
	</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">�û�</td>
		<td width="" class="main_td">��ʵ����</td>
		<td width="" class="main_td">���֤��</td>
		<td width="*" class="main_td">�Ա�</td>
		<td width="*" class="main_td">����</td>
		<td width="*" class="main_td">����</td>
		<td width="*" class="main_td">ID5��֤</td>
		<td width="*" class="main_td">״̬</td>
		<td width="*" class="main_td">ʱ��</td>
		<td width="*" class="main_td">����</td>
	</tr>
	{ list module="approve" function="GetRealnameList" var="loop" username=request  realname=request  card_id=request status=request  epage=20 }
	{foreach from="$loop.list" item="item"}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.realname}</td>
		<td class="main_td1" align="center">{$item.card_id}</td>
		<td class="main_td1" align="center">{if $item.sex==1}��{else}Ů{/if}</td>
		<td class="main_td1" align="center">{if $item.card_pic1==""}-{else}<a href="./{ $item.card_pic1_url}" target="_blank" title="��ͼƬ"><img src="{ $_A.tpldir  }/images/ico_yes.gif" border="0"  /></a>{/if}</td>
		<td class="main_td1" align="center">{if $item.card_pic2==""}-{else}<a href="./{ $item.card_pic2_url}" target="_blank" title="��ͼƬ"><img src="{ $_A.tpldir }/images/ico_yes.gif" border="0"  /></a>{/if}</td>
		<td class="main_td1" align="center"><a href="javascript:void(0)" onclick='tipsWindown("ID5��֤���","url:get?{$_A.query_url_all}&id5={$item.user_id}",500,230,"true","","false","text");'>{if $item.id5_status==0}δ��֤{elseif $item.id5_status==1}����֤{else}��ͨ��{/if}</a></td>
		<td class="main_td1" align="center">{if $item.status==0}�����{elseif $item.status==1}��֤ͨ��{else}��ͨ��{/if}</td>
		<td class="main_td1" align="center">{$item.addtime|date_format:"Y-m-d"}</td>
		<td class="main_td1" align="center"><a href="javascript:void(0)" onclick='tipsWindown("�˹����","url:get?{$_A.query_url_all}&examine={$item.user_id}",500,230,"true","","false","text");'/>���</a>/<a href="{$_A.query_url_all}&user_id={$item.user_id}&page={$magic.request.page}">�޸�</a></td>
	</tr>
	{/foreach}
	<tr>
			<td colspan="15" class="action">
			<div class="floatl">
			<script>
			  var url = '{$_A.query_url_all}';
				{literal}
				function sousuo(){
					var username = $("#username").val();
					var realname = $("#realname").val();
					var card_id = $("#card_id").val();
					var status = $("#status").val();
					location.href=url+"&username="+username+"&realname="+realname+"&card_id="+card_id+"&status="+status;
				}
			  </script>
			  {/literal}
			</div>
			<div class="floatr">
			</div>
			</td>
		</tr>
		
	<tr align="center">
		<td colspan="14" align="center"><div align="center">{$loop.pages|showpage}</div></td>
	</tr>
	{/list}
	
</table>
<!--�˵��б� ����-->
</div>
</div>

	{/if}
{/if}
