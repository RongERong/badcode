{if $magic.request.p=="edit"}
<div class="module_add">
	<div class="module_title"><strong>�޸Ļ��ʽ</strong></div>
    <form action="{$_A.query_url_all}&p=edit" method="post">
    {articles module="borrow" function="GetStyleOne" plugins="style" id="$magic.request.id" var="item"}
	
	<div class="module_border">
		<div class="l">��ʽ�������ƣ�</div>
		<div class="c">
	       	{$item.name}
		</div>
	</div>
    
    <div class="module_border">
		<div class="l">��ʶ����</div>
		<div class="c">
			{$item.nid}<input type="hidden" name="id" value="{$item.id}" />
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">���ƣ�</div>
		<div class="c">
			<input type="text" name="title"  class="input_border" value="{$item.title}"   size="20" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬</div>
		<div class="c">
			<input type="radio" name="status"  class="input_border" value="1" {if $item.status==1} checked=""{/if}  />���� 
			<input type="radio" name="status"  class="input_border"  value="0" {if $item.status==0} checked=""{/if} />�ر�
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�㷨��</div>
		<div class="c">
        <textarea name="contents" cols="50" rows="5">{$item.contents}</textarea>
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<input type="submit"  name="submit" value="ȷ���ύ" />
		<input type="reset"  name="reset" value="���ñ�" />
		</div>
	</div>
</form>
 </div>   
    {/articles}
{else}
<div class="module_add">
	<div class="module_title"><strong>���н��ʽ</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">���ʽ</td>
			<td width="*" class="main_td">��ʶ��</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td" title="һ���رգ�������վ��������">״̬</td>
			<td width="" class="main_td">�㷨��Ϣ</td>
			<td width="" class="main_td">����</td>
		</tr>
		{loop module="borrow" function="GetStyleList" plugins="style" var="item" limit="all" }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" align="center"><strong>{$item.name}</strong></td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center">{$item.title}</td>
			<td class="main_td1" align="center">{if $item.status==1}����{else}�ر�{/if}</td>
			<td class="main_td1" align="center">{$item.contents}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=edit&id={$item.id}">�޸�</a></td>
		</tr>
		{ /loop}
	</form>	
</table>
{/if}
