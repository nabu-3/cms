{nabu_model model="bootstrap-3.3.7"}
<div class="login">
    {nabu_form horizontal="4:8" method=POST}
        <h1 class="text-center">{$nb_site_target.translation.subtitle}</h1>
        <div>
            <img src="/images/logo-256x256.png" alt="nabu-3" title="nabu-3" class="hidden-xs">
            <div class="login-box">
                {nabu_form_textbox label={nabu_static key=lbl_email_address}
                                   placeholder={nabu_static key=lbl_email_address}
                                   help={nabu_static key=txt_see_your_email_address}
                                   id=email name=login_email autofocus=true}
                {nabu_form_textbox label={nabu_static key=lbl_password}
                                   placeholder={nabu_static key=lbl_password}
                                   help={nabu_static key=txt_see_your_password}
                                   type=password id=passwd name=login_pass}
                {nabu_form_checkbox label={nabu_static key=chk_remember_me}
                                    id=rememberme name=login_remember check=T}
                <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
            </div>
        </div>
    {/nabu_form}
</div>
