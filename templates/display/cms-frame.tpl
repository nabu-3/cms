<!DOCTYPE html>{nabu_model model="bootstrap-3.3.7"}
<html lang="{$nb_language.ISO639_1}">
    {include file="display/parts/head.tpl"}
    <body{if array_key_exists('css_class', $nb_site_target) && strlen($nb_site_target.css_class)>0} class="{$nb_site_target.css_class}"{/if}>
        {include file="display/parts/header.tpl"}
        <div class="container-fluid">
            <div class="row">
                {include file="display/parts/sidebar.tpl"}
                <div class="col-md-10 col-md-offset-2 col-sm-9 col-sm-offset-3 main">
                    {if isset($breadcrumb_part) && is_array($breadcrumb_part)}
                        {nabu_breadcrumb sitemap=main_menu level_dropdown=true parts=$breadcrumb_part}
                    {else}
                        {nabu_breadcrumb sitemap=main_menu level_dropdown=true}
                    {/if}
                    <h1 class="page-header">{strip}
                        {if isset($title_part)}
                            {if is_string($title_part)}
                                {$nb_site_target.translation.title|sprintf:$title_part}
                            {elseif is_array($title_part)}
                                {$nb_site_target.translation.title|vsprintf:$title_part}
                            {/if}
                        {else}
                            {$nb_site_target.translation.title}
                        {/if}
                        {if isset($nb_site_target.translation.subtitle)}<small>{$nb_site_target.translation.subtitle}</small>{/if}
                    {/strip}</h1>
                    {if isset($content) && strlen($content) > 0}{$content}{/if}
                </div>
            </div>
        </div>
        {include file="display/parts/footer.tpl"}
        {include file="display/parts/js.tpl"}
    </body>
</html>
