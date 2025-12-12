<?php

include_once("class.ForumViewPager.php");

/**
* WordPager
*
* @package
* @author Administrator
* @copyright Copyright (c) 2006
* @version $Id: class.ItemPager.php,v 1.2 2008/07/25 02:37:25 redlinesoft Exp $
* @access public
**/
class ItemPager extends ForumViewPager {

 public function __construct(&$db, $sql, $id = 'adodb', $showPageLinks = false)
    {
        parent::__construct($db, $sql, $id, $showPageLinks);
        $this->page = _PAGE;
    }

//--------------------------------------------------------
// Simply rendering of grid. You should override this for
// better control over the format of the grid
//
// We use output buffering to keep code clean and readable.
function RenderGrid()
{
	//global $gSQLBlockRows; // used by rs2html to indicate how many rows to display
	//include_once(ADODB_DIR.'/tohtml.inc.php');
	ob_start();
	$gSQLBlockRows = $this->rows;
	//rs2html($this->rs,$this->gridAttributes,$this->gridHeader,$this->htmlSpecialChars);
	$forum=new Forum();
    ?>
    <script language="javascript" type="text/javascript">
      function selectall(obj) {
        var checkBoxes = document.getElementsByTagName('input');
        for (i = 0; i < checkBoxes.length; i++) {
        	if (obj.checked == true) {
        		checkBoxes[i].checked = true; // this checks all the boxes
        	} else {
        		checkBoxes[i].checked = false; // this unchecks all the boxes
        	}
        }
      }
    </script>
    <table cellpadding="3" cellspacing="1" border="0" class="tblForumTable">
    <form name="form" method="get" action="<?=$_SERVER['PHP_SELF']?>">
    <input type="hidden" name="modname" value="forum">
    <input type="hidden" name="mf" value="edit">
    <input type="hidden" name="ac" value="">
    <tr class="tblForumTop">
    <th align="center"><input type="checkbox" value="select_all" onclick="selectall(this);" class="radioButton" /></th>
    <th><?=_FORUM_DESCRIPTION; ?></th>
    </tr>
    <?
        while(!$this->rs->EOF) {
    ?>

    <tr bgcolor="#FFFFFF">
      <td><input type="checkbox" name="mid[]"  value="<?=$this->rs->fields['fitId']; ?>"  class="radioButton" /></td>
      <td><b><?=$this->rs->fields['fitTitle'];?></b><br/><?=substr($forum->cleanHtml($this->rs->fields['fitDescription']),0,200);?>...</td>
    </tr>
 <?
            $this->rs->movenext();
        }

    ?>
    </form>
    </table>
    <?
    $s = ob_get_contents();
	ob_end_clean();
	return $s;
}

}
