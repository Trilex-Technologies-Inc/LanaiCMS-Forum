<?php
if (!eregi("setting.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

// Assuming you have a Forum class in your module.php
$forum = new Forum(); 
?>

<span class="txtContentTitle">Forum Module Installation</span><br/><br/>

<OL>
<?php
global $cfg, $db;

switch($_REQUEST['step']) {
    case "1":
        // Create necessary tables
        ?>
        <LI>Creating Table <?=$cfg['tablepre']."forum_category" ?> 
        <?php
        
        // Drop table if exists and create forum_category
        $sql = "DROP TABLE IF EXISTS " . $cfg['tablepre'] . "forum_category;";
        $db->execute($sql);
        
        $sql = "CREATE TABLE " . $cfg['tablepre'] . "forum_category (
                fctId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                userId INTEGER UNSIGNED NOT NULL,
                fctParentId INTEGER UNSIGNED NOT NULL DEFAULT 0,
                fctTitle VARCHAR(80) NULL,
                fctDescription TEXT NULL,
                fctOrder INTEGER UNSIGNED NULL,
                fctActive ENUM('y','n') default 'y',
                fctCreate TIMESTAMP NULL,
                PRIMARY KEY(fctId)
                )";
        $rs1 = $db->execute($sql);
        
        if (empty($rs1)) {
            ?><span style="color:red;">Error!</span><?php
        } else {
            ?><span style="color:green;">OK</span><?php
        }
        
        ?>
        <LI>Creating Table <?=$cfg['tablepre']."forum_items" ?> 
        <?php
        
        // Drop table if exists and create forum_items
        $sql = "DROP TABLE IF EXISTS " . $cfg['tablepre'] . "forum_items;";
        $db->execute($sql);
        
        $sql = "CREATE TABLE " . $cfg['tablepre'] . "forum_items (
                fitId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                fitParentId INTEGER UNSIGNED NOT NULL DEFAULT 0,
                fctId INTEGER UNSIGNED NOT NULL DEFAULT 0,
                userId INTEGER UNSIGNED NOT NULL DEFAULT 0,
                fitName VARCHAR(45) NULL,
                fitTitle VARCHAR(80) NULL,
                fitDescription TEXT NULL,
                fitCreate TIMESTAMP NULL,
                PRIMARY KEY(fitId)
                )";
        $rs2 = $db->execute($sql);
        
        if (empty($rs2)) {
            ?><span style="color:red;">Error!</span><?php
        } else {
            ?><span style="color:green;">OK</span><?php
        }
        
        ?>
        <LI>Creating Table <?=$cfg['tablepre']."forum_spam_keyword" ?> 
        <?php
        
        // Drop table if exists and create forum_spam_keyword
        $sql = "DROP TABLE IF EXISTS " . $cfg['tablepre'] . "forum_spam_keyword;";
        $db->execute($sql);
        
        $sql = "CREATE TABLE " . $cfg['tablepre'] . "forum_spam_keyword (
                keyId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                keyWord VARCHAR(45) NULL,
                PRIMARY KEY(keyId)
                )";
        $rs3 = $db->execute($sql);
        
        if (empty($rs3)) {
            ?><span style="color:red;">Error!</span><?php
        } else {
            ?><span style="color:green;">OK</span><?php
        }
        
        // Add some default spam keywords
        if (!empty($rs3)) {
            $defaultSpamWords = array('viagra', 'casino', 'porn', 'pharmacy', 'cialis');
            foreach ($defaultSpamWords as $word) {
                $sql = "INSERT INTO " . $cfg['tablepre'] . "forum_spam_keyword (keyWord) VALUES ('" . $db->escape($word) . "')";
                $db->execute($sql);
            }
            ?><br/><span style="color:green;">Added default spam keywords</span><?php
        }
        
        if ((!empty($rs1)) AND (!empty($rs2)) AND (!empty($rs3))) {
            ?>
            <br><br><input type="button" class="inputButton" value="Next ->" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=install&step=2';">
            <?php
        }
        
        break;
        
    case "2":
        // Create module data and menu
        // Check if module data exists
        $sql = "SELECT COUNT(*), modId FROM " . $cfg['tablepre'] . "module
                WHERE modName = 'forum' GROUP BY modId";
        $rs = $db->execute($sql);
        
        if (($rs->fields[0]) > 0) {
            // Delete from module table
            $sql = "DELETE FROM " . $cfg['tablepre'] . "module
                    WHERE modId = " . $rs->fields[1];
            $db->execute($sql);
            
            // Delete from menu table
            $sql = "DELETE FROM " . $cfg['tablepre'] . "menu
                    WHERE modId = " . $rs->fields[1] . " AND mnuType = 'm'";
            $db->execute($sql);
            
            // Delete from privilege table
            $sql = "DELETE FROM " . $cfg['tablepre'] . "privilege
                    WHERE modId = " . $rs->fields[1];
            $db->execute($sql);
        }
        
        // Select for max order
        $sql = "SELECT MAX(modOrder) FROM " . $cfg['tablepre'] . "module";
        $rsOModule = $db->execute($sql);
        
        ?>
        <LI>Creating module data 
        <?php
        
        // Create module data
        $sql = "INSERT INTO " . $cfg['tablepre'] . "module
                (modTitle, modName, modActive, modOrder, modSetting)
                VALUES ('Forum', 'forum', 'y', " . (($rsOModule->fields[0]) + 1) . ", 'y')";
        $rs1 = $db->execute($sql);
        
        // Select module data
        $sql = "SELECT COUNT(*), modId FROM " . $cfg['tablepre'] . "module
                WHERE modName = 'forum' GROUP BY modId";
        $rsIModule = $db->execute($sql);
        
        // Select menu data
        $sql = "SELECT MAX(mnuOrder) FROM " . $cfg['tablepre'] . "menu";
        $rsOMenu = $db->execute($sql);
        
        ?>
        <LI>Creating menu data 
        <?php
        
        // Create menu data
        $sql = "INSERT INTO " . $cfg['tablepre'] . "menu
                (mnuParentId, mnuTitle, modId, mnuType, mnuActive, mnuOrder)
                VALUES (0, 'Forum', " . $rsIModule->fields[1] . ", 'm', 'y', " . (($rsOMenu->fields[0]) + 1) . ")";
        $rs2 = $db->execute($sql);
        
        ?>
        <LI>Creating privilege data 
        <?php
        
        // Create privilege data
        $sql = "INSERT INTO " . $cfg['tablepre'] . "privilege
                (modAccess, modId, userPrivilege)
                VALUES ('y', " . $rsIModule->fields[1] . ", 'a')";
        $rs3 = $db->execute($sql);
        
        // Add additional privileges for different user levels if needed
        $sql = "INSERT INTO " . $cfg['tablepre'] . "privilege
                (modAccess, modId, userPrivilege)
                VALUES ('y', " . $rsIModule->fields[1] . ", 'm')";
        $db->execute($sql);
        
        $sql = "INSERT INTO " . $cfg['tablepre'] . "privilege
                (modAccess, modId, userPrivilege)
                VALUES ('y', " . $rsIModule->fields[1] . ", 'u')";
        $db->execute($sql);
        
        if ((!empty($rs1)) AND (!empty($rs2)) AND (!empty($rs3))) {
            ?>
            <br><br><input type="button" class="inputButton" value="Install Complete - Click to Setting" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>';">
            <?php
        }
        
        break;
        
    default:
        // Check necessary environment
        ?>
        <LI>PHP Safe Mode is 
        <?php
        if (ini_get('safe_mode')) {
            ?><span style="color:green;">ON</span><?php
        } else {
            ?><span style="color:red;">OFF</span><?php
        }
        
        ?>
        <LI>Module Directory is 
        <?php
        
        
        if ((is_writable($cfg['dir'] . $sys_lanai->getPath() . "modules")) AND (is_writable($cfg['dir'] . $sys_lanai->getPath() . "modules"))) {
            ?>
            <span style="color:green;">WRITABLE</span><br/><br/>
            <input type="button" class="inputButton" value="Next ->" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=install&step=1';">
            <?php
        } else {
            ?>
            <span style="color:red;">NOT WRITABLE - Please check directory permissions</span>
            <?php
        }
}
?>
</OL>