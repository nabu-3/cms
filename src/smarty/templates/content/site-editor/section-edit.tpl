{nabu_model model="bootstrap-3.3.7"}
<ul class="nav nav-tabs nav-stacked nav-stacked-right" role="tablist">
    <li role="presentation" class="active"><a href="#section_main" aria-controls="section_main" role="tab" data-toggle="tab">Principal</a></li>
    <li role="presentation"><a href="#section_link" aria-controls="section_link" role="tab" data-toggle="tab">Enlace</a></li>
    <li role="presentation"><a href="#section_contents" aria-controls="section_contents" role="tab" data-toggle="tab">Contenidos</a></li>
    <li role="presentation"><a href="#section_layout" aria-controls="section_layout" role="tab" data-toggle="tab">Maquetación</a></li>
    <li role="presentation"><a href="#section_options" aria-controls="section_options" role="tab" data-toggle="tab">Opciones</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="section_main">
    </div>
    <div role="tabpanel" class="tab-pane" id="section_link">
    </div>
    <div role="tabpanel" class="tab-pane" id="section_contents">
        {nabu_form vertical=true method=POST}
            {nabu_form_row}
                <div class="col-sm-12">
                    {nabu_form_row}
                        <div class="col-sm-3">{strip}
                            {if strlen($edit_site_target_section.translation.main_image)>0}
                                <img src="{$base_url}{$edit_site_target_section.translation.main_image}">
                            {else}
                                Imagen no disponible
                            {/if}
                        {/strip}</div>
                        <div class="col-sm-9">
                            {nabu_form_row}
                                <div class="col-sm-12">
                                    {nabu_form_textbox name=title from=$edit_site_target_section field=title label='Título'}
                                </div>
                            {/nabu_form_row}
                            {nabu_form_row}
                                <div class="col-sm-12">
                                    {nabu_form_textbox name=subtitle from=$edit_site_target_section field=subtitle label='Subtítulo'}
                                </div>
                            {/nabu_form_row}
                        </div>
                    {/nabu_form_row}
                </div>
            {/nabu_form_row}
            {nabu_form_row}
                <div class="col-sm-12">
                    {nabu_form_textbox type=textarea rows=3 name=opening from=$edit_site_target_section field=opening label='Entradilla'}
                </div>
            {/nabu_form_row}
            {nabu_form_row}
                <div class="col-sm-12">
                    {nabu_form_textbox type=textarea rows=8 name=content from=$edit_site_target_section field=content label='Cuerpo'}
                </div>
            {/nabu_form_row}
            {nabu_form_row}
                <div class="col-sm-12">
                    {nabu_form_textbox type=textarea rows=3 name=footer from=$edit_site_target_section field=footer label='Pie'}
                </div>
            {/nabu_form_row}
        {/nabu_form}
    </div>
    <div role="tabpanel" class="tab-pane" id="section_layout">
    </div>
    <div role="tabpanel" class="tab-pane" id="section_options">
    </div>
</div>
