<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController
{
    /**
   * @var EventController
   * @access private
   * @static
   */
   private static $_instance = null;
 
   /**
    * @param void
    * @return void
    */
   private function __construct() {  
   }
 
   /**
    * @param void
    * @return EventController
    */
    
   public static function getInstance() {
 
     if(is_null(self::$_instance)) {
       self::$_instance = new EventController();  
     }
 
     return self::$_instance;
   }

   public function LoadEvents(){
       return "toto";
   }
}
