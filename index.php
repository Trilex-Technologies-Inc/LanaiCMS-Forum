<?
	if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
			die ("You can't access this file directly...");
	}

	$module_name = basename(dirname(__FILE__));
	$modfunction="modules/$module_name/module.php";
	include_once($modfunction);
	
	$forum = new Forum();

    $rsgroup=$forum->getActiveGroupForum();

?>
<span class="txtContentTitle"><?=_FORUM_HOME; ?></span><br/><br/>
<?=_FORUM_HOME_INSTRUCTION; ?><br/><br/>
<?
    while (!$rsgroup->EOF) {
?>
<span class="txtContentTitle"><?=$rsgroup->fields['fctTitle']; ?></span><br/><br/>
<?
    $rssubgroup=$forum->getActiveSubGroupForum($rsgroup->fields['fctId']);
?>
<table cellpadding="3" cellspacing="1" border="0" width="100%" class="tblForumTable">
  <tr  class="tblForumTop">
    <th width="50%" height="40" class="tblForumTop"><?=_FORUM_FORUM; ?></th>
    <th ><?=_FORUM_TOPIC; ?></th>
    <th><?=_FORUM_REPLIES; ?></th>
    <th colspan="2" ><?=_FORUM_LASTPOST; ?></th>
  </tr>

<?
    while (!$rssubgroup->EOF) {
?>
<tr>
    <td bgcolor="#FFFFFF" height="40">
    <a href="module.php?modname=<?=$module_name; ?>&mf=viewforum&fgid=<?=$rssubgroup->fields['fctId']; ?>">
    <?=$rssubgroup->fields['fctTitle']; ?>
    </a>
    <br/><?=$rssubgroup->fields['fctDescription']; ?>
    </td>
    <td align="center" bgcolor="#FFFFFF" ><?=$forum->getSubGroupTopicNumber($rssubgroup->fields['fctId']); ?></td>
    <td align="center" bgcolor="#FFFFFF" ><?=$forum->getSubGroupReplyNumber($rssubgroup->fields['fctId']); ?></td>
    <td bgcolor="#FFFFFF" >
        <?
            $lastpost=$forum->getForumItemBySubGroup($rssubgroup->fields['fctId']);
            if (($lastpost->recordcount())>0) {
              echo adodb_date2("d M Y ",$lastpost->fields['fitCreate'])." "._FORUM_BY." ";
              if ($lastpost->fields['userId']==0) {
                  echo $lastpost->fields['fitName'];
              } else {
                  echo $forum->getMemberNameById($lastpost->fields['userId']);
              }
            }
        ?>
    </td>
    <td bgcolor="#FFFFFF" >
    <?
        if (($lastpost->recordcount())>0) {
    ?>
    <a href="module.php?modname=<?=$module_name; ?>&mf=viewitem&fid=<?=$lastpost->fields['fitId']; ?>&fgid=<?=$lastpost->fields['fctId']; ?>">
    <img src="modules/<?=$module_name; ?>/images/newreply.gif" alt="<?=_FORUM_GOTO_LASTPOST; ?>" border="0"/></a>
    <?
        }
    ?>
    </td>
  </tr>
<?
        $rssubgroup->movenext();
    }

?>
</table><br/>
<?
        $rsgroup->movenext();
    }

?>
