<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
<script type="text/javascript" src="/assets/js/jquery.sprintf.min.js"></script>
<script type="text/javascript" src="/runtime/nbfw/lib/js/nabu.min.js"></script>
<script type="text/javascript">
    nabu.setMinify(false);
</script>
<script type="text/javascript" src="/runtime/nbfw/lib/js/nabu-bootstrap.js"></script>
{if is_array($nb_site_target.attributes) && array_key_exists('ckeditor', $nb_site_target.attributes) && $nb_site_target.attributes.ckeditor}
    <script type="text/javascript" src="/runtime/assets/ckeditor/js/nabu-ck.js"></script>
    <script type="text/javascript" src="/runtime/assets/ckeditor/js/nabu-ck-bootstrap.js"></script>
{/if}
{if is_array($nb_site_target.attributes) && array_key_exists('aceeditor', $nb_site_target.attributes) && $nb_site_target.attributes.aceeditor}
    <script type="text/javascript" src="/runtime/assets/ace/js/nabu-ace.js"></script>
    <script type="text/javascript" src="/runtime/assets/ace/js/nabu-ace-bootstrap.js"></script>
{/if}
{if is_array($nb_site_target.attributes) && array_key_exists('visual_editor', $nb_site_target.attributes) && $nb_site_target.attributes.visual_editor}
    <script type="text/javascript">
        mxLanguage = "{$nb_language.ISO639_1}";
        mxLanguages = [ '{$nb_language.ISO639_1}' ];
    </script>
    <script type="text/javascript" src="/runtime/nbfw/visualeditor/js/nabu-ve.min.js"></script>
    <script type="text/javascript" src="/runtime/nbfw/visualeditor/js/nabu-ve-bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/visual-editor.js"></script>
{/if}
<script type="text/javascript" src="/js/commons.js"></script>
{if strlen($nb_site_target.script_file)>0}<script type="text/javascript" src="/js/{$nb_site_target.script_file}"></script>{/if}
