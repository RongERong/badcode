<ul class="nav3">
<li><a href="{$_A.query_url}/viewinfo&user_id={$magic.request.user_id}" {if $magic.request.type==""}  style="color:red"{/if}>��������</a></li> 
<li><a href="{$_A.query_url}/viewinfo&type=3&user_id={$magic.request.user_id}" {if $magic.request.type=="3"}  style="color:red"{/if}>��ϵ��ʽ</a></li>
<li><a href="{$_A.query_url}/viewinfo&type=1&user_id={$magic.request.user_id}" {if $magic.request.type=="1"}  style="color:red"{/if}>��λ����</a></li> 
<li><a href="{$_A.query_url}/viewinfo&type=4&user_id={$magic.request.user_id}" {if $magic.request.type=="4"}  style="color:red"{/if}>����״��</a></li>
<li><a href="{$_A.query_url}/viewinfo&type=2&user_id={$magic.request.user_id}" {if $magic.request.type=="2"}  style="color:red"{/if}>�������</a></li> 
</ul> 
<div class="module_add">
{if $magic.request.type==""}
<form action="{$_A.query_url}/viewinfo&edit=basic&user_id={$magic.request.user_id}" method="post"  name="formx" id="formx">
	{articles module="rating" function="GetInfoOne" user_id="$magic.request.user_id" var="var"}
	<div class="module_title"><strong>��������</strong></div>
	<div class="module_border">
		<div class="l">��ʵ������</div>
		<div class="c">
			{$var.realname|default:"-"}
			<input type="hidden" name="realname" id="realname" value="{$var.realname}" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���֤�ţ�</div>
		<div class="c">
			{$var.card_id|default:"-"}
			<input type="hidden" name="card_id" id="card_id" value="{$var.card_id}" />
		</div>
	</div>	
	<div class="module_border">
		<div class="l">�ֻ����룺</div>
		<div class="c">
		<input type="text" name="phone" id="phone" value="{$var.phone}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��</div>
		<div class="c">
		<span> {linkages nid="rating_sex" name="sex" type="text" value="$var.sex"  style="width:120px; border:#BFBFBF solid 1px; height:18px;"}
		</span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������ڣ�</div>
		<div class="c">
		<span> {linkages nid="rating_birthday_year" name="rating_birthday_year" type="value" value="$var.rating_birthday_year" }{linkages nid="rating_birthday_mouth" name="rating_birthday_mouth" type="value" value="$var.rating_birthday_mouth" }{linkages nid="rating_birthday_day" name="rating_birthday_day" type="value" value="$var.rating_birthday_day" } 
		</span>			
		</div>
	</div>
	<div class="module_border">
		<div class="l">����״����</div>
		<div class="c">
			<span>{linkages nid="rating_marry" name="marry" value="$var.marry" type="value"  style="width:120px; border:#BFBFBF solid 1px; "}
			</span>	
		</div>
	</div>	
	<div class="module_border">
		<div class="l">ѧ ����</div>
		<div class="c">
		<span> {linkages nid="rating_education" name="edu" value="$var.edu" type="value" style="width:120px; border:#BFBFBF solid 1px;"}
		</span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����룺</div>
		<div class="c">
		<span> {linkages nid="rating_income" name="income" value="$var.income" type="value" style="width:120px; border:#BFBFBF solid 1px;"}
		</span>
	</div>
	
	
	<div class="module_border">
		<div class="l">����������</div>
		<div class="c">
		<textarea name="remark" cols="50" rows="5"/>{$var.remark}</textarea>
		</div>
	</div>
	{articles module="users" plugins="Friends" function="GetUsersInviteOne" user_id=$magic.request.user_id var="User"} 
	<div class="module_border">
		<div class="l">�Ƽ���:</div>
		<div class="c">
		<input type="text" name="username" id="username" value="{$User.username}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	{/articles}
	<div class="module_border">
		<div class="l">������:</div>
		<div class="c">		
		{ list module="users" plugins="Friends" function="GetUsersInviteList" var="loop" 	user_id=$magic.request.user_id}
		{foreach from=$loop.list item="item"}
		{$item.username}&nbsp;
		{/foreach}
		{/list}
		</div>
	</div>
	<div class="module_submit border_b" >
		<input type="submit" value="�ύ" /><input type="hidden" name="status" value="1" />
	</div>
	{/articles}
	</form>
{elseif $magic.request.type==1}
<form action="{$_A.query_url}/viewinfo&edit=job&user_id={$magic.request.user_id}" method="post"  name="formx" id="formx">
	{articles module="rating" function="GetJobOne" user_id="$magic.request.user_id" var="Jvar"}
	<div class="module_title"><strong>������λ��Ϣ</strong></div>
	<div class="module_border">
		<div class="l">��λ���ƣ�</div>
		<div class="c">
		<input type="text" name="name" id="name" value="{$Jvar.name}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������ޣ�</div>
		<div class="c">
		{linkages nid="rating_workyear" default="��ѡ��" name="work_year" type="value"  value="$Jvar.work_year" style="width:200px; border:#BFBFBF solid 1px;" }	
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ַ��</div>
		<div class="c">
		{areas type="p,c" value="$var.work_city" name="work_" }
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����绰��</div>
		<div class="c">
		<input type="text" name="tel" id="tel" value="{$Jvar.tel}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">֤���ˣ�</div>
		<div class="c">
		<input type="text" name="reterence" id="reterence" value="{$Jvar.reterence}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>	
	<div class="module_border">
		<div class="l">֤���˵绰��</div>
		<div class="c">
		<input type="text" name="reterence_tel" id="reterence_tel" value="{$Jvar.reterence_tel}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_submit border_b" >
		<input type="submit" value="�ύ" /><input type="hidden" name="status" value="1" />
	</div>
	{/articles}
	</form>
{elseif $magic.request.type==2}
<form action="{$_A.query_url}/viewinfo&edit=lianbao&user_id={$magic.request.user_id}" method="post"  name="formx" id="formx">
	{articles module="rating" function="GetLianbaoOne" user_id="$magic.request.user_id" var="var"}
	<div class="module_title"><strong>�������</strong></div>
	<div class="module_border">
		<div class="l">��һ������������</div>
		<div class="c">
		<input type="text" name="name1" id="name1" value="{$var.name1}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ϵ��</div>
		<div class="c">
		<span> 
		 {linkages nid="rating_relation" name="relationship5" type="value" value="$var.relationship5"  style="width:150px; border:#BFBFBF solid 1px; "}
		</span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ֻ���</div>
		<div class="c">
		<input type="text" name="tel1" id="tel1" value="{$var.tel1}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ڶ�������������</div>
		<div class="c">
		<input type="text" name="name2" id="name2" value="{$var.name2}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ϵ��</div>
		<div class="c">
		<span> 
		 {linkages nid="rating_relation" name="relationship6" type="value" value="$var.relationship6"  style="width:150px; border:#BFBFBF solid 1px; "}
		</span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ֻ���</div>
		<div class="c">
		<input type="text" name="tel2" id="tel2" value="{$var.tel2}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>	
	<div class="module_submit border_b" >
		<input type="submit" value="�ύ" /><input type="hidden" name="status" value="1" />
	</div>
	</form>
	{/articles}
{elseif $magic.request.type==3}
<form action="{$_A.query_url}/viewinfo&edit=contact&user_id={$magic.request.user_id}" method="post"  name="formx" id="formx">
	{articles module="rating" function="GetContactOne" user_id="$magic.request.user_id" var="var"}
	<div class="module_title"><strong>��ϵ��ʽ</strong></div>
	<div class="module_border">
		<div class="l">�־�ס��ַ��:</div>
		<div class="c">
		<input type="text" name="live_address" id="live_address" value="{$var.live_address}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ס�ص绰��</div>
		<div class="c">
		<input type="text" name="live_tel" id="live_tel" value="{$var.live_tel}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ϵ��һ������</div>
		<div class="c">
		<input type="text" name="linkman2" id="linkman2" value="{$var.linkman2}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ϵ��</div>
		<div class="c">
		{linkages nid="rating_relation" name="relationship2" type="value" value="$var.relationship2"  style="width:150px; border:#BFBFBF solid 1px; "}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ֻ���</div>
		<div class="c">
		<input type="text" name="phone2" id="phone2" value="{$var.phone2}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ϵ�˶�������</div>
		<div class="c">
		<input type="text" name="linkman3" id="linkman3" value="{$var.linkman3}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ϵ��</div>
		<div class="c">
		{linkages nid="rating_relation" name="relationship3" type="value" value="$var.relationship3"  style="width:150px; border:#BFBFBF solid 1px; "}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ֻ���</div>
		<div class="c">
		<input type="text" name="phone3" id="phone3" value="{$var.phone3}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>	
	<div class="module_submit border_b" >
		<input type="submit" value="�ύ" /><input type="hidden" name="status" value="1" />
	</div>
	{/articles}
	</form>
{elseif $magic.request.type==4}
<form action="{$_A.query_url}/viewinfo&edit=finance&user_id={$magic.request.user_id}" method="post"  name="formx" id="formx">
	<div class="module_title"><strong>����״��</strong></div>	
	{articles module="rating" function="GetFinanceOne" user_id="$magic.request.user_id" var="var"}
	
	<div class="module_border">
		<div class="l">�¾�����:</div>
		<div class="c">
		<input type="text" name="month_income" id="month_income" value="{$var.month_income}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">���빹������:</div>
		<div class="c">
		<input type="text" name="month_income_describe" id="month_income_describe" value="{$var.month_income_describe}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">�¾�֧��:</div>
		<div class="c">
		<input type="text" name="month_pay" id="month_pay" value="{$var.month_pay}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">֧����������:</div>
		<div class="c">
		<input type="text" name="month_pay_describe" id="month_pay_describe" value="{$var.month_pay_describe}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">ס��������</div>
		<div class="c">
		<span>{linkages nid="rating_house" name="house" value="$var.house" type="value"  style="width:150px; border:#BFBFBF solid 1px; height:18px;"}
		</span>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ֵ��</div>
		<div class="c">
		<input type="text" name="house_value" id="house_value" value="{$var.house_value}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>	
	<div class="module_border">
		<div class="l">�Ƿ񹺳���</div>
		<div class="c">
		<span><select name='is_car' id="is_car"  style='width:150px;' >
			<option value='1' {if $var.is_car==1 } selected="selected"{/if} >��</option>
			<option value='2' {if $var.is_car==2 || $var.is_car=='' } selected="selected"{/if}>��</option>
			</select>
		</span>
		</div>
	</div>	
	<div class="module_border">
		<div class="l">������ֵ��</div>
		<div class="c">
		<input type="text" name="car_value" id="car_value" value="{$var.car_value}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ι���ҵ���ƣ�</div>
		<div class="c">
		<input type="text" name="cangu" id="cangu" value="{$var.cangu}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ι���ҵ���ʶ</div>
		<div class="c">
		<input type="text" name="cangu_account" id="cangu_account" value="{$var.cangu_account}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ʲ�������</div>
		<div class="c">
		<input type="text" name="describe" id="describe" value="{$var.describe}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	
	<div class="module_submit border_b" >
		<input type="submit" value="�ύ" /><input type="hidden" name="status" value="1" />
	</div>
	{/articles}
	</form>
{elseif $magic.request.type==5}
<form action="{$_A.query_url}/viewinfo&edit=houses&user_id={$magic.request.user_id}" method="post"  name="formx" id="formx">
<div class="module_title"><strong>������Ϣ</strong></div>	
	{articles module="rating" function="GetHousesOne" user_id="$magic.request.user_id" var="var"}	
	<div class="module_border">
		<div class="l">������ַ��</div>
		<div class="c">
		<input type="text" name="name" id="name" value="{$var.name}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">���������</div>
		<div class="c">
		<input type="text" name="areas" id="areas" value="{$var.areas}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ݣ�</div>
		<div class="c">
		<input type="text" name="in_year" id="in_year" value="{$var.in_year}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">����״����</div>
		<div class="c">
		<input type="text" name="repay" id="repay" value="{$var.repay}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">����Ȩ��1��</div>
		<div class="c">
		<input type="text" name="holder1" id="holder1" value="{$var.holder1}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ȩ�ݶ</div>
		<div class="c">
		<input type="text" name="right1" id="right1" value="{$var.right1}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>	
	<div class="module_border">
		<div class="l">����Ȩ��2��</div>
		<div class="c">
		<input type="text" name="holder2" id="holder2" value="{$var.holder2}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>	
	<div class="module_border">
		<div class="l">��Ȩ�ݶ</div>
		<div class="c">
		<input type="text" name="right2" id="right2" value="{$var.right2}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>	
	<div class="module_border">
		<div class="l">�������ޣ�</div>
		<div class="c">
		<input type="text" name="load_year" id="load_year" value="{$var.load_year}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>
		</div>
	</div>	
	<div class="module_border">
		<div class="l">ÿ�¹��</div>
		<div class="c">
		<input type="text" name="repay_month" id="repay_month" value="{$var.repay_month}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		</div>
	</div>	
	<div class="module_border">
		<div class="l">��Ƿ������</div>
		<div class="c">
		<input type="text" name="balance" id="balance" value="{$var.balance}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
		
		</div>
	</div>	
	<div class="module_border">
		<div class="l">�������У�</div>
		<div class="c">
		<input type="text" name="bank" id="bank" value="{$var.bank}" style="width:150px; border:#BFBFBF solid 1px; height:18px;"/>	
			
		</div>
	</div>
	<div class="module_submit border_b" >
		<input type="submit" value="�ύ" /><input type="hidden" name="status" value="1" />
	</div>
	{/articles}
	</form>
{/if}
</div>