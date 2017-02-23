<!DOCTYPE html>
<html lang="{$nb_language.ISO639_1}">
    {include file="display/parts/head.tpl"}
    <body{if array_key_exists('css_class', $nb_site_target) && strlen($nb_site_target.css_class)>0} class="{$nb_site_target.css_class}"{/if}>
        {include file="display/parts/header.tpl"}
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    {if strlen($content) > 0}{$content}{/if}
                </div>
            </div>
        </div>
        {include file="display/parts/footer.tpl"}
        {include file="display/parts/js.tpl"}
    </body>
</html>
