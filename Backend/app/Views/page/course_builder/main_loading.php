<div class="col-xl-9 col-lg-12 order-lg-2 order-xl-1" id="course-builder-main-pane">
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="text-muted mt-2 mb-0"><?php echo lang('UI_Text.CB_Loading_Page_Content'); ?></p>
        </div>
    </div>
</div>
</div><!-- closes the <div class="row"> left open by left_menu.php - page/course_builder/main
        normally closes it via its own trailing </div></div>, so this stands in for that while
        the AJAX fetch below is in flight. Once it resolves, the fetched markup's own trailing
        "row" closer has nothing left to close (this tag already did it) and the fragment parser
        used to inject that markup (see courseBuilderReloadMain below) silently drops that
        unmatched end tag. -->
<script>
    // Fetches page/course_builder/main's markup for the currently selected page and swaps it
    // into #course-builder-main-pane, re-running any <script> tags it contains - element.innerHTML
    // doesn't execute those on its own, so they're pulled out and re-inserted as fresh script
    // elements. Exposed globally so cyu.php's option-editing handlers can call this instead of
    // location.reload(true) after a small edit, instead of reloading the whole page (header,
    // left menu, every asset) just to refresh this one pane.
    window.courseBuilderReloadMain = function() {
        var container = document.getElementById('course-builder-main-pane');
        if (!container) {
            location.reload(true);
            return;
        }

        var formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('SCORM/course_builder/Editor/page_content') ?>', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Failed to load page content');
                return response.text();
            })
            .then(function(html) {
                var temp = document.createElement('div');
                temp.innerHTML = html;

                var scripts = Array.prototype.slice.call(temp.querySelectorAll('script'));
                scripts.forEach(function(oldScript) {
                    oldScript.parentNode.removeChild(oldScript);
                });

                var parent = container.parentNode;
                Array.prototype.slice.call(temp.childNodes).forEach(function(node) {
                    parent.insertBefore(node, container);
                });
                parent.removeChild(container);

                scripts.forEach(function(oldScript) {
                    var newScript = document.createElement('script');
                    for (var i = 0; i < oldScript.attributes.length; i++) {
                        newScript.setAttribute(oldScript.attributes[i].name, oldScript.attributes[i].value);
                    }
                    newScript.textContent = oldScript.textContent;
                    document.body.appendChild(newScript);
                });

                // footer_view.php only loads CKEditor when a .ckeditor field is already in the
                // DOM at the moment IT renders - true for most pages, but not this one: any
                // .ckeditor field in this pane (e.g. types/text.php's Content editor) doesn't
                // exist yet at that point, it's only added right here. Load/init CKEditor
                // ourselves whenever this fetch brings one in, or it silently stays a plain
                // textarea forever.
                var ckTextareas = Array.prototype.slice.call(document.querySelectorAll('textarea.ckeditor'));
                if (ckTextareas.length) {
                    var initCkeditors = function() {
                        ckTextareas.forEach(function(textarea) {
                            // A simple toolbar (bold/italic/underline/lists/link) rather than
                            // CKEditor's full default (tables, images, source view, styles,
                            // colors...) - scoped to just this replace() call/page, so
                            // CKEditor's other, unrelated usages elsewhere in the app keep
                            // their normal full toolbar (public/assets/ckeditor/config.js).
                            CKEDITOR.replace(textarea, {
                                toolbar: [
                                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
                                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote'] },
                                    { name: 'links', items: ['Link', 'Unlink'] }
                                ],
                                removePlugins: 'elementspath',
                                resize_enabled: true
                                <?php if (session('theme_color') != 0): ?>
                                ,
                                contentsCss: ['<?= base_url(); ?>/public/assets/ckeditor/contents-dark.css']
                                <?php endif; ?>
                            });
                        });
                    };
                    if (window.CKEDITOR) {
                        initCkeditors();
                    } else {
                        var ckScript = document.createElement('script');
                        ckScript.src = '<?= base_url(); ?>/public/assets/ckeditor/ckeditor.js';
                        ckScript.onload = initCkeditors;
                        document.body.appendChild(ckScript);
                    }
                }
            })
            .catch(function() {
                container.innerHTML = '<div class="alert alert-danger"><?php echo lang('UI_Text.CB_Unable_Load_Page_Content'); ?> <a href="javascript:location.reload()"><?php echo lang('Buttons.CB_Retry'); ?></a></div>';
            });
    };

    courseBuilderReloadMain();
</script>
