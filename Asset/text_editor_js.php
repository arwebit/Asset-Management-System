<script type="text/javascript" src="../assets/js/texteditor/js/froala_editor.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/align.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/char_counter.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/code_beautifier.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/code_view.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/colors.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/draggable.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/emoticons.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/entities.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/file.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/font_size.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/font_family.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/fullscreen.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/image.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/image_manager.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/line_breaker.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/inline_style.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/link.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/lists.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/paragraph_format.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/paragraph_style.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/quick_insert.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/quote.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/table.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/save.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/url.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/video.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/help.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/print.min.js?v=<?php echo time(); ?>"></script>
<!--<script type="text/javascript" src="../assets/js/texteditor/js/third_party/spell_checker.min.js"></script>-->
<script type="text/javascript" src="../assets/js/texteditor/js/third_party/embedly.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/special_characters.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/plugins/word_paste.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript" src="../assets/js/texteditor/js/platform.js?v=<?php echo time(); ?>" charset="UTF-8"></script>

<script>
    (function () {
        new FroalaEditor('textarea#manual_description', {
            toolbarButtons: [['bold', 'italic', 'underline'], ['strikeThrough', 'subscript', 'superscript', 'insertHR'],
                ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'], ['fontFamily', 'fontSize', 'textColor', 'backgroundColor'],
                ['insertImage', 'embedly', 'insertTable', 'specialCharacters'], ['formatOL', 'formatUL']],
            imageInsertButtons: ['imageUpload', 'imageByURL'],
            events: {
                'image.beforeUpload': function (files) {
                    const editor = this;
                    if (files.length) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var result = e.target.result;
                            editor.image.insert(result, null, null, editor.image.get());
                        }
                        reader.readAsDataURL(files[0]);
                    }
                    return false;
                }
            }
        })
    })();

    (function () {
        new FroalaEditor('textarea#address', {
            toolbarButtons: [['bold', 'italic', 'underline'], ['fontFamily', 'fontSize', 'textColor', 'backgroundColor']]
        })
    })();
    
   (function () {
        new FroalaEditor('textarea#project_scope', {
            toolbarButtons: [['bold', 'italic', 'underline'], ['fontFamily', 'fontSize', 'textColor', 'backgroundColor']]
        })
    })();
    
       (function () {
        new FroalaEditor('textarea#project_address', {
            toolbarButtons: [['bold', 'italic', 'underline'], ['fontFamily', 'fontSize', 'textColor', 'backgroundColor']]
        })
    })();
    
    (function () {
        new FroalaEditor('textarea#remark', {
            toolbarButtons: [['bold', 'italic', 'underline'], ['fontFamily', 'fontSize', 'textColor', 'backgroundColor']]
        })
    })();
</script>
