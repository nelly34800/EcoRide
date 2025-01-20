<?php
function getElectric_car($good)
{
    if ($good) {
        echo '<img src="/assets/img/good.png" alt="oui">';
    } else {
        echo '<img src="/assets/img/bad.png" alt="oui">';
    }
}



function getAvatar(string|null $avatar)
{
    if ($avatar === null) {
        return _ASSETS_IMG_PATH_ . 'avatar.png';
    } else {
        return _AVATAR_IMG_PATH_ . $avatar;
    }
}
