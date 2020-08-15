<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 15:40
 */

namespace Voltage\Core\inventory;

use pocketmine\inventory\ContainerInventory;
use pocketmine\math\Vector3;

class FakeInventory extends ContainerInventory
{
    protected $network_type;
    protected $title;
    protected $size;
    protected $holder;

    public function __construct(Vector3 $pos, int $network_type, int $size, string $title)
    {
        $this->network_type = $network_type;
        $this->title = $title;
        $this->size = $size;
        $this->holder = $pos;
        parent::__construct($pos, [], $size, $title);
    }

    /**
     * @return int
     */
    public function getNetworkType(): int
    {
        return $this->network_type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getDefaultSize(): int
    {
        return $this->size;
    }

    /**
     * @return Vector3
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * @param string $title
     */
    public function setName(string $title)
    {
        $this->title = $title;
    }

}