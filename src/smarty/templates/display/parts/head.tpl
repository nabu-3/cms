<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{strip}
        {if isset($title_part)}
            {if is_string($title_part)}
                {$nb_site_target.translation.head_title|sprintf:$title_part}
            {elseif is_array($title_part)}
                {$nb_site_target.translation.head_title|vsprintf:$title_part}
            {/if}
        {else}
            {$nb_site_target.translation.head_title}
        {/if}
    {/strip}</title>
    <link href="/images/logo-transparent-256x256.png" rel="icon" type="image/png" sizes="256x256">
    <!--link href="/images/logo-256x256.png" rel="icon" type="image/png" sizes="256x256"-->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">
    <link href="/css/nabu3-theme.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="/assets/css/font-awesome.css" rel="stylesheet">
</head>
