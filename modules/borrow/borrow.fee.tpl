<ul class="nav3"> 
<li><a href="{$_A.query_url_all}" {if  $magic.request.p=="new" || $magic.request.p==""} id="c_so"{/if}>������</a></li> 
<li><a href="{$_A.query_url_all}&p=type" title="���ڽ�δ���ı�"  {if $magic.request.p=="type"} id="c_so"{/if}>��������</a></li> 
</ul>

{if $magic.request.p=="type_edit" || $magic.request.p=="type_new" ||  $magic.request.p=="type"}

{include file="borrow.fee_type.tpl" template_dir="modules/borrow"}


{elseif $magic.request.p=="edit" || $magic.request.p=="new"}

{literal}
<script>
function fee_types(s){
   if (s==0){
        $("#fee_borrow").hide();
   }else{
     $("#fee_borrow").show();
   }
    
    if (s==1){
        $("#fee_borrow_1").show();
        $("#fee_borrow_2").hide();
    }else{
        $("#fee_borrow_2").show();
        $("#fee_borrow_1").hide();
    }
    
  
}
</script>
{/literal}
<div class="module_add">
	<div class="module_title"><strong>{if $magic.request.p=="edit"}�޸�{else}���{/if}������</strong></div>
<form action="{$_A.query_url_all}&p={$magic.request.p}" method="post">

	<div class="module_border">
		<div class="l">�������ƣ�</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{$_A.borrow_fee_result.name}"   size="20" />
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">��ʶ����</div>
		<div class="c">
			<input type="text" name="nid"  class="input_border" value="{$_A.borrow_fee_result.nid}"   size="20" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			<input type="radio" name="status"  class="input_border" value="1" {if $_A.borrow_fee_result.status==1} checked=""{/if}  />���� 
			<input type="radio" name="status"  class="input_border"  value="0" {if $_A.borrow_fee_result.status==0} checked=""{/if} />�ر�
		</div>
	</div>
	
    
	<div class="module_border">
		<div class="l">����</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{$_A.borrow_fee_result.order}"   size="10" />(�շѷ�ʽ���ᰴ�մ˴�С��������۳�)
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
        <select name="type">
        {loop module="borrow" function="GetFeeTypeList" plugins="fee" limit="all" }
        <option value="{$var.nid}" {if $var.nid==$_A.borrow_fee_result.type} selected=""{/if}>{$var.name}</option>
        {/loop}
        </select>
		��ָ�÷��õĿۿ�ʱ��㣬�硰���ɹ������÷����ڽ��ɹ�ʱ�۳���    
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">�۳�����</div>
		<div class="c">
			<input type="radio" name="user_type"  class="input_border" value="borrow" {if $_A.borrow_fee_result.user_type=='borrow' || $_A.borrow_fee_result.user_type==""} checked=""{/if}  />����� 
			<input type="radio" name="user_type"  class="input_border"  value="tender" {if $_A.borrow_fee_result.user_type=='tender'} checked=""{/if} />Ͷ����
		��ָ�۳����õ��û���</div>
	</div>
    
	<div class="module_border">
		<div class="l">�Ƿ�渶��Ͷ���ˣ�</div>
		<div class="c">
			<input type="radio" name="pay_tender"  class="input_border" value="0" {if $_A.borrow_fee_result.pay_tender=='0' || $_A.borrow_fee_result.pay_tender==""} checked=""{/if}  />�� 
			<input type="radio" name="pay_tender"  class="input_border"  value="1" {if $_A.borrow_fee_result.pay_tender=='1'} checked=""{/if} />��
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�������ͣ�</div>
		<div class="c">
			<input type="radio" name="fee_type"  class="input_border" value="0" {if $_A.borrow_fee_result.fee_type==0 || $_A.borrow_fee_result.fee_type==""} checked=""{/if} onclick="fee_types(0)"  /><span title="����ȡ�κη���">���</span>
            <input type="radio" name="fee_type"  class="input_border" value="1"  {if $_A.borrow_fee_result.fee_type==1} checked=""{/if}   onclick="fee_types(1)"/><span title="���̶�����������ȡ���������� 5%">������</span>
           
            <input type="radio" name="fee_type"  class="input_border" value="2" {if $_A.borrow_fee_result.fee_type==2 } checked=""{/if}   onclick="fee_types(2)" /><span title="��������ʽ���������� *% + �����������-*�£��� *%">��������ʽ</span>
		</div>
	</div>
    
    <div id="fee_borrow" >
    	<div class="module_border" id="fee_borrow_1" {if $_A.borrow_fee_result.fee_type!="1" }style="display:none"{/if}>
    		<div class="l">��������</div>
    		<div class="c">
    		      VIP ��<select name="account_scale_vip">{foreach from=$_A.account_type item='item'}<option value="{$key}" {if $_A.borrow_fee_result.account_scale_vip==$key} selected=""{/if}>{$item}</option>{/foreach}</select>*<input value="{$_A.borrow_fee_result.vip_borrow_scale}" name="vip_borrow_scale" size="3" />%��*<input type="checkbox" name="vip_rank" value="1" title="���ѡ�У������ȼ��ķ��ý��йҹ�" {if $_A.borrow_fee_result.vip_rank==1} checked=""{/if} />���ֵȼ����� *<input type="checkbox" name="vip_period" value="1" size="3" {if $_A.borrow_fee_result.vip_period==1} checked=""{/if}/>����<br />
                  ��Ա��<select name="account_scale_all">{foreach from=$_A.account_type item='item'}<option value="{$key}" {if $_A.borrow_fee_result.account_scale_all==$key} selected=""{/if}>{$item}</option>{/foreach}</select>*<input value="{$_A.borrow_fee_result.all_borrow_scale}" name="all_borrow_scale" size="3" />%�� *<input type="checkbox" name="all_rank" value="1" title="���ѡ�У������ȼ��ķ��ý��йҹ�" {if $_A.borrow_fee_result.all_rank==1} checked=""{/if} />���ֵȼ�����*<input type="checkbox" name="all_period" value="1" size="3"  {if $_A.borrow_fee_result.all_period==1} checked=""{/if}/>����
    		</div>
		�������ֵȼ�������ָ�����õȼ��������۷ѣ����������ǰ�����������۷ѣ�
    	</div>
        
        
    	<div class="module_border"  id="fee_borrow_2" {if $_A.borrow_fee_result.fee_type!="2" }style="display:none"{/if}>
    		<div class="l">��������ʽ��</div>
    		<div class="c">
    		      VIP ��<select name="account_scales_vip">{foreach from=$_A.account_type item='item'}<option value="{$key}" {if $_A.borrow_fee_result.account_scales_vip==$key} selected=""{/if}>{$item}</option>{/foreach}</select>*��<input value="{$_A.borrow_fee_result.vip_borrow_scales}" name="vip_borrow_scales" size="1" />%+������-<input value="{$_A.borrow_fee_result.vip_borrow_scales_month}" name="vip_borrow_scales_month" size="1" />����)*<input value="{$_A.borrow_fee_result.vip_borrow_scales_scale}" name="vip_borrow_scales_scale" size="1" />%��<a title="���������������ڴ�����">����</a>��<input value="{$_A.borrow_fee_result.vip_borrow_scales_max}" name="vip_borrow_scales_max" size="1" />%��<br />
                  ��Ա��<select name="account_scales_all">{foreach from=$_A.account_type item='item'}<option value="{$key}" {if $_A.borrow_fee_result.account_scales_all==$key} selected=""{/if}>{$item}</option>{/foreach}</select>*��<input value="{$_A.borrow_fee_result.all_borrow_scales}" name="all_borrow_scales" size="1" />%+������-<input value="{$_A.borrow_fee_result.all_borrow_scales_month}" name="all_borrow_scales_month" size="1" />����)*<input value="{$_A.borrow_fee_result.all_borrow_scales_scale}" name="all_borrow_scales_scale" size="1" />%��<a title="���������������ڴ�����">����</a>��<input value="{$_A.borrow_fee_result.all_borrow_scales_max}" name="all_borrow_scales_max" size="1" />%��
    		</div>
		��������������������շѣ���ֻ�������ʼ�İٷֱȼ��ɣ�
    	</div>
    </div>
     
    
    
    
	<div class="module_border">
		<div class="l">���ý�����ͣ�</div>
		<div class="c">
            {loop module="borrow" function="GetTypeList" limit="all" plugins="type" var="type_var"}
           
			<input type="checkbox" name="borrow_types[]"  class="input_border" value="{$type_var.nid}" {$type_var.nid|checked:"$_A.borrow_fee_result.borrow_types"} /><a title="{$type_var.title}"> {$type_var.name}</a>
           
            {/loop}
		</div>
		��ָ�÷�������Ե���Щ���ֽ����շѣ�
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
	<div class="module_title"><strong>���н�����</strong> <a href="{$_A.query_url_all}&p=new">����ӽ����á�</a></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td">��ʶ��</td>
			<td width="*" class="main_td" >����</td>
			<td width="*" class="main_td" >״̬</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">����</td>
		</tr>
		{loop module="borrow" function="GetFeeList" plugins="fee" var="item" limit="all" }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" align="center"><strong>{$item.name}</strong></td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center">{if $item.user_type=="borrow"}�����{else}Ͷ����{/if}</td>
			<td class="main_td1" align="center">{if $item.status==1}<font color='green'>����</font>{else}�ر�{/if}</td>
			<td class="main_td1" align="center">{$item.type_name}</td>
			<td class="main_td1" align="center">{if $item.fee_type==0}���{elseif $item.fee_type==1}������{else}��������ʽ{/if}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=edit&id={$item.id}">�޸�</a> | <a href="#" onClick="javascript:if(confirm('ȷ��Ҫɾ����?ɾ���󽫲��ɻָ�')) location.href='{$_A.query_url_all}&p=del&id={$item.id}'">ɾ��</a></td>
		</tr>
		{ /loop}
	</form>	
</table>
{/if}