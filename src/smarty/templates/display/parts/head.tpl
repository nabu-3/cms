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
    {*<link href="/images/logo-transparent-256x256.png" rel="icon" type="image/png" sizes="256x256">*}
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/images/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/images/favicons/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="/images/favicons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/images/favicons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/images/favicons/favicon-16x16.png" sizes="16x16">

    <!--link href="/images/logo-256x256.png" rel="icon" type="image/png" sizes="256x256"-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link href="/css/nabu3-theme.css" rel="stylesheet" defer>
    {if strlen($nb_site_target.css_file)>0}<link href="/css/{$nb_site_target.css_file}" rel="stylesheet" defer>{/if}
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans|Josefin+Sans:400,400i,700|Roboto+Condensed:300,400" rel="stylesheet" defer>
    <link href="https://fonts.googleapis.com/css?family=Baloo|Lato&subset=latin-ext" rel="stylesheet" defer>
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet" defer>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
