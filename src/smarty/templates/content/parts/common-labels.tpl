{if is_array($data)}
    {if array_key_exists('id', $data) && is_numeric($data.id)}<span class="label label-info">ID #{$data.id}</span>{/if}
    {if array_key_exists('hash', $data) && nb_isValidGUID($data.hash)}<span class="label label-info">GUID {$data.hash}</span>{/if}
    {if array_key_exists('key', $data) && nb_isValidKey($data.key)}<span class="label label-info">KEY {$data.key}</span>{/if}
{/if}
