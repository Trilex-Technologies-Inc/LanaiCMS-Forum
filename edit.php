<?
	if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
			die ("You can't access this file directly...");
	}

	$module_name = basename(dirname(__FILE__));
	$modfunction="modules/$module_name/module.php";
	include_once($modfunction);

	$forum = new Forum();

    if ($forum->uid > 0) {
        $userid=$forum->uid;
    } else {
        $userid=0;
    }
    if ($_REQUEST['captext']==$_SESSION['captcha']) {
      switch ($_REQUEST['ac']) {
          case "reply" :
                  $forum->setPostForum($_REQUEST['fid'],$_REQUEST['fgid'],$userid,$_REQUEST['fitName'],$_REQUEST['fitTitle'],$_REQUEST['fitDescription']);
                  $sys_lanai->go2Page("module.php?modname=forum&mf=viewitem&fid=".$_REQUEST['fid']."&fgid=".$_REQUEST['fgid']);
          break;
          case "post" :
				if (!empty($_REQUEST['fitTitle'])) {
					$forum->setPostForum(0,$_REQUEST['fgid'],$userid,$_REQUEST['fitName'],$_REQUEST['fitTitle'],$_REQUEST['fitDescription']);
					$sys_lanai->go2Page("module.php?modname=forum&mf=viewforum&fgid=".$_REQUEST['fgid']);
				} else {
					$sys_lanai->getErrorBox(_REQUIRE_FIELDS." '"._FORUM_SUBJECT."' <a href='javascript:history.back()'>".strtolower(_BACK)."</a>");
				}

          break;
      }
    } else {
        $sys_lanai->getErrorBox(_FORUM_CAPTCHA_TEXT_FAIL);
    }

?>
