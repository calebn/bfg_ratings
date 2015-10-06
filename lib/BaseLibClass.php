<?php
/**
 * BaseLibClass includes all functions common to all library classes
 * @author  Caleb Nelson <calebnelson@mac.com>
 */
class BaseLibClass{

    public function __construct(){
        /**
        * throw exceptions based on E_* error types
        * @link http://php.net/manual/en/function.set-error-handler.php#112881
        */
        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context)
        {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) { return false;}
            switch($err_severity)
            {
                case E_ERROR:               throw new ErrorException            ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_WARNING:             throw new WarningException          ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_PARSE:               throw new ParseException            ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_NOTICE:              throw new NoticeException           ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_CORE_ERROR:          throw new CoreErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_CORE_WARNING:        throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_COMPILE_ERROR:       throw new CompileErrorException     ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_COMPILE_WARNING:     throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_ERROR:          throw new UserErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_WARNING:        throw new UserWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_NOTICE:         throw new UserNoticeException       ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_STRICT:              throw new StrictException           ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_RECOVERABLE_ERROR:   throw new RecoverableErrorException ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_DEPRECATED:          throw new DeprecatedException       ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_DEPRECATED:     throw new UserDeprecatedException   ($err_msg, 0, $err_severity, $err_file, $err_line);
            }
        });
    }

    public function __destruct(){
        restore_error_handler();
    }
    /**
     * Generates a standard success message array
     * @param  string $message Optional Message about the return value
     * @param  mixed $data Optional Any data to return
     * @return array
     */
	protected function successMessage($data=null,$message=null){
		return array('status' => 'success', 'message' => $message, 'data' => $data);
	}
    /**
     * Generates a standard warning message array
     * @param  string $message Optional Message about the return value
     * @param  mixed $data Optional Any data to return
     * @return array
     */
	protected function warningMessage($data=null,$message=null){
		return array('status' => 'warning', 'message' => $message, 'data' => $data);
	}
    /**
     * Generates a standard failure message array
     * @param  string $message Optional Message about the return value
     * @param  mixed $data Optional Any data to return
     * @return array
     */
	protected function failureMessage($data=null,$message=null){
		return array('status' => 'failure', 'message' => $message, 'data' => $data);
	}

    protected function printException($e){
        return $e->getMessage()." in ".$e->getFile()." on line ".$e->getLine();
    }
}
?>