{strip}
    {if is_numeric($li.catalog_taxonomy_id) && array_key_exists($li.catalog_taxonomy_id, $edit_catalog.taxonomies)}
        <div class="taxonomy">{$edit_catalog.taxonomies[$li.catalog_taxonomy_id].translation.title}</div>
    {/if}
    {if isset($languages) && is_array($languages) && array_key_exists('translations', $li) && is_array($li.translations)}
        {foreach from=$languages key=klang item=language}
            <div lang="{$language.default_country_code}">
                <span class="flag">
                {if strlen($language.flag_url)>0}<img src="{$language.flag_url}">{else}[{$language.name}]{/if}
                </span>
                <div class="text">
                    {assign var=strcount value=0}
                    {if array_key_exists($klang, $li.translations)}
                        {if array_key_exists('sku', $li.translations[$klang]) && strlen($li.translations[$klang].sku) > 0}
                            <span class="sku">{$li.translations[$klang].sku}</span>
                            {assign var=strcount value=$strcount+strlen($li.translations[$klang].sku)}
                        {/if}
                        {if array_key_exists($field_name, $li.translations[$klang])}
                            {$li.translations[$klang][$field_name]|escape:"html"}
                            {assign var=strcount value=$strcount+strlen({$li.translations[$klang][$field_name]|escape:"html"})}
                        {/if}
                    {/if}
                    {if $strcount===0}
                        <span class="label label-danger">{nabu_static key=lbl_empty}</span>
                    {/if}
                </div>
            </div>
        {/foreach}
        {assign var=final_content value=''}
    {else}
        {assign var=final_content value=$li.translation[$field_name]}
    {/if}
{/strip}
