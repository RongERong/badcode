<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if  $magic.request.p=="new" || $magic.request.p=="edit" || $magic.request.p==""} id="c_so"{/if}>����б�</a></li> 
<li><a href="{$_A.query_url_all}&p=type" title="�к��������"  {if $magic.request.p=="type" || $magic.request.p=="type_new" || $magic.request.p=="type_edit"} id="c_so"{/if}>�������</a></li> 
<li><a href="{$_A.query_url_all}&p=mingxi"  {if $magic.request.p=="mingxi"} id="c_so"{/if}>�����ϸ</a></li> 
<li><a href="{$_A.query_url_all}&p=count"  {if $magic.request.p=="count"} id="c_so"{/if}>���ͳ��</a></li> 
</ul>

{if $magic.request.p=="type_edit" || $magic.request.p=="type_new" ||  $magic.request.p=="type"}

{include file="hongbao.type.tpl" template_dir="modules/hongbao"}

{elseif $magic.request.p=="edit" || $magic.request.p=="new"}

<div class="module_add">
	<div class="module_title"><strong>{if $magic.request.p=="edit"}�޸�{else}���{/if}�������</strong></div>
<form action="{$_A.query_url_all}&p={$magic.request.p}" method="post">

	<div class="module_border">
		<div class="l">������ƣ�</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{$_A.hongbao_result.name}"   size="20" />
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">��ʶ����</div>
		<div class="c">
			<input type="text" name="nid"  class="input_border" value="{$_A.hongbao_result.nid}"   size="20" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			<input type="radio" name="status"  class="input_border" value="1" {if $_A.hongbao_result.status==1} checked=""{/if}  />���� 
			<input type="radio" name="status"  class="input_border"  value="0" {if $_A.hongbao_result.status==0} checked=""{/if} />�ر�
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">����</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{$_A.hongbao_result.order}"   size="10" />(������ᰴ�մ˴�С��������۳�)
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
        <select name="type_id">
        {loop module="hongbao" function="GetTypeList" limit="all" }
        <option value="{$var.id}" {if $var.id==$_A.hongbao_result.type_id} selected=""{/if}>{$var.name}</option>
        {/loop}
        </select>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�н��������</div>
		<div class="c">
			<input type="text" name="money"  class="input_border" value="{$_A.hongbao_result.money}"   size="10" />(1Ԫ-1000Ԫ��������)
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�к������</div>
		<div class="c">
			<input type="text" name="percent"  class="input_border" value="{$_A.hongbao_result.percent}"   size="10" />% (1-100����������)
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��Чʱ��</div>
		<div class="c">
			<input type="text" name="available_time"  class="input_border" value="{$_A.hongbao_result.available_time}"   size="10" /> Сʱ
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">ʱ����</div>
		<div class="c">
			<input type="text" name="explode_time"  class="input_border" value="{$_A.hongbao_result.explode_time}"   size="10" /> ����
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">����ģʽ��</div>
		<div class="c">
			<input type="radio" name="mode"  class="input_border" value="0" {if $_A.hongbao_result.mode==0} checked=""{/if}  />�ֶ�ģʽ
			<input type="radio" name="mode"  class="input_border"  value="1" {if $_A.hongbao_result.mode==1} checked=""{/if} />�Զ�ģʽ
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
	<div class="module_title"><strong>���к��</strong> <a href="{$_A.query_url_all}&p=new">����Ӻ����</a></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td">��ʶ��</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td">״̬</td>
			<td width="" class="main_td">�н����</td>
			<td width="" class="main_td">�н�����</td>
			<td width="" class="main_td">��Чʱ��</td>
			<td width="" class="main_td">ʱ����</td>
			<td width="" class="main_td">����ģʽ</td>
			<td width="" class="main_td">����</td>
		</tr>
		{loop module="hongbao" function="GetHongbaoList" var="item" limit="all" }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" align="center"><strong>{$item.name}</strong></td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center">{$item.type_name}</td>
			<td class="main_td1" align="center">{if $item.status==1}<font color='green'>����</font>{else}�ر�{/if}</td>
			<td class="main_td1" align="center">{$item.money}</td>
			<td class="main_td1" align="center">{$item.percent}%</td>
			<td class="main_td1" align="center">{$item.available_time}Сʱ</td>
			<td class="main_td1" align="center">{$item.explode_time}����</td>
			<td class="main_td1" align="center">{if $item.status==1}�Զ�ģʽ{else}�ֶ�ģʽ{/if}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=edit&id={$item.id}">�޸�</a> | <a href="#" onClick="javascript:if(confirm('ȷ��Ҫɾ����?ɾ���󽫲��ɻָ�')) location.href='{$_A.query_url_all}&p=del&id={$item.id}'">ɾ��</a></td>
		</tr>
		{ /loop}
	</form>	
</table>
{/if}