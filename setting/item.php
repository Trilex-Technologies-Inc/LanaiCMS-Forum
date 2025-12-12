<?

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );
    include_once("modules/$module_name/class.ItemPager.php");
	$forum=new Forum();

?>
<span class="txtContentTitle"><?=_FORUM_ITEM_DELETE_SETTING; ?></span><br/><br/>
<?=_FORUM_ITEM_DELETE_SETTING_INSTRUCTION; ?><br/><br/>
<img src="theme/<?=$cfg['theme']; ?>/images/delete.gif" border="0" align="absmiddle"/>
<a href="javascript:chk_mdelete();" >
<?=_DELETE; ?></a>&nbsp;&nbsp;
<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="setting.php?modname=forum" >
<?=_BACK; ?></a>
<br><br>
<script language="javascript">
<!--
function chk_mdelete() {
	if (confirm("<?=_DELETE_QUESTION; ?>")){
		document.form.ac.value="mitemdelete";
		document.form.submit();
	}
}
//-->
</script>
<?
    $fitem=$forum->getForumItems();
  	$pager=new ItemPager($forum->db,$forum->_sql,true);
	$pager->Render(30);
?>
