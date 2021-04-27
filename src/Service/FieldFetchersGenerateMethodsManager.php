<?php

namespace Drupal\entities_service_generator\Service;

use Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethodInterface;
use Drupal\field\Entity\FieldConfig;
use Nette\PhpGenerator\ClassType;

class FieldFetchersGenerateMethodsManager {

  /**
   * @var FieldFetcherMethodInterface[]
   */
  protected $fieldFetcherMethods;

  public function __construct(FieldFetcherMethodInterface ...$fieldFetcherMethods){
    foreach ($fieldFetcherMethods as $fieldFetcherMethod){
      $this->fieldFetcherMethods[] = $fieldFetcherMethod;
    }
  }

  public function generateMethods(ClassType &$class, FieldConfig $fieldConfig, string $entityType,
                                  string $entityClass){
    foreach($this->fieldFetcherMethods as $fieldFetcherMethod){
      if($fieldFetcherMethod->doesHandle($fieldConfig, $entityType, $entityClass)) {
        $fieldFetcherMethod->generateMethods($class, $fieldConfig, $entityType, $entityClass);
        break;
      }
    }
  }
}
