{if $magic.request.p=="edit"}
<div class="module_add">
	<div class="module_title"><strong>�޸ı�������</strong></div>
<form action="{$_A.query_url_all}&p=edit" method="post">
    {articles module="borrow" function="GetTypeOne" plugins="type" id="$magic.request.id" var="item"}

	
	<div class="module_border">
		<div class="l">�������ͣ�</div>
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
		<div class="l">������</div>
		<div class="c">
            <textarea name="description" rows="4" cols="40">{$item.description}</textarea>
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			<input type="radio" name="status"  class="input_border" value="1" {if $item.status==1} checked=""{/if}  />���� 
			<input type="radio" name="status"  class="input_border"  value="0" {if $item.status==0} checked=""{/if} />�ر�
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">������ͣ�</div>
		<div class="c">
        {if $item.nid=="worth"}
        ��ֵ���(��ʽ=���˻����ý��+Ͷ�ʶ�����+���ձ���*0.8 -�����ܶ�)<input type="hidden" name="amount_type" value="worth" />
        {elseif $item.nid=="second"}
        ��겻��Ҫ���<input type="hidden" name="amount_type" value="second" />
        {else}
        {loop module="borrow" plugins="amount" status=1 function="GetAmountTypeList" limit="all"}
			<input type="radio" name="amount_type"  class="input_border" value="{$var.nid}" {if $var.nid==$item.amount_type} checked=""{/if}  />{$var.name}
           {/loop}
           {/if}
		</div>
	</div>
   
	<div class="module_border">
		<div class="l">����ȣ�</div>
		<div class="c">
			<input type="text" name="amount_first"  class="input_border" value="{$item.amount_first}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" />Ԫ ~  <input type="text" name="amount_end"  class="input_border" value="{$item.amount_end}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" />Ԫ
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">��������</div>
		<div class="c">
			<input type="text" name="account_multiple"  class="input_border" value="{$item.account_multiple}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" />Ԫ (0��ʾ����)
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">�����ʣ�</div>
		<div class="c">
			<input type="text" name="apr_first"  class="input_border" value="{$item.apr_first}"   size="4" />~  <input type="text" name="apr_end"  class="input_border" value="{$item.apr_end}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" /> %
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">������ޣ�</div>
		<div class="c">
			<input type="text" name="period_first"  class="input_border" value="{$item.period_first}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" />~  <input type="text" name="period_end"  class="input_border" value="{$item.period_end}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" /> {if $item.nid=="day"}��{else}��{/if}
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">��Ч�ڣ�</div>
		<div class="c">
			<input type="text" name="validate_first"  class="input_border" value="{$item.validate_first}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" />~  <input type="text" name="validate_end"  class="input_border" value="{$item.validate_end}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" /> ��
		</div>
	</div>
    
	<div class="module_border">
		<div class="l">���ʱ�䣺</div>
		<div class="c">
			<input type="text" name="check_first"  class="input_border" value="{$item.check_first}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" />~  <input type="text" name="check_end"  class="input_border" value="{$item.check_end}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" /> ��
		</div>
	</div>
    
	<div class="module_border">
		<div class="l" title="�������,����">���Ͷ���</div>
		<div class="c">
			<input type="text" name="tender_account_min"  class="input_border" value="{$item.tender_account_min}"   size="25" />Ԫ(�������,�Ÿ���)
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l" title="�������,����">���Ͷ���</div>
		<div class="c">
			<input type="text" name="tender_account_max"  class="input_border" value="{$item.tender_account_max}"   size="25" />Ԫ(�������,�Ÿ�����0��ʾ����)
		</div>
	</div>
    
   	<div class="module_border">
		<div class="l">�Ƿ����ý�����</div>
		<div class="c">
			<input type="radio" name="award_status"  class="input_border" value="1" {if $item.award_status==1 || $item.award_status=="" } checked=""{/if}  />���� 
			<input type="radio" name="award_status"  class="input_border"  value="0" {if $item.award_status==0} checked=""{/if} />�ر�
		</div>
	</div>
    
    
    
   	<div class="module_border">
		<div class="l">�Ƿ����ò��ֽ�</div>
		<div class="c">
			<input type="radio" name="part_status"  class="input_border" value="1" {if $item.part_status==1 || $item.part_status=="" } checked=""{/if}  />���� 
			<input type="radio" name="part_status"  class="input_border"  value="0" {if $item.part_status==0} checked=""{/if} />�ر�
		</div>
	</div>
    
   	<div class="module_border">
		<div class="l">�Ƿ����ý�����룺</div>
		<div class="c">
			<input type="radio" name="password_status"  class="input_border" value="1" {if $item.password_status==1 || $item.award_status=="" } checked=""{/if}  />���� 
			<input type="radio" name="password_status"  class="input_border"  value="0" {if $item.password_status==0} checked=""{/if} />�ر�
		</div>
	</div>
    
    
   	<div class="module_border">
		<div class="l">�Ƿ����ý��ʧ��Ҳ����</div>
		<div class="c">
			<input type="radio" name="award_false_status"  class="input_border" value="1" {if $item.award_false_status==1 || $item.award_false_status=="" } checked=""{/if}  />���� 
			<input type="radio" name="award_false_status"  class="input_border"  value="0" {if $item.award_false_status==0} checked=""{/if} />�ر�
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">����������</div>
		<div class="c">
			<input type="text" name="award_scale_first"  class="input_border" value="{$item.award_scale_first}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" />~<input type="text" name="award_scale_end"  class="input_border" value="{$item.award_scale_end}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" /> %������������ı������н�����
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l">������</div>
		<div class="c">
			<input type="text" name="award_account_first"  class="input_border" value="{$item.award_account_first}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" />~  <input type="text" name="award_account_end"  class="input_border" value="{$item.award_account_end}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" />Ԫ�����ѡ����Ļ����򰴴˽����Ľ�Χ��
		</div>
	</div>
    
    
    
   	<div class="module_border">
		<div class="l">�����Զ�ͨ����</div>
		<div class="c">
			<input type="radio" name="verify_auto_status"  class="input_border" value="1" {if $item.verify_auto_status==1 || $item.verify_auto_status=="" } checked=""{/if}  />���� 
			<input type="radio" name="verify_auto_status"  class="input_border"  value="0" {if $item.verify_auto_status==0} checked=""{/if} />�ر�
		</div>
	</div>
    
    
   	<div class="module_border">
		<div class="l">�����Զ�ͨ������˱�ע��</div>
		<div class="c">
    		<input type="text" name="verify_auto_remark"  value="{$item.verify_auto_remark}" />
		</div>
	</div>
    
	<div class="module_border">
		<div class="l" title="�����������Ϊ�գ�������д0.00����">vip���ᱣ֤��</div>
		<div class="c">
			<input type="text" name="frost_scale_vip"  class="input_border" value="{$item.frost_scale_vip}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" />%
		</div>
	</div>


	<div class="module_border">
		<div class="l" title="�����������Ϊ�գ�������д0.00����">��ͨ��Ա���ᱣ֤��</div>
		<div class="c">
			<input type="text" name="frost_scale"  class="input_border" value="{$item.frost_scale}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" />%
		</div>
	</div>
    
    
	<div class="module_border">
		<div class="l" title="���ڶ������վ���е渶">�渶����������</div>
		<div class="c">
			<input type="text" name="late_days"  class="input_border" value="{$item.late_days}"   size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" />��
		</div>
	</div>
    
	<div class="module_border">
		<div class="l" title="vip��Ա�渶��Ϣ�ı���">vip�渶��Ϣ������</div>
		<div class="c">
			<input type="text" name="vip_late_scale"  class="input_border" value="{$item.vip_late_scale}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" />%
		</div>
	</div>
    
	<div class="module_border">
		<div class="l" title="��ͨ��Ա�渶����ı���">��ͨ��Ա�渶���������</div>
		<div class="c">
			<input type="text" name="all_late_scale"  class="input_border" value="{$item.all_late_scale}"   size="4" onkeyup="value=value.replace(/[^0-9.]/g,'')" />%
		</div>
	</div>

	<div class="module_border">
		<div class="l">���ʽ��</div>
		<div class="c">
            {loop module="borrow" function="GetStyleList" status=1 limit="all" plugins="style" var="style_var"}
            {if $item.id==3}
            {if $style_var.nid=="endday"}
            <input type="checkbox" name="styles[]"  class="input_border" value="{$style_var.nid}" checked="" readonly="" /> {$style_var.title}
            {/if}
            {else}
            {if $style_var.nid!="endday"}
			<input type="checkbox" name="styles[]"  class="input_border" value="{$style_var.nid}" {$style_var.nid|checked:"$item.styles"} /> {$style_var.title}
            {/if}
            {/if}
            {/loop}
		</div>
	</div>
    <div class="module_title"><strong>���¹�����Ҫ�����������н��</strong></div>
   	<div class="module_border">
		<div class="l">ϵͳ�������</div>
		<div class="c">
			<input type="radio" name="system_borrow_full_status"  class="input_border" value="1" {if $item.system_borrow_full_status==1} checked=""{/if}  />�� 
			<input type="radio" name="system_borrow_full_status"  class="input_border"  value="0" {if $item.system_borrow_full_status==0 || $item.system_borrow_full_status==""} checked=""{/if} />��
           
		</div>
	</div>
	<div class="module_border">
		<div class="l">ϵͳ�û�����</div>
		<div class="c">
			<input type="radio" name="system_borrow_repay_status"  class="input_border" value="1" {if $item.system_borrow_repay_status==1} checked=""{/if}  />�� 
			<input type="radio" name="system_borrow_repay_status"  class="input_border"  value="0" {if $item.system_borrow_repay_status==0 || $item.system_borrow_repay_status==""} checked=""{/if} />��
           
		</div>
	</div>
	<div class="module_border">
		<div class="l">ϵͳ�����Զ��渶</div>
		<div class="c">
			<input type="radio" name="system_web_repay_status"  class="input_border" value="1" {if $item.system_web_repay_status==1} checked=""{/if}  />�� 
			<input type="radio" name="system_web_repay_status"  class="input_border"  value="0" {if $item.system_web_repay_status==0 || $item.system_web_repay_status==""} checked=""{/if} />��
           
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
	<div class="module_title"><strong>���б�������</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">�������</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td">��ʶ��</td>
			<td width="*" class="main_td" title="һ���رգ�ǰ̨�����ܽ�˱�Ŀ�">״̬</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">��Ч��</td>
			<td width="" class="main_td">���ᱣ֤��</td>
			<td width="" class="main_td">���ʽ</td>
			<td width="" class="main_td">����</td>
		</tr>
		{loop module="borrow" function="GetTypeList" plugins="type" var="item" limit="all" }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" align="center"><strong>{$item.name}</strong></td>
			<td class="main_td1" align="center">{$item.title}</td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center">{if $item.status==1}����{else}�ر�{/if}</td>
			<td class="main_td1" align="center">{$item.apr_first}~{$item.apr_end}%</td>
			<td class="main_td1" align="center">{$item.period_first}~{$item.period_end}{if $item.id==3}��{else}��{/if}</td>
			<td class="main_td1" align="center">{$item.validate_first}~{$item.validate_end}��</td>
			<td class="main_td1" align="center">{$item.frost_scale}%</td>
			<td class="main_td1" align="center">{$item.styles_name}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=edit&id={$item.id}">�޸�</a></td>
		</tr>
		{ /loop}
	</form>	
</table>
{/if}
