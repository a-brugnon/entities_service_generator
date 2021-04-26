<?php

namespace Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethod;

use Drupal\Core\Field\FieldConfigInterface;
use Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethodInterface;
use Drupal\Tests\migrate_drupal\Unit\source\d6\i18nVariableTest;
use Nette\PhpGenerator\ClassType;

class DefaultGenerator implements FieldFetcherMethodInterface {

  public function doesHandle(FieldConfigInterface $fieldConfig, string $entityType, string $entityClass): bool {
    return TRUE;
  }

  public function generateMethods(ClassType &$class, FieldConfigInterface $fieldConfig, string $entityType, string $entityClass){
    $fieldName = $fieldConfig->getName();
    $method = $class->addMethod('fetch'.$this->prepareFieldName($fieldName));
    $method->addParameter($entityType)->setType($entityClass);

    $body = "if($".$entityType."->hasField('".$fieldName."') && !$".$entityType."->get('".$fieldName."')->isEmpty())";
    $body .= "\n\treturn $".$entityType."->get('".$fieldName."')->getValue();";
    $body .= "\nelse";
    $body .="\n\treturn NULL;";

    $method->setBody($body);
  }

  protected function prepareFieldName(string $fieldName): string {
    $explodeFieldName = explode('_', $fieldName);
    $readableFieldName = '';
    foreach ($explodeFieldName as $key => $item){
      if($key == 0 && $item === 'field')
        continue;
      $readableFieldName .= ucfirst($item);
    }

    return $readableFieldName;
  }
}
