{if $magic.request.p=="edit" || $magic.request.p=="new"}
{literal}
<script>
function onflag(val){
    if(val==1){
        $("#sytle_1").show()
        $("#sytle_2").hide()
    }else if(val==2){
        $("#sytle_1").hide()
        $("#sytle_2").show()
    }
}</script>
{/literal}
<div class="module_add">
	<div class="module_title"><strong>{if $magic.request.p=="edit"}�޸�{else}���{/if}������</strong></div>
<form action="{$_A.query_url_all}&p={$magic.request.p}" method="post" enctype="multipart/form-data">

	<div class="module_border">
		<div class="l">���ƣ�</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{$_A.borrow_flag_result.name}"   size="20" />
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">������</div>
		<div class="c">
			<input type="text" name="title"  class="input_border" value="{$_A.borrow_flag_result.title}"   size="40" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ʶ����</div>
		<div class="c">
			<input type="text" name="nid"  class="input_border" value="{$_A.borrow_flag_result.nid}"   size="10" />
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">����</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{$_A.borrow_flag_result.order|default:10}"   size="10" />
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			<input type="radio" name="status"  class="input_border" value="1" {if $_A.borrow_flag_result.status==1} checked=""{/if}  />���� 
			<input type="radio" name="status"  class="input_border"  value="0" {if $_A.borrow_flag_result.status==0} checked=""{/if} />�ر�
		</div>
	</div>
	
    
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
		      <textarea name="remark" rows="7" cols="40">{$_A.borrow_flag_result.remark}</textarea>
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">ͼƬ���ͣ�</div>
		<div class="c">	
            <input type="radio" name="style"  class="input_border"  value="1" {if $_A.borrow_flag_result.style==1} checked=""{/if} onclick="onflag(1)"/>����
			<input type="radio" name="style"  class="input_border" value="2" {if $_A.borrow_flag_result.style==2} checked=""{/if}  onclick="onflag(2)"/>�ϴ� 
		
		</div>
	</div>
    
	<div class="module_border" {if $_A.borrow_flag_result.style!=1} style="display:none"{/if} id="sytle_1">
		<div class="l">����ͼƬ��ַ��</div>
		<div class="c">
			<input type="text" name="fileurl"  class="input_border" value="{$_A.borrow_flag_result.fileurl}"   size="20" />����д������ͼƬ���ƣ�����ͼƬ�ĺ�׺����ȷ���ļ���data/images/borrow�д��ļ�
		</div>
	</div>
    
    
	<div class="module_border" {if $_A.borrow_flag_result.style!=2 || $_A.borrow_flag_result.style==""} style="display:none"{/if} id="sytle_2">
		<div class="l">�����ϴ�ͼƬ��</div>
		<div class="c">
			<input type="file" name="pic"  class="input_border"    />
            <img src="{$_A.borrow_flag_result.upfile_url}" height="20" >
            <input name="upfiles_id" value="{$_A.borrow_flag_result.upfiles_id}" type="hidden" >
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
	<div class="module_title"><strong>���ͼ���б�</strong> <a href="{$_A.query_url_all}&p=new">�����ͼ�꡿</a></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td">��ʶ��</td>
			<td width="*" class="main_td" >״̬</td>
			<td width="" class="main_td">ͼƬ</td>
			<td width="" class="main_td">����</td>
		</tr>
		{loop module="borrow" function="GetFlagList" plugins="Flag" var="item" limit="all" }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" align="center"><strong>{$item.name}</strong></td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center">{if $item.status==1}<font color='green'>����</font>{else}�ر�{/if}</td>
			<td class="main_td1" align="center">{if $item.style==1}<img src="/data/images/borrow/{$item.fileurl}" />{else}<img src="{$item.upfile_url}" height="20" />{/if}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=edit&id={$item.id}">�޸�</a> | <a href="#" onClick="javascript:if(confirm('ȷ��Ҫɾ����?ɾ���󽫲��ɻָ�')) location.href='{$_A.query_url_all}&p=del&id={$item.id}'">ɾ��</a></td>
		</tr>
		{ /loop}
	</form>	
</table>
{/if}