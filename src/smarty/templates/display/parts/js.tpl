<script type="text/javascript" src="/assets/js/jquery-3.1.0.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.js"></script>
<script type="text/javascript" src="/assets/js/jquery.sprintf.js"></script>
<script type="text/javascript" src="/runtime/nbfw/lib/js/nabu.js"></script>
<script type="text/javascript" src="/runtime/nbfw/lib/js/nabu-bootstrap.js"></script>
<script type="text/javascript" src="/runtime/assets/ckeditor/4.6.2/ckeditor.js"></script>
<script type="text/javascript" src="/runtime/assets/ckeditor/4.6.2/adapters/jquery.js"></script>
{if is_array($nb_site_target.attributes) && array_key_exists('visual_editor', $nb_site_target.attributes) && $nb_site_target.attributes.visual_editor}
    <script type="text/javascript">
        mxLanguage = "{$nb_language.ISO639_1}";
        mxLanguages = [ '{$nb_language.ISO639_1}' ];
    </script>
    <script type="text/javascript" src="/runtime/nbfw/visualeditor/js/nabu-ve.js"></script>
    <script type="text/javascript" src="/runtime/nbfw/visualeditor/js/nabu-ve-bootstrap.js"></script>
    <script type="text/javascript" src="/js/visual-editor.js"></script>
{/if}
<script type="text/javascript" src="/js/commons.js"></script>
{if strlen($nb_site_target.script_file)>0}<script type="text/javascript" src="/js/{$nb_site_target.script_file}"></script>{/if}
