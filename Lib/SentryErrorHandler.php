<?php
/**
 * Description of SentryErrorHandler
 *
 * @author Sandreu
 */
class SentryErrorHandler extends ErrorHandler {

    protected static function sentryLog(Exception $exception) {
        if (Configure::read('debug')==0 || !Configure::read('Sentry.production_only')) {
            Raven_Autoloader::register();
            App::uses('CakeRavenClient', 'Sentry.Lib');

            $client = new CakeRavenClient(Configure::read('Sentry.PHP.server'));
            $client->captureException($exception, get_class($exception), 'PHP');
        }
    }

    public static function handleException(Exception $exception) {
        try {
            self::sentryLog($exception);


                self::sentryLog($exception);

                return parent::handleException($exception);
            } catch (Exception $e) {
                return parent::handleException($e);
            }
        }
    }

    public static function handleError($code, $description, $file = null, $line = null, $context = null) {
        try {
            list($error, $log) = self::mapErrorCode($code);
            if ($log === LOG_ERR || $log === LOG_WARNING) {
                $e = new ErrorException($description, 0, $code, $file, $line);
                self::sentryLog($e);
            }
            
            return parent::handleError($code, $description, $file, $line, $context);
        } catch (Exception $e) {
            self::handleException($e);
        }
    }
}