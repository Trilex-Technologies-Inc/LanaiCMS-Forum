<?
    /**
	 * Forum
	 *
	 * @package
	 * @author Administrator
	 * @copyright Copyright (c) 2006
	 * @version $Id: module.php,v 1.2 2008/07/25 02:37:25 redlinesoft Exp $
	 * @access public
	 **/
	class Forum {

		var $uid;
		var $db;
		var $cfg;
		var $_sql;


		function Forum () {
			global $db,$cfg;
			$this->db=$db;
			$this->cfg=$cfg;
        if (!empty($_SESSION['uid']))
			$this->uid=$_SESSION['uid'];
			//$this->db->debug=true;
		}

        /*
        function getForumGroup ($gid=0,$active=true) {
            if ($gid==0) {
				if ($active) {
					$sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
                        WHERE fctActive='y' ORDER BY fctCreate DESC";
				} else {
					$sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
							ORDER BY fctCreate DESC";
				}
            } else {
					$sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
                        WHERE fctId=$gid ORDER BY fctCreate DESC";
            }
            return $this->db->execute($sql);
        }

        function getForumItem($fid=0){
             if ($fid==0) {
					$sql="SELECT * FROM ".$this->cfg['tablepre']."forum_items
							ORDER BY fitId DESC";
            } else {
					$sql="SELECT * FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fitId=$fid ";
            }
            return $this->db->execute($sql);
        }
        */

        function getActiveGroupForum() {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
                        WHERE fctActive='y' AND fctParentId=0 ORDER BY fctOrder DESC";
             return $this->db->execute($sql);
        }

        function getActiveSubGroupForum($cid) {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
                        WHERE fctActive='y' AND fctParentId=$cid ORDER BY fctOrder DESC";
             return $this->db->execute($sql);
        }

        function getSubGroupTopicNumber($cid) {
            $sql="SELECT COUNT(*) AS total FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fctId=$cid AND fitParentId=0 ";
            $rs=$this->db->execute($sql);
             return ($rs->fields['total']);
        }

        function getSubGroupReplyNumber($cid) {
            $sql="SELECT COUNT(*) AS total FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fctId=$cid AND fitParentId>0 ";
            $rs=$this->db->execute($sql);
             return ($rs->fields['total']);
        }

        function getForumItemBySubGroup($cid){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fctId=$cid AND fitParentId=0 ORDER BY fitCreate DESC";
            $this->_sql=$sql;
             return $this->db->execute($sql);
        }

        function getSubGroupById($cid){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
            WHERE fctId=$cid ";
             return ($this->db->execute($sql));
        }

        function getMemberNameById($uid) {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."user
                    WHERE userId=$uid ";
            $rs=$this->db->execute($sql);
            if ($rs->recordcount()>0) {
                return ($rs->fields['userLogin']);
            } else {
                return "Guest";
            }
        }

        function getForumReplyNumber($fid){
            $sql="SELECT COUNT(*) AS total FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fitParentId=$fid ";
            $rs=$this->db->execute($sql);
             return ($rs->fields['total']);
        }

        function getForumReplyById($fid){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fitParentId=$fid ORDER BY fitCreate DESC";
            return ($this->db->execute($sql));
        }

        function getForumItem($fid){
             $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fitId=$fid";
            return ($this->db->execute($sql));
        }

        function getForumItems(){
             $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_items ORDER BY fitId DESC";
             $this->_sql=$sql;
            return ($this->db->execute($sql));
        }

        function getForumItemReply($fid){
             $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_items
                        WHERE fitParentId=$fid ORDER BY fitCreate DESC";
            $this->_sql=$sql;
            return ($this->db->execute($sql));
        }

        function setPostForum($fid,$fig,$userid,$fitName,$fitTitle,$fitDescription){
            $sql="INSERT INTO ".$this->cfg['tablepre']."forum_items 
                    (fitParentId,fctId,userId,fitName,fitTitle,fitDescription,fitCreate)
                    VALUES ($fid,$fig,$userid,'".$fitName."','".$fitTitle."','".$fitDescription."',NOW())";
            return ($this->db->execute($sql));
        }

        function getForumGroup(){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
                    WHERE fctParentId=0 ORDER BY fctOrder ASC";
             return ($this->db->execute($sql));
        }

        function getForumSubGroup($cid){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_category
                    WHERE fctParentId=$cid";
             return ($this->db->execute($sql));
        }

        function setDeleteGroup($cid) {
            $sql="DELETE FROM ".$this->cfg['tablepre']."forum_category
                    WHERE fctId=$cid OR fctParentId=$cid";
            return ($this->db->execute($sql));
        }

        function setActiveGroup($cid,$vl) {
            $sql="UPDATE ".$this->cfg['tablepre']."forum_category
                    SET fctActive='".$vl."'
                    WHERE fctId=$cid";
            return ($this->db->execute($sql));
        }

        function setForumOrderGroup($cid,$vl){
            $sql="UPDATE ".$this->cfg['tablepre']."forum_category
                    SET fctOrder='".$vl."'
                    WHERE fctId=$cid";
             return ($this->db->execute($sql));
        }

        function setNewGroup($fig,$fctTitle,$fctDescription){
            $sql="INSERT INTO ".$this->cfg['tablepre']."forum_category
                    (userId,fctParentId,fctTitle,fctDescription,fctOrder,fctActive,fctCreate)
                    VALUES (".$this->uid.",".$fig.",'".$fctTitle."','".$fctDescription."',0,'y',NOW())";
             return ($this->db->execute($sql));
        }

        function setEditGroup($fid,$fig,$fctTitle,$fctDescription){
            $sql="UPDATE ".$this->cfg['tablepre']."forum_category
                    SET fctParentId=".$fig.",fctTitle='".$fctTitle."',fctDescription='".$fctDescription."'
                    WHERE fctId=$fid";
             return ($this->db->execute($sql));
        }

        function getRudeWord() {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_spam_keyword
                    ORDER BY keyWord ASC";
            $this->_sql=$sql;
            return ($this->db->execute($sql));
        }

        function setNewWord($word){
            $sql="INSERT INTO ".$this->cfg['tablepre']."forum_spam_keyword
                    (keyWord) VALUES ('".$word."')";
            return ($this->db->execute($sql));
        }

        function setDeleteWord($wid){
            $sql="DELETE FROM ".$this->cfg['tablepre']."forum_spam_keyword
                    WHERE keyId=$wid";
            return ($this->db->execute($sql));
        }

        function getWordById($wid){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_spam_keyword
                    WHERE keyId=$wid";
            return ($this->db->execute($sql));
        }

        function setEditWord($wid,$word) {
            $sql="UPDATE ".$this->cfg['tablepre']."forum_spam_keyword
                    SET keyWord='".$word."'
                    WHERE keyId=$wid";
            return ($this->db->execute($sql));
        }

        function getWordArray(){
             $sql="SELECT * FROM ".$this->cfg['tablepre']."forum_spam_keyword";
             $rs=$this->db->execute($sql);
             $arr=array();
             while (!$rs->EOF) {
                array_push($arr,$rs->fields['keyWord']);
                $rs->movenext();
             }
             return $arr;
        }

        function getFilterString($str,$badword,$repstr){
            foreach ($badword as $baditem) {
                $str=eregi_replace($baditem,$repstr,$str);
            }
            return $str;
        }

        function setDeleteItem($iid){
            $sql="DELETE FROM ".$this->cfg['tablepre']."forum_items
                    WHERE fitId=$iid";
            return ($this->db->execute($sql));
        }

        function cleanHtml($words){
            $words = preg_replace("'<script[^>]*>.*?</script>'si","",$words);
        	$words = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $words);
        	$words = preg_replace('/<!--.+?-->/','',$words);
        	$words = preg_replace('/{.+?}/','',$words);
        	$words = preg_replace('/&nbsp;/',' ',$words);
        	$words = preg_replace('/&amp;/',' ',$words);
        	$words = preg_replace('/&quot;/',' ',$words);
        	$words = strip_tags($words);
        	$words = htmlspecialchars($words);
            return $words;
        }




    }
?>
