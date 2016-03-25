<?php
namespace Info\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        phpinfo();
    }


}