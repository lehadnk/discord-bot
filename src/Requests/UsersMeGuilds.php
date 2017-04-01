<?php
/**
 * UsersMeGuilds.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */

namespace Requests;

use DataStructures\Guild;

class UsersMeGuilds extends RequestAbstract
{
    public function request() {
        $this->get('/users/@me/guilds');
        return $this;
    }

    protected function onComplete($data) {
        $guilds = [];
        foreach ($data as $d) {
            if (!is_object($d)) {
                $this->onError;
                return;
            }

            $guilds[] = new Guild($d);
        }

        $this->callbackComplete($guilds);
    }
}