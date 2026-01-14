<?php


namespace App\Notifications\Traits;


trait Tag
{
    public function commonForSubject()
    {
        return [];
    }

    public function commonTagForSystem()
    {
        return [];
    }

    public function systemTemplateModifier($vars)
    {
        return $vars;
    }
}
