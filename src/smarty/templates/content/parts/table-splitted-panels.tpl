{nabu_model model="bootstrap-3.3.7"}
<div class="split-panels" data-toggle="nabu-split-panels" data-split-direction="horizontal" data-split-method="flex">
    <div class="split-content">
        {strip}
            {assign var=empty_message value=false}
            {if isset($section)}
                {assign var=info_section value=false}
                {if is_string($section) && strlen($section)>0}
                    {nabu_assign var=info_section section=$section}
                {elseif is_array($section) && array_key_exists('translation', $section) && count($section.translation)>0}
                    {assign var=info_section value=$section}
                {/if}
                {if is_array($info_section) && array_key_exists('translation', $info_section) && is_array($info_section.translation) && array_key_exists('content', $info_section.translation)}
                    {assign var=empty_message value=$info_section.translation.content}
                {/if}
            {/if}
        {/strip}
        {nabu_table id=item_list data=$data metadata=$metadata
                    selectable=true draw_empty=true empty_message=$empty_message
                    bordered=true striped=true hover=true condensed=true
                    search=true pager=true size=10 column_selector=true
                    api=api_call editor=item_edit edit_button=line}
    </div>
    <div class="split-separator"><div class="split-separator-inner"></div></div>
    <div class="split-content">
        <div class="box box-default">
            <div class="box-heading">Hola</div>
            <div class="box-body">Lorem ipsum dolor sit amet.</div>
        </div>
    </div>
</div>
