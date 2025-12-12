<?

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$forum=new Forum();

    $rs=$forum->getSubGroupById($_REQUEST['fig']);
?>
<span class="txtContentTitle"><?=_FORUM_NEW_CATEGORY_SETTING; ?></span><br/><br/>
<?=_FORUM_NEW_CATEGORY_INSTRUCTION; ?><br/><br/>
<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
<a href="javascript:document.post.submit();" >
<?=_SAVE; ?></a>&nbsp;&nbsp;
<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="setting.php?modname=forum&mf=category" >
<?=_CANCEL; ?></a>&nbsp;&nbsp;<br/><br/>
<table>
<FORM NAME="post" METHOD="POST" ACTION="<?=$_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="modname" value="<?=$module_name; ?>" />
<input type="hidden" name="mf" value="edit" />
<input type="hidden" name="fctId" value="<?=$_REQUEST['fig']; ?>" />
<input type="hidden" name="ac" value="edit" />
<tr>
    <td><?=_FORUM_TITLE; ?></td>
    <td><input type="text" name="fctTitle" size="40" value="<?=$rs->fields['fctTitle']; ?>"/>*</td>
</tr>
<tr>
    <td><?=_FORUM_PARENT; ?></td>
    <td>
    <select name="fctParentId" size="1">
    <option value="0" ><?=_FORUM_NONE; ?></option>
    <?
        $rsg=$forum->getActiveGroupForum();
        while (!$rsg->EOF) {
            if ($rsg->fields['fctParentId']==$_REQUEST['fig']) {
                $sel="selected";
            } else {
                $sel="";
            }
            ?><option value="<?=$rsg->fields['fctId']; ?>" <?=$sel; ?> ><?=$rsg->fields['fctTitle']; ?></option><?
            $rsg->movenext();
        }
    ?>
    </select>
    </td>
</tr>
<tr>
    <td valign="top"><?=_FORUM_DESCRIPTION; ?></td>
    <td><textarea name="fctDescription" rows="5" cols="40"><?=$rs->fields['fctDescription']; ?></textarea></td>
</tr>
</FORM>
</table>
