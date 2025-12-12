<?
	if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
			die ("You can't access this file directly...");
	}

	$module_name = basename(dirname(__FILE__));
	$modfunction="modules/$module_name/module.php";
	include_once($modfunction);
	/// settype
	settype($_REQUEST['fgid'],"integer");
	settype($_REQUEST['fid'],"integer");
    include_once("modules/".$module_name."/class.ForumViewItemPager.php");

	$forum = new Forum();
    $rssubgroup=$forum->getSubGroupById($_REQUEST['fgid']);

    $rs=$forum->getForumItem($_REQUEST['fid']);



?>
<span class="txtContentTitle"><?=_FORUM_NAME; ?> :
<?=$rssubgroup->fields['fctTitle']; ?>&nbsp;&gt;&nbsp;<?=$rs->fields['fitTitle'];?>
</span><br/><br/>

 <?php if ((!empty($_SESSION['uid'])) && ($_SESSION['uid'] > 0)): ?>
<?=_FORUM_ITEM_INSTRUCTION; ?><br/><br/>
<img src="theme/<?=$cfg['theme']; ?>/images/new.gif" border="0" align="absmiddle"/>
<a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name?>&mf=post&fgid=<?=$_REQUEST['fgid']; ?>" >
<?=_POST; ?></a>&nbsp;&nbsp;
<img src="modules/forum/images/reply.gif" border="0" align="absmiddle"/>
<a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name?>&mf=reply&fid=<?=$_REQUEST['fid']; ?>&fgid=<?=$_REQUEST['fgid']; ?>" >
<?=_REPLY; ?></a>&nbsp;&nbsp;
<?php endif ?>
<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name?>&mf=viewforum&fgid=<?=$_REQUEST['fgid']; ?>" >
<?=_BACK; ?></a>&nbsp;&nbsp;<br/><br/>

<table cellpadding="3" cellspacing="1" border="0" width="100%" class="tblForumTable">
  <tr class="tblForumTop">
    <td  width="120"><?=_FORUM_TITLE; ?></td>
    <td  class="tblForumBody"><?=$rs->fields['fitTitle'];?></td>
  </tr>
   <tr class="tblForumTop">
      <td width="120"><?=_FORUM_DATE; ?></td>
      <td  class="tblForumBody"><?=adodb_date2("r",$rs->fields['fitCreate']);?></td>
    </tr>
  <tr class="tblForumTop">
    <td width="120"><?=_FORUM_AUTHOR; ?></td>
    <td  class="tblForumBody">
    <?
        if ($rs->fields['userId']==0) {
            ?><?=$rs->fields['fitName']; ?><?
        } else {
            ?><?=$forum->getMemberNameById($rs->fields['userId']); ?><?
        }
    ?>
    </td>
  </tr>
  <tr class="tblForumTop">
    <td>&nbsp;</td>
    <td class="tblForumBody">
    <?
         $badword=$forum->getWordArray();
         $repstr="<img src=\"modules/forum/images/badword.gif\" border=\"0\" align=\"absmiddle\"/>";
    ?>
    <?=$forum->getFilterString($rs->fields['fitDescription'],$badword,$repstr); ?>
    </td>
  </tr>
  <tr></tr>
</table><br/>
<?
    $fitem=$forum->getForumItemReply($_REQUEST['fid']);
  	$pager=new ForumViewItemPager($forum->db,$forum->_sql,true);
	$pager->Render(30);

?>


