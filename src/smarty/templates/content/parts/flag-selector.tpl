{if isset($lang_list) && is_array($lang_list) && count($lang_list)>0}
    <div class="lang-selector" data-toggle="nabu-lang-selector"{if isset($target) && strlen($target)>0} data-target="{$target}"{/if}{if isset($default_lang) && is_numeric($default_lang)} data-default-lang="{$default_lang}"{/if}>
        <ul class="list-inline">
            <li>{nabu_static key=lbl_languages}</li>
            {foreach from=$lang_list key=klang item=language}
                {strip}
                    <li data-id="{$klang}" lang="{$language.default_country_code}"{if isset($default_lang) && $default_lang==$klang} class="active"{/if}>
                        <a href="#" class="btn{if isset($default_lang) && $default_lang==$klang} btn-default{else} btn-link{/if}">
                            {if strlen($language.flag_url)>0}<span class="flag"><img src="{$language.flag_url}"></span>{/if}
                            <span{if strlen($language.flag_url)>0} class="sr-only"{/if}>{$language.name}</span>
                        </a>
                    </li>
                {/strip}
            {/foreach}
        </ul>
    </div>
{/if}
