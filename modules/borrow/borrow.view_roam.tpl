<div class="module_add" >
<form method="post" action="">
	{articles module="borrow" plugins="roam" function="GetRoamOne" borrow_nid='$_A.borrow_result.borrow_nid' var="roam_var"}
	<div class="module_title"><strong>���������Ϣ</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="r">
		<a href="{$_A.admin_url}&q=code/users/info_view&user_id={$_A.borrow_result.user_id}">	{$_A.borrow_result.username}</a>
		</div>
		<div class="s">�ع����ޣ�</div>
		<div class="c" style="width:220px">
		{if $magic.request.first_edit!=""}<select name="borrow_period">
	{foreach from=$_A.borrow_type_result.period_result key=key item=item}<option value='{$item.value}' {if $item.value==$_A.borrow_result.borrow_period} selected=""{/if}>{$item.name}</option>{/foreach}
	</select> {else}	{$_A.borrow_result.borrow_period}����{/if}
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">״̬��
		{if $magic.request.first_edit!=""}
        <input type="submit" value="ȷ���޸�"  class="submit_button"/> 
        {else}
        	{if $_A.borrow_result.borrow_status_nid=="full"}�������<input type="button"  src="{$_A.tpldir}/images/button.gif" align="absmiddle" value="�������" class="submit_button" onclick='location.href="{$_A.query_url}/full&p=verify&borrow_nid={$_A.borrow_result.borrow_nid}"'/>
            {elseif $_A.borrow_result.borrow_status_nid=="first" }
             <input type="button"  src="{$_A.tpldir}/images/button.gif" align="absmiddle" value="������" class="submit_button" onclick='tipsWindown("������","url:get?{$_A.query_url}/first&check={$_A.borrow_result.borrow_nid}",500,230,"true","","false","text");'/>
             {else}
             {$_A.borrow_result.borrow_status_nid|linkages:"borrow_status"}
             {/if}
        {/if}     
             </div>
	</div>	
	
	
	<div class="module_border">
		<div class="l">���⣺</div>
		<div class="r">
		{if $magic.request.first_edit!=""}<input type="text" name="name" value="{$_A.borrow_result.name}" /> {else}	{$_A.borrow_result.name}{/if}
		</div>
		<div class="s">����ţ���</div>
		<div class="c" style="width:220px">
		{$_A.borrow_result.borrow_nid}
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">������ͣ�{$_A.borrow_result.type_title} </div>
	</div>
	
	<div class="module_border">
		<div class="l">����ܽ�</div>
		<div class="r">
		{$_A.borrow_result.account}
		</div>
		<div class="s">�����ʣ�</div>
		<div class="c" style="width:220px">
        	{if $magic.request.first_edit!=""}<input type="text" name="borrow_apr" size="5" value="{$_A.borrow_result.borrow_apr}" /> {else}	{$_A.borrow_result.borrow_apr} {/if}%
			</div>
		<div style="float:left;padding:4px 5px 0 0px;">���ʽ��{$_A.borrow_result.style_title}<input type="hidden" value="end" name="borrow_style" /></div>
	</div>
	
	<div class="module_border">
		<div class="l">��С��ת��λ��</div>
		<div class="r">
        	{$roam_var.account_min}   
		</div>
		<div class="s">����ת������</div>
		<div class="c" style="width:220px">
			{$roam_var.portion_total}��
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">���ʱ�䣺
			{$_A.borrow_result.addtime|date_format}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">����������</div>
		<div class="r">
		{if $magic.request.first_edit!=""}<input size="20" type="text" name="voucher" value="{$roam_var.voucher}" /> {else}{$roam_var.voucher}     {/if}
		</div>
		<div class="s">��������ʽ��</div>
		<div class="c" style="width:220px">
		{if $magic.request.first_edit!=""}<input size="20" type="text" name="vouch_style" value="{$roam_var.vouch_style}" /> {else}{$roam_var.vouch_style}     {/if}
		</div>
	
	</div>
	
	<div class="module_border">
		<div class="l">���������</div>
		<div class="r">
				{$_A.borrow_result.hits|default:0}��
		</div>
		<div class="s">���۴�����</div>
		<div class="c" style="width:220px">
			{$_A.borrow_result.comment_count}��
		</div>
		<div style="float:left;padding:4px 5px 0 0px;">���IP��{$_A.borrow_result.addip}</div>
        	
	</div>


	{if $_A.borrow_result.status>=1}
	<div class="module_title"><strong>��������</strong></div>
	<table width="100%" >
  <tr >
    <td width="" class="main_td">������� </td>
    <td width="" class="main_td">��˽�� </td>
    <td width="" class="main_td">���ʱ�� </td>
    <td width="" class="main_td">�����Ա </td>
    <td width="" class="main_td">��˱�ע </td>
    <td width="" class="main_td">����ע </td>
  </tr>
  {loop module="borrow" plugins="loan" function="GetVerifyList" limit="all" borrow_nid='$_A.borrow_result.borrow_nid'}
  <tr >
    <td>{$var.type_name} </td>
    <td>{$var.status_name} </td>
    <td>{$var.addtime|date_format} </td>
    <td>{$var.username|default:ϵͳ} </td>
    <td>{$var.remark} </td>
    <td>{$var.contents} </td>
  </tr>
  {/loop}
  </table>
	
	
	
	<div class="module_title"><strong>Ͷ�����飺</strong></div>
  <table width="100%">
  <tr >
    <td>����ת������{$roam_var.portion_total}</td>
    <td >����ת������{$roam_var.portion_yes}</td>
    <td >����ת������{$roam_var.portion_wait}</td>
  </tr>
  <tr >
    <td >��Ͷ���{$_A.borrow_result.borrow_account_yes}</td>
    <td colspan="2" >��Ͷ��Ľ�{$_A.borrow_result.borrow_account_wait}</td>
  </tr>
  </table>
   <table width="100%">
  <tr >
    <td width="" class="main_td" >ID </td>
    <td width="" class="main_td" >Ͷ���� </td>
    <td width="" class="main_td" >�Ϲ����� </td>
    <td width="" class="main_td" >Ͷ�ʽ�� </td>
    <td width="" class="main_td" >Ͷ��ʱ�� </td>
    <td width="" class="main_td" >Ͷ������ </td>
  </tr>
	{ loop module="borrow" function="GetTenderList" plugins="Tender" limit="all" borrow_nid='$_A.borrow_result.borrow_nid' var="item"}
	<tr  {if $key%2==1} class="tr2"{/if}>
		<td>{ $item.id}<input type="hidden" name="id[]" value="{ $item.id}" /></td>
		<td class="main_td1" align="center"><a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/users/view&user_id={$item.user_id}",500,230,"true","","true","text");'>	{$item.username}</a></td>
		<td>{$item.account/$roam_var.account_min}��</td>
		<td>{$item.account}Ԫ</td>
		<td>{$item.addtime|date_format}</td>
		<td>{$item.contents}</td>
	</tr>
	{ /loop}
  </table>
	
	{/if}
	
		{if $_A.borrow_result.status>1}
  <div class="module_title"><strong>�������飺</strong></div>
  <table width="100%">
  <tr >
    <td colspan="2">����ܶ��{$_A.borrow_result.account}</td>
    <td colspan="2">Ӧ���ܶ��{$_A.borrow_result.repay_account_all}</td>
    <td colspan="2">Ӧ����Ϣ����{$_A.borrow_result.repay_account_interest}</td>
   </tr>
  <tr >
    <td colspan="2">�ѻ��ܶ<font style="color:#009900">��{$_A.borrow_result.repay_account_yes}</font></td>
    <td colspan="2">�ѻ����𣺣�{$_A.borrow_result.repay_account_capital_yes}</td>
    <td colspan="2">�ѻ���Ϣ����{$_A.borrow_result.repay_account_interest_yes}</td>
  </tr>
 
  <tr >
    <td colspan="2">δ���ܶ<font style="color:#FF0000">��{$_A.borrow_result.repay_account_wait}</font></td>
    <td colspan="2">δ�����𣺣�{$_A.borrow_result.repay_account_capital_wait}</td>
    <td colspan="2">δ����Ϣ����{$_A.borrow_result.repay_account_interest_wait}</td>
  </tr>
 
  <tr >
    <td valign="center" class="main_td">���� </td>
    <td valign="center" class="main_td">Ӧ��������+��Ϣ�� </td>
    <td valign="center" class="main_td">Ӧ��ʱ�� </td>
    <td valign="center" class="main_td">ʵ��ʱ�� </td>
    <td valign="center" class="main_td">ʵ������ </td>
    <td valign="center" class="main_td">ʵ����Ϣ </td>
    <td valign="center" class="main_td">�������� </td>
    <td valign="center" class="main_td">״̬ </td>
    <td valign="center" class="main_td">��վ�Ƿ�渶 </td>
    <td valign="center" class="main_td">�渶ʱ�� </td>
    <td valign="center" class="main_td">�渶��� </td>
  </tr>
  {loop module="borrow" plugins="loan" function="GetRepayList" limit="all" borrow_nid='$_A.borrow_result.borrow_nid' var="item"}
  <tr >
    <td>{$item.repay_period}</td>
    <td>{$item.repay_account}</td>
    <td>{$item.repay_time|date_format:"Y-m-d"}</td>
    <td>{$item.repay_yestime|date_format:"Y-m-d"|default:'-'}</td>
    <td>{$item.repay_capital_yes}</td>
    <td>{$item.repay_interest_yes}</td>
    <td>{$item.late_days}</td>
    <td>{$item.repay_type_name}</td>
    <td>{if $item.repay_web==1}��{else}��{/if}</td>
	<td >{$item.repay_web_time|date_format:"Y-m-d"}</td>
	<td >��{if $item.repay_web==1}{$item.repay_account}{else}0.00{/if}</td>

  </tr>
  {/loop}
  </table>
  
  	{/if}
  

	
	<div class="module_title"><strong>���ϣ�</strong></div>
	<div class="module_border" >
    {if $magic.request.first_edit!=""}
	<span id="share_upload">�ϴ�ͼƬ</span>
    {/if}
<script type="text/javascript" src="/plugins/dyswfupload/swfupload.js"></script>
<script type="text/javascript" src="/plugins/dyswfupload/dyswfupload.js"></script>
<script type="text/javascript" src="{$tempdir}/js/jquery.dragsort-0.5.1.min.js"></script>
   <ul class="upload_pic" id="photo_items">
   
   {foreach from=$roam_var.upfiles_pic item="_item"}
	 <li  class="fin">
     <div class="pic"> <img class="img" src="{$_item.fileurl|litpic:100,100}"></div><div class="box"> {if $magic.request.first_edit==""}{$_item.contents}{else}<input type="hidden" name="upfiles_id[]" value="{$_item.id}"><span class="move ico-move"></span><input type="text" name="upfiles_content[]" value="{$_item.contents}" size="5" />  <span class="close ico-close-btn" >ɾ��</span>{/if}</div></li>
	 {/foreach}</ul>
     
			 {literal}
             <script>
             $(".ico-close-btn").click(function(){
                $(this).parent().remove();
             })
var swfu;
SWFUpload.onload = function () {
	var settings = {
		flash_url : "/plugins/dyswfupload/swfupload_fp9.swf",
		flash9_url : "/plugins/dyswfupload/swfupload_fp9.swf",
		upload_url: "/?user&q=plugins&ac=dyswfupload",
		file_size_limit : "5 MB",
		file_types : "*.jpg;*.gif",
		file_types_description : "All Files",
		file_upload_limit : 30,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "photo_items"
		},
		debug: false,
		button_image_url : "",
		button_placeholder_id : "share_upload",
		button_text: '<span class="button" >+�ϴ�ͼƬ</div>',
		//button_text_style: ".aa{ font-size:22px; border-radius: 3px; }",
	//	button_image_url : "/plugins/dyswfupload/upload.gif",
		button_width: 155,
		button_height: 47,
	// 	button_text : '<span class="button"><img src=""></span>',
		button_text_style : '.button {   font-size: 22px;font-family: Microsoft YaHei,SimSun; }',
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess
		
	};

	swfu = new SWFUpload(settings);
	
}
             </script>
             {/literal}
	</div>	
	
	<div class="module_title"><strong>��������</strong></div>
	<div class="module_border" >
	
   {if $magic.request.first_edit!=""} <textarea id="borrow_contents" name="borrow_contents"  style="width:530px;height:200px;">{$_A.borrow_result.borrow_contents}</textarea>{else} {$_A.borrow_result.borrow_contents}{/if}

	</div>
		<div class="module_title"><strong>���ʲ������</strong></div>
	<div class="module_border" >
   {if $magic.request.first_edit!=""} <textarea id="borrow_account" name="borrow_account"  style="width:530px;height:200px;">{$roam_var.borrow_account}</textarea>{else}{$roam_var.borrow_account}{/if}
	</div>
		<div class="module_title"><strong>���ʽ���;��</strong></div>
	<div class="module_border" >
{if $magic.request.first_edit!=""} <textarea id="borrow_account_use" name="borrow_account_use"  style="width:530px;height:200px;">{$roam_var.borrow_account_use}</textarea>{else}{$roam_var.borrow_account_use}{/if}
	</div>
		<div class="module_title"><strong>���տ��ƴ�ʩ��</strong></div>
	<div class="module_border" >
{if $magic.request.first_edit!=""} <textarea id="risk" name="risk"  style="width:530px;height:200px;">{$roam_var.risk}</textarea>{else}{$roam_var.risk}{/if}
	</div>
</div>
<input type="hidden" name="borrow_nid" value="{$magic.request.first_edit}" />
{/articles}

</form>