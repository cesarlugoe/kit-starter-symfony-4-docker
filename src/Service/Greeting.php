<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Greeting {

   private $logger;

   public function __construct(LoggerInterface $logger) {
      $this->logger = $logger;
   }
   
   public function greet(string $name): string {
      $message = "Greeted $name";
      $this->logger->info($message);
      return "Hello $name";
   }

}