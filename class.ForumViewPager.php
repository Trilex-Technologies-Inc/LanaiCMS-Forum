<?php

/**
* ForumViewPager
*
* @package
* @author Administrator
* @copyright Copyright (c) 2006
* @version $Id: class.ForumViewPager.php,v 1.2 2008/07/25 02:37:25 redlinesoft Exp $
* @access public
**/
class ForumViewPager extends ADODB_Pager {

 function __construct($db, $sql, $id = 'adodb', $showPageLinks = false) {
        parent::__construct($db, $sql, $id, $showPageLinks);
        $this->page = _PAGE;
    }

function RenderLayout($header,$grid,$footer)
{
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">",
		 "<tr><td>",
			$grid,
		"</td></tr><tr><td>",$footer,"&nbsp;",$header,
		"</td></tr></table>";
}

//---------------------------
// Display link to first page
function Render_First($anchor=true)
{
	global $PHP_SELF;
	if ($anchor) {
	?>
		<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&fgid=',$_REQUEST['fgid'],'&fid=',$_REQUEST['fid'],'&',$this->id;?>_next_page=1"><?php echo $this->first;?></a> &nbsp;
	<?php
	} else {
		print "$this->first &nbsp; ";
	}
}

//--------------------------
// Display link to next page
function render_next($anchor=true)
{
	global $PHP_SELF;

	if ($anchor) {
	?>
	<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&fgid=',$_REQUEST['fgid'],'&fid=',$_REQUEST['fid'],'&',$this->id,'_next_page=',$this->rs->AbsolutePage() + 1 ?>"><?php echo $this->next;?></a> &nbsp;
	<?php
	} else {
		print "$this->next &nbsp; ";
	}
}

//------------------
// Link to last page

function render_last($anchor=true)
{
	global $PHP_SELF;

	if (!$this->db->pageExecuteCountRows) return;

	if ($anchor) {
	?>
		<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&fgid=',$_REQUEST['fgid'],'&fid=',$_REQUEST['fid'],'&',$this->id,'_next_page=',$this->rs->LastPageNo() ?>"><?php echo $this->last;?></a> &nbsp;
	<?php
	} else {
		print "$this->last &nbsp; ";
	}
}

// Link to previous page
function render_prev($anchor=true)
{
	global $PHP_SELF;
	if ($anchor) {
	?>
		<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&fgid=',$_REQUEST['fgid'],'&fid=',$_REQUEST['fid'],'&',$this->id,'_next_page=',$this->rs->AbsolutePage() - 1 ?>"><?php echo $this->prev;?></a> &nbsp;
	<?php
	} else {
		print "$this->prev &nbsp; ";
	}
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
          <table cellpadding="3" cellspacing="1" border="0" width="100%" class="tblForumTable">
          <tr class="tblForumTop">
            <th width="60%" height="40" ><?=_FORUM_TOPIC; ?></th>
            <th><?=_FORUM_AUTHOR; ?></th>
            <th><?=_FORUM_REPLIES; ?></th>
            <th colspan="2" ><?=_FORUM_LASTPOST; ?></th>
          </tr>
          <?
	while(!$this->rs->EOF){
	?>
          <tr>
		<td bgcolor="#FFFFFF" height="40">
			<a href="<?=$_SERVER['PHP_SELF']; ?>?modname=forum&mf=viewitem&fid=<?=$this->rs->fields['fitId']; ?>&fgid=<?=$this->rs->fields['fctId']; ?>" >
            <?=$this->rs->fields['fitTitle']; ?>
            </a>
		</td>
              <td bgcolor="#FFFFFF" >
                  <?
                      if ($this->rs->fields['userId']==0) {
			        ?><?=$this->rs->fields['fitName']; ?><?
                      } else {
                          ?><?=$forum->getMemberNameById($this->rs->fields['userId']); ?><?
                      }
                  ?>
		</td>
              <td bgcolor="#FFFFFF" align="center">
                  <?=$forum->getForumReplyNumber($this->rs->fields['fitId']); ?>
              </td>
              <td bgcolor="#FFFFFF" >
                  <?
                      $lastpost=$forum->getForumReplyById($this->rs->fields['fitId']);
                      if (($lastpost->recordcount())>0) {
                          echo adodb_date2("d M Y ",$lastpost->fields['fitCreate'])." "._FORUM_BY." ";
                      if ($lastpost->fields['userId']==0) {
			        ?><?=$lastpost->fields['fitName']; ?><?
                      } else {
                          ?><?=$forum->getMemberNameById($lastpost->fields['userId']); ?><?
                      }
                      }
                  ?>
              </td>
              <td bgcolor="#FFFFFF" >
                  <?
                      if (($lastpost->recordcount())>0) {
                          ?>
                          <a href="module.php?modname=forum&mf=viewitem&id=<?=$lastpost->fields['fitId']; ?>&fid=<?=$this->rs->fields['fitId']; ?>&fgid=<?=$lastpost->fields['fctId']; ?>">
                          <img src="modules/forum/images/newreply.gif" alt="<?=_FORUM_GOTO_LASTPOST; ?>" border="0"/></a>
                          <?
                      }
                  ?>
              </td>
          </tr>

	<?
		$this->rs->movenext();
	} // while
          ?></table><?
    $s = ob_get_contents();
	ob_end_clean();
	return $s;
}

}



?>
