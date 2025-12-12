<?

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$forum=new Forum();

?>
<table width="80%">
  <tr>
    <td align="center">
    <img src="modules/forum/images/category.gif" /><br/>
    <a href="<?=$_SERVER['PHP_SELF']; ?>?modname=forum&mf=category"><?=_FORUM_CATEGORY; ?></a>
    </td>
    <td align="center">
    <img src="modules/forum/images/rude.gif" /><br/>
    <a href="<?=$_SERVER['PHP_SELF']; ?>?modname=forum&mf=rudeword"><?=_FORUM_RUDE_FILTER; ?></a>
    </td>
    <td align="center">
    <img src="modules/forum/images/chatitem.gif" /><br/>
    <a href="<?=$_SERVER['PHP_SELF']; ?>?modname=forum&mf=item"><?=_FORUM_ITEMS_FILTER; ?></a>
    </td>
    <td align="center">
    <img src="modules/forum/images/back.gif" /><br/>
    <a href="module.php?modname=setting"><?=_FORUM_BACK; ?></a>
    </td>
  </tr>
</table>
