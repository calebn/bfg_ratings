<?php
/**
 * StaticHTMLWriter is used to write static HTML pages for this site
 * @author  Caleb Nelson <calebnelson@mac.com>
 */
abstract class v_StaticHTMLWriter extends BaseLibClass{
    /**
     * Uses the provided parameters to get the static HTML for this site's header
     * @param  array $head_params Optional associative array with any configuration parameters necessary to write the header
     * @return string Either the HTML for the header or an error/warning message
    */
    public function writeHeader($head_params=null){
        $response = $this->getHeader($head_params);
        if($response['status']=='success'){
            return $response['data'];
        }else{
            return $response['status'].": ".$response['message'];
        }
    }
    
    /**
     * Generates the static HTML necessary to write the header
     * @param  array $head_params Optional associative array with any configuration parameters necessary to write the header
     * @return array A standard form response associative array $response = array('status' => {'warning'|'failure'|'success'}, 'message' => {message}, 'data'=> {data})
     */
    abstract protected function getHeader($head_params=null);

    /**
     * Uses the provided parameters to get the static HTML for this site's body
     * @param  array $body_params Optional associative array with any configuration parameters necessary to write the body
     * @return string Either the HTML for the body or an error/warning message
    */
    public function writeBody($body_params){
        $response = $this->getBody($body_params);
        if($response['status']=='success'){
            return $response['data'];
        }else{
            return $response['status'].": ".$response['message'];
        }
    }

    /**
     * Generates the static HTML necessary to write the body
     * @param  array $body_params Optional associative array with any configuration parameters necessary to write the body
     * @return array A standard form response associative array $response = array('status' => {'warning'|'failure'|'success'}, 'message' => {message}, 'data'=> {data})
     */
    abstract protected function getBody($body_params=null);

    /**
     * Uses the provided parameters to get the static HTML for this site's footer
     * @param  array $foot_params Optional associative array with any configuration parameters necessary to write the footer
     * @return string Either the HTML for the footer or an error/warning message
    */
    public function writeFooter($foot_params=null){
        $response = $this->getfooter($foot_params);
        if($response['status']=='success'){
            return $response['data'];
        }else{
            return $response['status'].": ".$response['message'];
        }
    }
    
    /**
     * Generates the static HTML necessary to write the footer
     * @param  array $foot_params Optional associative array with any configuration parameters necessary to write the footer
     * @return array A standard form response associative array $response = array('status' => {'warning'|'failure'|'success'}, 'message' => {message}, 'data'=> {data})
     */
    abstract protected function getfooter($foot_params=null);
}
?>