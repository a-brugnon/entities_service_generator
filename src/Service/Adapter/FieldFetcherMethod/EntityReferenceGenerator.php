<?php

namespace Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethod;

use Drupal\Core\Field\FieldConfigInterface;
use Nette\PhpGenerator\ClassType;

class EntityReferenceGenerator extends Generator {

  public function doesHandle(FieldConfigInterface $fieldConfig, string $entityType, string $entityClass): bool {
    return $fieldConfig->getType() == 'entity_reference';
  }

  public function generateMethods(ClassType &$class, FieldConfigInterface $fieldConfig, string $entityType, string $entityClass){
    $this->generateFetchIdsMethod($class, $fieldConfig, $entityType, $entityClass);
    $this->generateFetchEntitiesMethod($class, $fieldConfig, $entityType, $entityClass);
  }

  protected function generateFetchIdsMethod(ClassType &$class, FieldConfigInterface $fieldConfig, string $entityType, string $entityClass) {
    $fieldName = $fieldConfig->getName();
    $method = $class->addMethod($this->prepareFunctionTitle($fieldName).'IDs');
    $method->addParameter($entityType)->setType($entityClass);

    $body = $this->prepareBody($entityType, $fieldName);
    $method->addBody($body);
    $method->addBody('$iterator = $'.$entityType.'->get(\''.$fieldName.'\')->getIterator();');
    $method->addBody('$output = [];');
    $method->addBody('foreach($iterator as $item){');
    $method->addBody("\t".'$output[] = $item->get(\'target_id\')->getValue();');
    $method->addBody('}');
    $method->addBody('return $output;');
  }

  protected function generateFetchEntitiesMethod(ClassType $class, FieldConfigInterface $fieldConfig, string $entityType, string $entityClass) {
    $fieldName = $fieldConfig->getName();
    $method = $class->addMethod($this->prepareFunctionTitle($fieldName).'Entities');
    $method->addParameter($entityType)->setType($entityClass);

    $body = $this->prepareBody($entityType, $fieldName);
    $method->addBody($body);
    $method->addBody('$iterator = $'.$entityType.'->get(\''.$fieldName.'\')->getIterator();');
    $method->addBody('$output = [];');
    $method->addBody('foreach($iterator as $item){');
    $method->addBody("\t".'$output[] = $item->entity;');
    $method->addBody('}');
    $method->addBody('return $output;');
  }

}
