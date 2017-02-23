{nabu_model model="bootstrap-3.3.7"}
{nabu_form vertical=true method=POST}
    {nabu_form_row}
        <div class="col-sm-12">
            {nabu_form_row}
                <div class="col-sm-3">{strip}
                    {if strlen($edit_site_target.translation.main_image)>0}
                        <img src="{$base_url}{$edit_site_target.translation.main_image}">
                    {else}
                        Imagen no disponible
                    {/if}
                {/strip}</div>
                <div class="col-sm-9">
                    {nabu_form_row}
                        <div class="col-sm-12">
                            {nabu_form_textbox name=title from=$edit_site_target field=title label='Título'}
                        </div>
                    {/nabu_form_row}
                    {nabu_form_row}
                        <div class="col-sm-12">
                            {nabu_form_textbox name=subtitle from=$edit_site_target field=subtitle label='Subtítulo'}
                        </div>
                    {/nabu_form_row}
                </div>
            {/nabu_form_row}
        </div>
    {/nabu_form_row}
    {nabu_form_row}
        <div class="col-sm-12">
            {nabu_form_textbox type=textarea rows=3 name=opening from=$edit_site_target field=opening label='Entradilla'}
        </div>
    {/nabu_form_row}
    {nabu_form_row}
        <div class="col-sm-12">
            {nabu_form_textbox type=textarea rows=8 name=content from=$edit_site_target field=content label='Cuerpo'}
        </div>
    {/nabu_form_row}
    {nabu_form_row}
        <div class="col-sm-12">
            {nabu_form_textbox type=textarea rows=3 name=footer from=$edit_site_target field=footer label='Pie'}
        </div>
    {/nabu_form_row}
{/nabu_form}
