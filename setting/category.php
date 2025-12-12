<?

if (!eregi("setting.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))) );
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$forum = new Forum();

?>
<span class="txtContentTitle"><?=_FORUM_CATEGORY_SETTING;?></span><br/><br/>
<?=_FORUM_CATEGORY_SETTING_INSTRUCTION;?><br/><br/>

<img src="theme/<?=$cfg['theme'];?>/images/new.gif" border="0" align="absmiddle"/>
<a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name?>&mf=newcategory"><?=_NEW;?></a>&nbsp;&nbsp;
<img src="theme/<?=$cfg['theme'];?>/images/ok.gif" border="0" align="absmiddle"/>
<a href="javascript:chk_mactive();"><?=_ACTIVE;?></a>&nbsp;&nbsp;
<img src="theme/<?=$cfg['theme'];?>/images/save.gif" border="0" align="absmiddle"/>
<a href="javascript:chk_morder();"><?=_SAVE_ORDER;?></a>&nbsp;&nbsp;
<img src="theme/<?=$cfg['theme'];?>/images/delete.gif" border="0" align="absmiddle"/>
<a href="javascript:chk_mdelete();"><?=_DELETE;?></a>&nbsp;&nbsp;
<img src="theme/<?=$cfg['theme'];?>/images/back.gif" border="0" align="absmiddle"/>
<a href="setting.php?modname=forum"><?=_BACK;?></a>

<br><br>

<script>
function selectall(obj) {
    var checkBoxes = document.getElementsByTagName('input');
    for (i = 0; i < checkBoxes.length; i++) {
        checkBoxes[i].checked = obj.checked;
    }
}
function chk_mdelete() {
    if (confirm("<?=_DELETE_QUESTION;?>")){
        document.form.ac.value="mdelete";
        document.form.submit();
    }
}
function chk_mactive() {
    document.form.ac.value="mactive";
    document.form.submit();
}
function chk_morder() {
    document.form.ac.value="morder";
    document.form.submit();
}
</script>

<table border="0" width="100%" cellpadding="3" cellspacing="1" class="tblForumTable">
<form name="form" method="get" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="modname" value="forum">
<input type="hidden" name="mf" value="edit">
<input type="hidden" name="ac" value="">

<tr class="tblForumTop">
    <th align="center"><input type="checkbox" onclick="selectall(this);" class="radioButton"/></th>
    <th><?=_FORUM_FORUM;?></th>
    <th><?=_ORDER;?></th>
    <th><?=_ACTIVE;?></th>
    <th><?=_EDIT;?></th>
</tr>

<?
$rscat = $forum->getForumGroup();

if ($rscat && is_object($rscat)) {
    while (!$rscat->EOF) {
?>

<tr bgcolor="#FFFFFF">
    <td align="center"><input type="checkbox" name="mid[]" value="<?=$rscat->fields['fctId'];?>" class="radioButton"/></td>
    <td><strong><?=$rscat->fields['fctTitle'];?></strong></td>
    <td align="center">
        <input type="text" size="3" name="fctOrder[]" value="<?=$rscat->fields['fctOrder'];?>"/>
        <input type="hidden" name="fctOrderId[]" value="<?=$rscat->fields['fctId'];?>"/>
    </td>
    <td align="center">
        <? if ($rscat->fields['fctActive']=="y") { ?>
            <a href="<?=$_SERVER['PHP_SELF'];?>?modname=forum&mf=edit&ac=active&vl=n&fig=<?=$rscat->fields['fctId'];?>">
                <img src="theme/<?=$forum->cfg['theme'];?>/images/ok.gif" border="0"/>
            </a>
        <? } else { ?>
            <a href="<?=$_SERVER['PHP_SELF'];?>?modname=forum&mf=edit&ac=active&vl=y&fig=<?=$rscat->fields['fctId'];?>">
                <img src="theme/<?=$forum->cfg['theme'];?>/images/cancel.gif" border="0"/>
            </a>
        <? } ?>
    </td>
    <td align="center">
        <a href="<?=$_SERVER['PHP_SELF'];?>?modname=forum&mf=categoryform&fig=<?=$rscat->fields['fctId'];?>">
            <img src="theme/<?=$forum->cfg['theme'];?>/images/edit.gif" border="0"/>
        </a>
    </td>
</tr>

<?
$rsforum = $forum->getForumSubGroup($rscat->fields['fctId']);

if ($rsforum && is_object($rsforum)) {
    while (!$rsforum->EOF) {
?>

<tr bgcolor="#FFFFFF">
    <td align="center"><input type="checkbox" name="mid[]" value="<?=$rsforum->fields['fctId'];?>" class="radioButton"/></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?=$rsforum->fields['fctTitle'];?></strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;<?=$rsforum->fields['fctDescription'];?></td>
    <td align="center">
        <input type="text" size="3" name="fctOrder[]" value="<?=$rsforum->fields['fctOrder'];?>"/>
        <input type="hidden" name="fctOrderId[]" value="<?=$rsforum->fields['fctId'];?>"/>
    </td>
    <td align="center">
        <? if ($rsforum->fields['fctActive']=="y") { ?>
            <a href="<?=$_SERVER['PHP_SELF'];?>?modname=forum&mf=edit&ac=active&vl=n&fig=<?=$rsforum->fields['fctId'];?>">
                <img src="theme/<?=$forum->cfg['theme'];?>/images/ok.gif" border="0"/>
            </a>
        <? } else { ?>
            <a href="<?=$_SERVER['PHP_SELF'];?>?modname=forum&mf=edit&ac=active&vl=y&fig=<?=$rsforum->fields['fctId'];?>">
                <img src="theme/<?=$forum->cfg['theme'];?>/images/cancel.gif" border="0"/>
            </a>
        <? } ?>
    </td>
    <td align="center">
        <a href="<?=$_SERVER['PHP_SELF'];?>?modname=forum&mf=categoryform&fig=<?=$rsforum->fields['fctId'];?>">
            <img src="theme/<?=$forum->cfg['theme'];?>/images/edit.gif" border="0"/>
        </a>
    </td>
</tr>

<?
        $rsforum->MoveNext();
    }
}

$rscat->MoveNext();

    }
}

?>

</form>
</table>
