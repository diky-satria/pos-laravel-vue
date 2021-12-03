<?php

function format_rupiah($value)
{
    return 'Rp. '.number_format($value, '0','.','.');
}

?>