<?php

namespace Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethod;

use Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethodInterface;
use Drupal\field\Entity\FieldConfig;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

abstract class Generator implements FieldFetcherMethodInterface{

  protected function prepareFunctionTitle(string $fieldName): string {
    $explodeFieldName = explode('_', $fieldName);
    $readableFieldName = '';
    foreach ($explodeFieldName as $key => $item){
      if($key == 0 && $item === 'field')
        continue;
      $readableFieldName .= ucfirst($item);
    }

    return 'fetch'.$readableFieldName;
  }

  protected function prepareBody(string $entityType, string $fieldName):string{
    $body = "if(!$".$entityType."->hasField('".$fieldName."') || $".$entityType."->get('".$fieldName."')->isEmpty())";
    $body .="\n\treturn NULL;";

    return $body;
  }

  protected function prepareMethod(FieldConfig $fieldConfig, ClassType $class, string $methodName, string $entityType, string $entityClass): Method{
    $method = $class->addMethod($methodName);
    $method->addParameter($entityType)->setType($entityClass);
    $method->addParameter('offset')->setType('int')->setDefaultValue(NULL);

    $body = $this->prepareBody($entityType, $fieldConfig->getName());
    $method->addBody($body);
    return $method;
  }
}
