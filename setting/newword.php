<?

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$forum=new Forum();

?>
<span class="txtContentTitle"><?=_FORUM_NEW_RUDE_WORDS_SETTING; ?></span><br/><br/>
<?=_FORUM_NEW_RUDE_WORDS_SETTING_INSTRUCTION; ?><br/><br/>
<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
<a href="javascript:document.post.submit();" >
<?=_SAVE; ?></a>&nbsp;&nbsp;
<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="setting.php?modname=forum&mf=rudeword" >
<?=_CANCEL; ?></a>&nbsp;&nbsp;<br/><br/>

<table>
<FORM NAME="post" METHOD="POST" ACTION="<?=$_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="modname" value="<?=$module_name; ?>" />
<input type="hidden" name="mf" value="edit" />
<input type="hidden" name="ac" value="newword" />
<?
    for ($i=0;$i<10;$i++) {
?>
<tr>
    <td><?=_FORUM_RUDE_WORD; ?> <?=($i+1); ?></td>
    <td><input name="keyWord[]" type="text" /></td>
</tr>
<?
    }
?>
</FORM>
</table>
