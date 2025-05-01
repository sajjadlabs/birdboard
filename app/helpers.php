<?php

function gravatar_url($email): string
{
    return "https://gravatar.com/avatar/" . md5($email) . http_build_query([
            's' => '60'
        ]);
}
