<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Mobile controller.
 *
 * @package    PNP4Nagios
 * @author     Joerg Linge
 * @license    GPL
 */
class Mobile_Controller extends System_Controller  {

    public function __construct()
    {
        parent::__construct();
        $this->template   = $this->add_view('mobile');
    }

    public function index()
    {
        // Service Details
        if($this->host != "" && $this->service != ""){
            //
        // Host Overview
        }elseif($this->host != ""){
            $this->is_authorized = $this->auth->is_authorized($this->host); 
        }else{
        
		}
    }
}
