<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 12/07/2019
 * Time: 18:48
 */

namespace Voltage\Core\fake;


class Player
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}