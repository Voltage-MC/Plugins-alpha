<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 28/04/2019
 * Time: 15:07
 */

namespace Voltage\Core\task\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use Voltage\Core\utils\MySQL;

class DataBaseAsyncTask extends AsyncTask
{
    /**
     * @var string
     */
    private $text;

    /**
     * DataBaseAsyncTask constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function onRun()
    {

    }

    /**
     * @param Server $server
     * @return \Exception|void
     */
    public function onCompletion(Server $server)
    {
        $my = MySQL::getData();
        $my->query($this->text);
        if ($my->error) new \Exception($my->error);
    }

}