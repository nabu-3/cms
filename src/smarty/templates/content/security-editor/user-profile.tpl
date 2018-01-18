{nabu_model model="bootstrap-3.3.7"}
{nabu_form method=POST}
    {nabu_form_row}
        {nabu_form_static class="col-md-4" label="Alias" id=login name=login from=$edit_user field=login}
    {/nabu_form_row}
    {nabu_form_row}
        {nabu_form_textbox class="col-md-4" label="Nombre" id=first_name name=first_name autofocus=true from=$edit_user field=first_name}
        {nabu_form_textbox class="col-md-8" label="Apellidos" id=last_name name=last_name from=$edit_user field=last_name}
    {/nabu_form_row}
    {nabu_form_fieldset title="Cambia tu contraseña"}
        {nabu_form_row}
            {nabu_form_textbox type=password class="col-md-3" label="Introduce tu Contraseña" id=pass_1 name=pass_1}
            {nabu_form_textbox type=password class="col-md-3" label="Repite tu Contraseña" id=pass_2 name=pass_2}
        {/nabu_form_row}
    {/nabu_form_fieldset}
    {nabu_form_commands}
        <button type="submit" class="btn btn-success pull-right" name="Update"><i class="fa fa-check"></i> Guardar</button>
    {/nabu_form_commands}
{/nabu_form}
