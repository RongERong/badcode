{if $magic.request.p=="type_edit" || $magic.request.p=="type_new"}

<div class="module_add">
	<div class="module_title"><strong>{if $magic.request.p=="edit"}�޸�{else}���{/if}����������</strong></div>
<form action="{$_A.query_url_all}&p={$magic.request.p}" method="post">

	<div class="module_border">
		<div class="l">�������ƣ�</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{$_A.account_fee_result.name}"   size="20" />
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">��ʶ����</div>
		<div class="c">
			<input type="text" name="nid"  class="input_border" value="{$_A.account_fee_result.nid}"   size="20" />
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			<input type="radio" name="status"  class="input_border" value="1" {if $_A.account_fee_result.status==1} checked=""{/if}  />���� 
			<input type="radio" name="status"  class="input_border"  value="0" {if $_A.account_fee_result.status==0} checked=""{/if} />�ر�
		</div>
	</div>
	
    
	<div class="module_border">
		<div class="l">�걸ע˵����</div>
		<div class="c">
            <textarea name="remark">{$_A.account_fee_result.remark}</textarea>
		</div>
	</div>
    
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<input type="submit"  name="submit" value="ȷ���ύ" />
		<input type="reset"  name="reset" value="���ñ�" />
        {if $magic.request.id!=""}
        <input type="hidden" name="id" value="{$magic.request.id}" />{/if}
		</div>
	</div>
</form>
 </div>   
    {/articles}
{else}
<div class="module_add">
	<div class="module_title"><strong>����������</strong> <a href="{$_A.query_url_all}&p=type_new">����ӽ��������͡�</a></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td">��ʶ��</td>
			<td width="*" class="main_td" >״̬</td>
			<td width="" class="main_td">����</td>
		</tr>
		{loop module="account" plugins="fee" function="GetFeeTypeList"  var="item" limit="all" }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" align="center">{$item.name}</td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center">{if $item.status==1}<font color='green'>����</font>{else}�ر�{/if}</td>
            <td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=type_edit&id={$item.id}">�޸�</a> | <a href="#" onClick="javascript:if(confirm('ȷ��Ҫɾ����?ɾ���󽫲��ɻָ�')) location.href='{$_A.query_url_all}&p=type_del&id={$item.id}'">ɾ��</a></td>
		</tr>
		{ /loop}
	</form>	
</table>
{/if}