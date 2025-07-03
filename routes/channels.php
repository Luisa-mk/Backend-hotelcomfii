<?php
Broadcast::channel('comandas', function ($user) {
    return true;
});
