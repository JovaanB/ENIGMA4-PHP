<?php
declare(strict_types = 1);

namespace src\Logger;

use DateTime;
use LogicException;

trait LoggerAware
{
    public string $logDir = __DIR__.'/../../var/logs';

    public function log(string $message, string $level = 'info', array $context = []) : void
    {
        $path = $this->logDir.'/'.$level.'.log';

        if(!$handle = fopen($path,'a+')){
            throw new LogicException("Impossible to open Log file in '$path");
        }

        $message = (new DateTime())->format('Y-m-d H:i:s') . ' - ' . $message;

        if(!empty($context)){
            $message .= PHP_EOL.var_export($context, true);
        }

        if(false === fwrite($handle, $message.PHP_EOL)){
            throw new LogicException("Impossible to write in Log file '$path'");
        }

        fclose(($handle));
    }
}