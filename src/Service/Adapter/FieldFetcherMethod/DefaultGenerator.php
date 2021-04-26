<?php

namespace Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethod;

use Drupal\Core\Field\FieldConfigInterface;
use Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethodInterface;
use Drupal\Tests\migrate_drupal\Unit\source\d6\i18nVariableTest;
use Nette\PhpGenerator\ClassType;

class DefaultGenerator extends Generator {

  public function doesHandle(FieldConfigInterface $fieldConfig, string $entityType, string $entityClass): bool {
    return TRUE;
  }

  public function generateMethods(ClassType &$class, FieldConfigInterface $fieldConfig, string $entityType, string $entityClass){
    $fieldName = $fieldConfig->getName();
    $method = $class->addMethod($this->prepareFunctionTitle($fieldName).'Values');
    $method->addParameter($entityType)->setType($entityClass);

    $method->addBody($this->prepareBody($entityType, $fieldName));
    $method->addBody("return $".$entityType."->get('".$fieldName."')->getValue();");
  }

}
