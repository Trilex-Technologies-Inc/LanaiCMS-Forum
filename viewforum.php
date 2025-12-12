<?
	if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
			die ("You can't access this file directly...");
	}

	$module_name = basename(dirname(__FILE__));
	$modfunction="modules/$module_name/module.php";
	include_once($modfunction);

    include_once("modules/".$module_name."/class.ForumViewPager.php");

	$forum = new Forum();
	/// settype
	settype($_REQUEST['fgid'],"integer");
    	$rssubgroup=$forum->getSubGroupById($_REQUEST['fgid']);

?>
<span class="txtContentTitle"><?=_FORUM_NAME; ?> :
<?=$rssubgroup->fields['fctTitle']; ?>
</span><br/><br/>

 <?php if ((!empty($_SESSION['uid'])) && ($_SESSION['uid'] > 0)): ?>
 <?=_FORUM_GROUP_INSTRUCTION; ?><br/><br/>
<img src="theme/<?=$cfg['theme']; ?>/images/new.gif" border="0" align="absmiddle"/>
<a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name?>&mf=post&fgid=<?=$_REQUEST['fgid']; ?>" >

<?=_POST; ?></a>&nbsp;&nbsp;
 <?php endif ?>
<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name?>" >
<?=_BACK; ?></a>&nbsp;&nbsp;<br/><br/>
<?
    $fitem=$forum->getForumItemBySubGroup($_REQUEST['fgid']);
  	$pager=new ForumViewPager($forum->db,$forum->_sql,true);
	$pager->Render(30);

?>

