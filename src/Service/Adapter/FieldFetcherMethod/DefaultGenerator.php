<?php

namespace Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethod;

use Drupal\Core\Field\FieldConfigInterface;
use Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethodInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\Tests\migrate_drupal\Unit\source\d6\i18nVariableTest;
use Nette\PhpGenerator\ClassType;

class DefaultGenerator extends Generator {

  public function doesHandle(FieldConfigInterface $fieldConfig, string $entityType, string $entityClass): bool {
    return TRUE;
  }

  public function generateMethods(ClassType &$class, FieldConfig $fieldConfig, string $entityType, string $entityClass){
    $fieldName = $fieldConfig->getName();
    $methodName = $this->prepareFunctionTitle($fieldName).'Values';
    $method = $this->prepareMethod($fieldConfig, $class, $methodName , $entityType, $entityClass);

    $method->addBody('if(!empty($offset)) {');
    $method->addBody("\t".'$item = $node->get(\''.$fieldName.'\')->get($offset);');
    $method->addBody("\t".'return !empty($value) ? $item->getValue() : NULL;');
    $method->addBody('}');

    $method->addBody("return $".$entityType."->get('".$fieldName."')->getValue();");
  }

}
