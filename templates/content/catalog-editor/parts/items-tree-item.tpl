{strip}
    {if isset($languages) && is_array($languages) && array_key_exists('translations', $li) && is_array($li.translations)}
        {foreach from=$languages key=klang item=language}
            <div lang="{$language.default_country_code}">
                <span class="flag">
                {if strlen($language.flag_url)>0}<img src="{$language.flag_url}">{else}[{$language.name}]{/if}
                </span>
                <div class="text">
                    {if array_key_exists($klang, $li.translations) && array_key_exists($field_name, $li.translations[$klang])}
                        {$li.translations[$klang][$field_name]}
                    {else}
                        &lt;Nonamed&gt;
                    {/if}
                </div>
            </div>
        {/foreach}
        {assign var=final_content value=''}
    {else}
        {assign var=final_content value=$li.translation[$field_name]}
    {/if}
{/strip}
