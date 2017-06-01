<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#前台父控制器
class Home_Controller extends CI_Controller{
	public function __construct(){
		parent::__construct();
		#权限验证
		if(! isset($_SESSION['user'])){
			redirect('user');
		}
	}
	
}

class Base_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    protected function returnResult($result) {
        header("Content-type: application/json");
        echo json_encode($result);
        die();
    }

}
