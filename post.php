<?php
if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(__FILE__));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$forum = new Forum();
?>


<script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce',
        height: 300,
        license_key: 'gpl',
        menubar: true,
        plugins: "preview paste importcss searchreplace autolink code visualblocks fullscreen image link media table charmap help emoticons",
        toolbar: 'undo redo | formatselect | bold italic underline | link image media table | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat',
        paste_as_text: false,
        content_css: false
    });
</script>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="txtContentTitle mb-4"><?= _FORUM_POST; ?></h2>
            
            <div class="card">
                <div class="card-body">
             <?php if ((!empty($_SESSION['uid'])) && ($_SESSION['uid'] > 0)): ?>
                    <p class="mb-4"><?= _FORUM_POST_INSTRUCTION; ?></p>
                     <?php endif; ?>
                    
                    <div class="mb-4">
                        <button type="submit" form="postForm" class="btn btn-primary">
                            <img src="theme/<?= $cfg['theme']; ?>/images/save.gif" class="me-1" />
                            <?= _SAVE; ?>
                        </button>
                        <a href="javascript:history.back();" class="btn btn-secondary ms-2">
                            <img src="theme/<?= $cfg['theme']; ?>/images/back.gif" class="me-1" />
                            <?= _CANCEL; ?>
                        </a>
                    </div>
                     <?php if ((!empty($_SESSION['uid'])) && ($_SESSION['uid'] > 0)): ?>
                    <form id="postForm" name="post" method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="modname" value="<?= $module_name; ?>" />
                        <input type="hidden" name="mf" value="edit" />
                        <input type="hidden" name="ac" value="post" />
                        <input type="hidden" name="fgid" value="<?= $_REQUEST['fgid']; ?>" />
                        
                        <?php if ((empty($_SESSION['uid'])) || ($_SESSION['uid'] < 0)): ?>
                        <div class="mb-3">
                            <label for="fitName" class="form-label"><?= _FORUM_NAME; ?></label>
                            <input type="text" name="fitName" id="fitName" class="form-control" placeholder="<?= _FORUM_NAME; ?>" />
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="fitTitle" class="form-label"><?= _FORUM_SUBJECT; ?></label>
                            <input type="text" name="fitTitle" id="fitTitle" class="form-control" placeholder="<?= _FORUM_SUBJECT; ?>" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="fitDescription" class="form-label"><?= _FORUM_MESSAGE; ?></label>
                            <textarea name="fitDescription" id="fitDescription" class="form-control tinymce">This is some <strong>sample text</strong>.</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="captext" class="form-label"><?= _FORUM_CAPTCHA; ?></label>
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <input type="text" name="captext" id="captext" class="form-control" size="10" maxlength="5" />
                                </div>
                                <div class="col-auto">
                                    <img src="images/captcha.php?<?= time(); ?>" alt="captcha" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </form>
                     <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

