<?php

include_once("class.ForumViewPager.php");

/**
* ForumViewItemPager
*
* @package
* @author Administrator
* @copyright Copyright (c) 2006
* @version $Id: class.ForumViewItemPager.php,v 1.2 2008/07/25 02:37:25 redlinesoft Exp $
* @access public
**/
class ForumViewItemPager extends ForumViewPager {

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

    <?
        while(!$this->rs->EOF) {
    ?>
     <table cellpadding="3" cellspacing="1" border="0" width="100%" class="tblForumTable">
    <tr>
      <td class="tblForumTop2" width="120"><?=_FORUM_TITLE; ?></td>
      <td bgcolor="#FFFFFF" ><?=$this->rs->fields['fitTitle'];?></td>
    </tr>
    <tr>
      <td class="tblForumTop2"><?=_FORUM_DATE; ?></td>
      <td bgcolor="#FFFFFF" ><?=adodb_date2("r",$this->rs->fields['fitCreate']);?></td>
    </tr>
    <tr>
      <td class="tblForumTop2"><?=_FORUM_AUTHOR; ?></td>
      <td bgcolor="#FFFFFF" >
    <?
        if ($this->rs->fields['userId']==0) {
            ?><?=$this->rs->fields['fitName']; ?><?
        } else {
            ?><?=$forum->getMemberNameById($this->rs->fields['userId']); ?><?
        }
    ?>
      </td>
    </tr>
     <tr>
    <td class="tblForumTop2">&nbsp;</td>
    <td bgcolor="#FFFFFF" >
    <?
         $badword=$forum->getWordArray();
         $repstr="<img src=\"modules/forum/images/badword.gif\" border=\"0\" align=\"absmiddle\"/>";
    ?>
    <?=$forum->getFilterString($this->rs->fields['fitDescription'],$badword,$repstr); ?>
    </td>
  </tr>
  <tr></tr>
    </table><br />
    <?
            $this->rs->movenext();
        }

    ?>

    <?
    $s = ob_get_contents();
	ob_end_clean();
	return $s;
}

}
