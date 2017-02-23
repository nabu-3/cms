<form>{$var01}
    {nabu_form_textbox model="smarty-3.7" file="models/smarty-3.7/textbox.tpl"}
    <div class="form-group">
        <label for="exampleInputEmail1">{nabu_static key=email_address}</label>
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
        <p class="help-block">Example block-level help text here.</p>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">{nabu_static key=password}</label>
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        <p class="help-block">Example block-level help text here.</p>
    </div>
    <div class="checkbox">
        <label><input type="checkbox"> Check me out</label>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>
