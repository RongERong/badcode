{if $magic.request.p!= "check"}
<ul class="nav3">
  <li><a href="{$_A.query_url_all}" {if $magic.request.p==""}id="c_so"{/if}>����б�</a></li>
  <li><a href="{$_A.query_url_all}&p=apply" {if $magic.request.p=="apply" || $magic.request.p=="view"  }id="c_so"{/if}>����������</a></li>
  <li><a href="{$_A.query_url_all}&p=log" {if $magic.request.p=="log"}id="c_so"{/if}>��ȼ�¼</a></li>
  <li><a href="{$_A.query_url_all}&p=type" {if $magic.request.p=="type"}id="c_so"{/if}>�������</a></li>
  <li><a href="{$_A.query_url_all}&p=new" {if $magic.request.p=="new"}id="c_so"{/if}>�������</a></li>
</ul>
{/if}
{if $magic.request.p == ""}
<div class="module_add">
  <div class="module_title"><strong>�û�����б�</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
 
  <tr >
    <td width="" class="main_td">�û���</td>
    <td class="main_td">���ö��</td>
    <td class="main_td" >����|���� </td>
    <td class="main_td">�������</td>
    <td class="main_td" >����|���� </td>
    <td class="main_td">���Ŷ��</td>
    <td class="main_td" >����|���� </td>
    <td class="main_td">��ת���</td>
    <td class="main_td" >����|���� </td>
    <td class="main_td" title="">����</td>
  </tr>
  {list module="borrow" plugins="Amount" function="GetAmountList" var="loop" username=request user_id=request }
  {foreach from="$loop.list" item="item"}

  <tr {if $key%2==1} class="tr2"{/if}>
    <td class="main_td1" align="center">{$item.username}</td>
    <td class="main_td1" align="center">{$item.credit} </td>
    <td class="main_td1" align="center">{$item.credit_use}|{$item.credit_frost} </td>
    <td class="main_td1" align="center">{$item.vouch}</td>
    <td class="main_td1" align="center">{$item.vouch_use}|{$item.vouch_frost}</td>
    <td class="main_td1" align="center">{$item.pawn}</td>
    <td class="main_td1" align="center">{$item.pawn_use}|{$item.pawn_frost}</td>
    <td class="main_td1" align="center">{$item.vest}</td>
    <td class="main_td1" align="center">{$item.vest_use}|{$item.vest_frost}</td>
    <td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=log&username={$item.username}">��¼</a> | <a href="{$_A.query_url_all}&p=apply&username={$item.username}">����</a> | <a href="{$_A.query_url_all}&p=new&user_id={$item.user_id}">���</a> </td>
  </tr>
  {/foreach}
    <tr>
      <td colspan="27" class="action"><div class="floatl">
          <script>
			  var url = '{$_A.query_url_all}';
				{literal}
				function amount_sou(){
					var username = $("#username").val();
					location.href=url+"&username="+username;
				}
			  </script>
          {/literal} </div>
        <div class="floatr"> �û�����
          <input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" size="7"/>
          <input type="button" value="{$MsgInfo.users_name_sousuo}" / onclick="amount_sou()">
        </div></td>
    </tr>
  <tr align="center">
    <td colspan="27" align="center"><div align="center">{$loop.pages|showpage}</div></td>
  </tr>
  {/list}
</table>

{elseif  $magic.request.p=="view"}	

  <div class="module_add">
    <div class="module_title"><strong>����������</strong></div>
  </div>
{articles module="borrow" plugins="Amount" function="GetAmountApplyOne" id="$magic.request.id"  var="var"}	
			<div class="module_border_ajax" >
				<div class="l"> �����ˣ�</div>
				<div class="c">{$var.username}</div>
			</div>
            	<div class="module_border_ajax" >
				<div class="l"> �������ͣ�</div>
				<div class="c">{$var.type_name}</div>
			</div>
			<div class="module_border_ajax" >
				<div class="l"> ����״̬��</div>
				<div class="c">{if $var.status==0}�����{elseif $var.status==1}���ͨ��{else}��˲�ͨ��{/if}</div>
			</div>
			<div class="module_border_ajax" >
				<div class="l">�����ȣ�</div>
				<div class="c">{$var.amount_account}</div>
			</div>
			<div class="module_border_ajax" >
				<div class="l">ͨ����ȣ�</div>
				<div class="c">{$var.account}</div>
			</div>
			<div class="module_border_ajax" >
				<div class="l">�����;��</div>
				<div class="c">{$var.borrow_use|linkages:"borrow_use"}</div>
			</div>
			<div class="module_border_ajax" >
				<div class="l">��ϸ˵����</div>
				<div class="c">{$var.content}</div>
			</div>	
			<div class="module_border_ajax" >
				<div class="l">����������</div>
				<div class="c">{if $var.otherborrow==1}��{else}��{/if}</div>
			</div>	
			<div class="module_border_ajax" >
				<div class="l">���ʱ�䣺</div>
				<div class="c">{$var.verify_time|date_format}</div>
			</div>	
			<div class="module_border_ajax" >
				<div class="l">��˱�ע��</div>
				<div class="c">{$var.verify_remark}</div>
			</div>	
			<div class="module_border_ajax" >
				<div class="l">����ע��</div>
				<div class="c">{$var.verify_contents}</div>
			</div>		
			
		{/articles}

    
    
    
{elseif $magic.request.p== "apply"}
<div>
  <div class="module_add">
    <div class="module_title"><strong>�û���������б�</strong></div>
  </div>
  <table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
    <tr >
      <td width="" class="main_td">ID</td>
      <td width="" class="main_td">�û�</td>
      <td width="" class="main_td">����</td>
      <td width="" class="main_td">����</td>
      <td class="main_td">����</td>
      <td width="" class="main_td">��������</td>
      <td class="main_td">������</td>
      <td class="main_td">ͨ�����</td>
      <td class="main_td">���뱸ע</td>
      <td class="main_td">״̬</td>
      <td class="main_td">����ʱ��</td>
      <td class="main_td">���ʱ��</td>
      <td class="main_td">����</td>
    </tr>
    { list module="borrow" plugins="Amount" function="GetAmountApplyList" var="loop" user_id=request  username=request order="1" epage=8 status="request" amount_type="request" }
    {foreach from="$loop.list" item="item"}
    <tr {if $key%2==1} class="tr2"{/if}>
      <td class="main_td1" align="center">{$item.id}</td>
      <td class="main_td1" align="center"><a href="{$_A.query_url_all}&username={$item.username}">{$item.username}</a></td>
      <td class="main_td1" align="center">{if $item.amount_type=="credit"}���ý����{elseif $item.amount_type=="vouch"}���������{elseif $item.amount_type=="pawn"}���Ŷ��{elseif $item.amount_type=="vest"}��ת���{/if}</td>
      <td class="main_td1" align="center">{if $item.amount_style=="once"}һ���Զ��{else}���ö��{/if}</td>
      <td class="main_td1" align="center">{if $item.oprate=="add"}����{else}����{/if}</td>
      <td class="main_td1" align="center">{if $item.type=="webapply"}��վ����{else}�û�����{/if}</td>
      <td class="main_td1" align="center">{$item.amount_account}</td>
      <td class="main_td1" align="center">{$item.account}</td>
      <td class="main_td1" align="center">{$item.content}</td>
      <td class="main_td1" align="center">{if $item.status==0}�����{elseif $item.status==1}���ͨ��{else}��˲�ͨ��{/if}</td>
      <td class="main_td1" align="center">{$item.addtime|date_format:"Y/m/d"}</td>
      <td class="main_td1" align="center">{$item.verify_time|date_format:"Y/m/d H:i"|default:"-"}</td>
      <td class="main_td1" align="center">{if $item.status==0}<a href="javascript:void(0)" onclick='tipsWindown("����û�������","url:get?{$_A.query_url_all}&p=check&id={$item.id}",500,330,"true","","false","text");'><font color="red">���</font></a>{else}<a href="{$_A.query_url_all}&p=view&id={$item.id}" />�鿴</a>{/if}<!-- /<a href="javascript:void(0)" onclick='tipsWindown(" ","url:get?{$_A.query_url_all}&amountview={$item.id}",500,500,"true","","false","text");'/>�鿴</a> --></td>
    </tr>
    {/foreach}
    <tr>
      <td colspan="15" class="action"><div class="floatl">
          <script>
			  var url = '{$_A.query_url_all}&p=apply';
				{literal}
				function amount_sou(){
					var username = $("#username").val();
					var status = $("#status").val();
					var amount_type = $("#amount_type").val();
					location.href=url+"&username="+username+"&amount_type="+amount_type+"&status="+status;
				}
			  </script>
          {/literal} </div>
        <div class="floatr"> �û�����
          <input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" size="7"/>
          ״̬��
          <select name="status" id="status">
          <option value="">ȫ��</option>
          <option value="1" {if $magic.request.status=="1"} selected=""{/if}>ͨ��</option>
          <option value="2" {if $magic.request.status=="2"} selected=""{/if}>��ͨ��</option>
          <option value="0"{if $magic.request.status=="0"} selected=""{/if}>�����</option>
          </select>
           ������ͣ�
        <select name="amount_type" id="amount_type">
        <option value="">ȫ��</option>
        {loop module="borrow" function="GetAmountTypeList" limit="all" plugins="amount" var="item"}
        <option value="{$item.nid}" {if $item.nid==$magic.request.amount_type} selected=""{/if}>{$item.name}</option>
        {/loop}
        </select>
          <input type="button" value="{$MsgInfo.users_name_sousuo}"  onclick="amount_sou()">
        </div></td>
    </tr>
    <tr align="center">
      <td colspan="14" align="center"><div align="center">{$loop.pages|showpage}</div></td>
    </tr>
    {/list}
  </table>
  <!--�˵��б� ����-->
</div>
</div>

{elseif $magic.request.p== "log"}
<div class="module_add">
  <div class="module_title"><strong>��ȼ�¼�б�</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
  <tr >
    <td width="">ID</td>
    <td width="">�û�</td>
    <td width="">����</td>
    <td width="">����</td>
    <td>����</td>
    <td>���</td>
    <td>��ע</td>
    <td>ʱ��</td>
  </tr>
  { list module="borrow"  plugins="Amount" function="GetAmountLogList" var="loop" user_id=request  username=request  amount_type=request  epage=8 }
  {foreach from="$loop.list" item="item"}
  <tr {if $key%2==1} class="tr2"{/if}>
    <td class="main_td1" align="center">{ $item.id}</td>
    <td class="main_td1" align="center"><a href="{$_A.query_url_all}&username={$item.username}">{$item.username}</a></td>
    <td class="main_td1" align="center">{if $item.amount_type=="credit"}���ý����{elseif $item.amount_type=="vouch"}���������{elseif $item.amount_type=="pawn"}���Ŷ��{elseif $item.amount_type=="vest"}��ת��ȶ��{/if}</td>
      <td class="main_td1" align="center">{if $item.amount_style=="once"}һ���Զ��{else}���ö��{/if}</td>
    <td class="main_td1" align="center">{if $item.oprate=="add"}����{elseif $item.oprate=="return"}����{elseif $item.oprate=="frost"}����{else}����{/if}</td>
    <td class="main_td1" align="center">{$item.account}</td>
    <td class="main_td1" align="center">{$item.remark}</td>
    <td class="main_td1" align="center">{$item.addtime|date_format}</td>
  </tr>
  {/foreach}
  <tr>
    <td colspan="15" class="action"><div class="floatl"> ���ӵĶ�ȣ�{$loop.oprate_add|default:0} ���ٵĶ�ȣ�{$loop.oprate_reduce|default:0}
        <script>
			  var url = '{$_A.query_url_all}&p=log';
				{literal}
				function amount_sou(){
					var username = $("#username").val();
					var amount_type = $("#amount_type").val();
					location.href=url+"&username="+username+"&amount_type="+amount_type;
				}
			  </script>
        {/literal} </div>
      <div class="floatr"> �û�����
        <input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" size="7"/>
        ������ͣ�
        <select name="amount_type" id="amount_type">
        <option value="">ȫ��</option>
        {loop module="borrow" function="GetAmountTypeList" limit="all" plugins="amount" var="item"}
        <option value="{$item.nid}" {if $item.nid==$magic.request.amount_type} =""{/if}>{$item.name}</option>
        {/loop}
        </select>
        <input type="button" value="{$MsgInfo.users_name_sousuo}"  onclick="amount_sou()">
      </div></td>
  </tr>
  <tr align="center">
    <td colspan="14" align="center"><div align="center">{$loop.pages|showpage}</div></td>
  </tr>
  {/list}
</table>
<!--�˵��б� ����-->
</div>



{elseif $magic.request.p=="new"}
<div class="module_add">
  <div style="margin-top:10px;">
    <div >
      <div style="border:1px solid #CCCCCC; "> {if $magic.request.user_id==""}
        <form action="" method="post">
          <div class="module_title">��Ӷ��:<strong>���Ȳ����û�</strong>(����˳���������)
            <input type="hidden" name="type" value="user_id" />
          </div>
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
          <div class="module_submit">
            <input type="submit" value="ȷ���ύ" class="submit_button" />
          </div>
        </form>
      </div>
      {else}
      <form action="" method="post" enctype="multipart/form-data" onsubmit="return check_new();">
        <div class="module_title"><strong>����û����</strong>
          <input type="hidden" name="user_id" value="{$magic.request.user_id}" />
        </div>
        <div class="module_border">
          <div class="l">�� �� �� ��</div>
          <div class="c"> {$_A.users_result.username} </div>
        </div>
        <div class="module_border">
          <div class="l">������ͣ�</div>
          <div class="c"><select name='amount_type'  id='amount_type'><option value='credit' >���ö��</option><option value='vouch' >���������</option><option value='pawn' >���Ŷ��</option><option value='vest' >��ת���</option></select></div>
        </div>
        
        <div class="module_border">
          <div class="l">������ࣺ</div>
          <div class="c"><select name='amount_style'  id='amount_style'><option value='forever' >���ö��</option>
          </select>
          
          </div>
        </div>
        <div class="module_border">
          <div class="l">������</div>
          <div class="c"> {input type="radio" value="add|����,reduce|����" name="oprate" checked="$_A.amount_apply_result.oprate"} </div>
        </div>
        <div class="module_border">
          <div class="l">����ȣ�</div>
          <div class="c">
            <input type="text" name="amount_account"  id="amount_account" value="{$_A.amount_apply_result.amount_account}" onkeyup="value=value.replace(/[^0-9]/g,'')"/>
          </div>
        </div>
        <div class="module_border">
          <div class="l">�����Ϣ��</div>
          <div class="c">
            <textarea name="content" cols="30" rows="4" id="content" >{$_A.amount_apply_result.content}</textarea>
          </div>
        </div>
        <div class="module_submit">
          <input type="submit" value="ȷ���ύ" class="submit_button" />
        </div>
      </form>
    </div>
    {literal}
    <script>
    function check_new(){
        if ($("#amount_account").val()==""){
            alert("�������Ϊ��");
            return false;
        }
        if ($("#content").val()==""){
            alert("�����Ϣ����Ϊ��");
            return false;
        }
        
        return true;
    }
    </script>
    {/literal}
    {/if} 
{elseif $magic.request.p== "check"}

<div style="overflow:hidden">
  <form name="form1" method="post" action="{$_A.query_url_all}&p=check&id={$magic.request.id}" onsubmit="return check_amount()">    
    <div class="module_border_ajax">
        <div class="l">���:</div>
        <div class="c"><input type="radio" name="status" value="1" />ͨ�� <input type="radio" name="status" value="2" checked="" />��ͨ��</div>
    </div>
    <div class="module_border_ajax">
    <div class="l">����:</div>
    <div class="c">{if $_A.amount_apply_result.amount_style=="once"}һ���Զ��{else}���ö��{/if} {if $_A.amount_apply_result.oprate=="add"}����{else}����{/if} </div>
    </div>
    {if $_A.amount_apply_result.borrow_use>0}
    <div class="module_border_ajax">
    <div class="l">�����;:</div>
    <div class="c"> {$_A.amount_apply_result.borrow_use|linkages:"borrow_use"} </div>
    </div>
    {/if}
    <div class="module_border_ajax">
    <div class="l">��ϸ˵��:</div>
    <div class="c"> {$_A.amount_apply_result.content|html_format}  </div>
    </div>
     {if $_A.amount_apply_result.remark!=""}
    <div class="module_border_ajax">
    <div class="l">�����ط�˵��:</div>
    <div class="c"> {$_A.amount_apply_result.remark|html_format} </div>
    </div>
    {/if}
    <div class="module_border_ajax">
    <div class="l">������:</div>
    <div class="c"> {$_A.amount_apply_result.amount_account} </div>
    </div>
    <div class="module_border_ajax">
    <div class="l">ͨ�����:</div>
    <div class="c">
      <input type="text" value="{$_A.amount_apply_result.amount_account}" name="account"  id="account" />
    </div>
    </div>
  
    <div class="module_border_ajax" >
      <div class="l">��˱�ע:</div>
      <div class="c">
        <textarea name="verify_remark"  id="verify_remark" cols="45" rows="7">{$_A.amount_apply_result.verify_remark}</textarea>
      </div>
    </div>
    
    
    <div class="module_border_ajax" >
      <div class="l">����ע:</div>
      <div class="c">
        <textarea name="verify_contents"  id="verify_contents" cols="45" rows="7">{$_A.amount_apply_result.verify_contents}</textarea>
      </div>
    </div>
     <div class="module_border_ajax" >
      <div class="l"></div>
      <div class="c">
        <input type="hidden" name="user_id" value="{$_A.amount_apply_result.user_id}" />
      <input type="hidden" name="nid" value="{$_A.amount_apply_result.nid}" />
      <input type="hidden" name="id" value="{$magic.request.id}" />
      <input type="submit"  name="reset" class="submit_button" value="ȷ�����" />
      </div>
    </div>
  </form>
</div>
{literal}
    <script>
    function check_amount(){
        if ($("#account").val()==""){
            alert("ͨ������Ϊ��");
            return false;
        }
        if ($("#verify_remark").val()==""){
            alert("��˱�ע����Ϊ��");
            return false;
        }
        
        return true;
    }
    </script>
    {/literal}
{elseif $magic.request.p=="type_edit"}
<div class="module_add">
	<div class="module_title"><strong>�޸Ļ��ʽ</strong></div>
    <form action="{$_A.query_url_all}&p=type_edit" method="post">
    {articles module="borrow" function="GetAmountTypeOne" plugins="Amount" id="$magic.request.id" var="item"}
	
	<div class="module_border">
		<div class="l">������������ƣ�</div>
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
	       	<input type="text" name="description"  class="input_border" value="{$item.description}"   size="20" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬</div>
		<div class="c">
			<input type="radio" name="status"  class="input_border" value="1" {if $item.status==1} checked=""{/if}  />���� 
			<input type="radio" name="status"  class="input_border"  value="0" {if $item.status==0} checked=""{/if} />�ر�
		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">�Ƿ�����һ���Զ�ȣ�</div>
		<div class="c">
			<input type="radio" name="once_status"  class="input_border" value="1" {if $item.once_status==1} checked=""{/if}  />���� 
			<input type="radio" name="once_status"  class="input_border"  value="0" {if $item.once_status==0} checked=""{/if} />�ر�
	       (Ӧ�����û�����)
    	</div>
	</div>
    
    -->
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
        <textarea name="remark" cols="50" rows="5">{$item.remark}</textarea>
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
{elseif $magic.request.p=="type"}
<div class="module_add">
	<div class="module_title"><strong>���������</strong></div>
</div>
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">���ʽ</td>
			<td width="*" class="main_td">��ʶ��</td>
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td" title="һ���رգ�������վ��������">״̬</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">����</td>
		</tr>
		{loop module="borrow" function="GetAmountTypeList" plugins="Amount" var="item" limit="all" }
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" align="center"><strong>{$item.name}</strong></td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center">{$item.title}</td>
			<td class="main_td1" align="center">{if $item.status==1}����{else}�ر�{/if}</td>
			<td class="main_td1" align="center">{$item.description}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url_all}&p=type_edit&id={$item.id}">�޸�</a></td>
		</tr>
		{ /loop}
	</form>	
</table>

{/if}