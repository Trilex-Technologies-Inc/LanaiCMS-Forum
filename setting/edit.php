<?php

if (!isset($_SERVER['PHP_SELF']) || !preg_match("/setting\.php/i", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))));
$modfunction = "modules/$module_name/module.php";

if (file_exists($modfunction)) {
    include_once($modfunction);
} else {
    die("Module file not found: $modfunction");
}

$forum = new Forum();

function R($key, $default = null) {
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

$action = R('ac');

switch ($action) {

    case "newcat":
        if (empty(R('fctTitle'))) {
            $sys_lanai->getErrorBox(_REQUIRE_FIELDS . ". <a href=\"#\" onclick=\"history.back();\">" . _BACK . "</a>");
        } else {
            $forum->setNewGroup(R('fctParentId'), R('fctTitle'), R('fctDescription'));
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=category");
        break;

    case "newword":
        $wordarr = R('keyWord', array());
        if (!is_array($wordarr)) $wordarr = array($wordarr);
        foreach ($wordarr as $item) {
            if (!empty($item)) $forum->setNewWord($item);
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=rudeword");
        break;

    case "edit":
        if (empty(R('fctTitle'))) {
            $sys_lanai->getErrorBox(_REQUIRE_FIELDS . ". <a href=\"#\" onclick=\"history.back();\">" . _BACK . "</a>");
        } else {
            $forum->setEditGroup(R('fctId'), R('fctParentId'), R('fctTitle'), R('fctDescription'));
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=category");
        break;

    case "editword":
        if (empty(R('keyWord'))) {
            $sys_lanai->getErrorBox(_REQUIRE_FIELDS . ". <a href=\"#\" onclick=\"history.back();\">" . _BACK . "</a>");
        } else {
            $forum->setEditWord(R('keyId'), R('keyWord'));
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=rudeword");
        break;

    case "mactive":
        $midarr = R('mid', array());
        if (is_array($midarr)) {
            foreach ($midarr as $id) {
                $rs = $forum->getSubGroupById($id);
                $val = ($rs && isset($rs->fields['fctActive']) && $rs->fields['fctActive'] == "y") ? "n" : "y";
                $forum->setActiveGroup($id, $val);
            }
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=category");
        break;

    case "active":
        $forum->setActiveGroup(R('fig'), R('vl'));
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=category");
        break;

    case "mdelete":
        $midarr = R('mid', array());
        if (is_array($midarr)) {
            foreach ($midarr as $id) $forum->setDeleteGroup($id);
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=category");
        break;

    case "mitemdelete":
        $midarr = R('mid', array());
        if (is_array($midarr)) {
            foreach ($midarr as $id) $forum->setDeleteItem($id);
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=item");
        break;

    case "mworddelete":
        $midarr = R('mid', array());
        if (is_array($midarr)) {
            foreach ($midarr as $item) $forum->setDeleteWord($item);
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=rudeword");
        break;

    case "morder":
        $midarr = R('fctOrderId', array());
        $orders = R('fctOrder', array());
        if (is_array($midarr)) {
            foreach ($midarr as $i => $id) {
                $orderValue = isset($orders[$i]) ? $orders[$i] : 0;
                $forum->setForumOrderGroup($id, $orderValue);
            }
        }
        $sys_lanai->go2Page($_SERVER['PHP_SELF'] . "?modname=" . $module_name . "&mf=category");
        break;

    default:
      
        break;
}

?>
