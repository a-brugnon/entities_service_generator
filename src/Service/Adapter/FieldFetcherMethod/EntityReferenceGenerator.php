<?php

namespace Drupal\entities_service_generator\Service\Adapter\FieldFetcherMethod;

use Drupal\Core\Field\FieldConfigInterface;
use Drupal\field\Entity\FieldConfig;
use Nette\PhpGenerator\ClassType;

class EntityReferenceGenerator extends Generator {

  public function doesHandle(FieldConfigInterface $fieldConfig, string $entityType, string $entityClass): bool {
    return $fieldConfig->getType() == 'entity_reference';
  }

  public function generateMethods(ClassType &$class, FieldConfig $fieldConfig, string $entityType, string $entityClass){
    $this->generateFetchIdsMethod($class, $fieldConfig, $entityType, $entityClass);
    $this->generateFetchEntitiesMethod($class, $fieldConfig, $entityType, $entityClass);
  }

  protected function generateFetchIdsMethod(ClassType &$class, FieldConfig $fieldConfig, string $entityType, string $entityClass) {
    $fieldName = $fieldConfig->getName();
    $methodName = $this->prepareFunctionTitle($fieldName).'Ids';
    $method = $this->prepareMethod($fieldConfig, $class, $methodName , $entityType, $entityClass);

    $method->addBody('if(!empty($offset)) {');
    $method->addBody("\t".'$item = $node->get(\''.$fieldName.'\')->get($offset);');
    $method->addBody("\t".'return !empty($value) ? $item->get(\'target_id\')->getValue() : NULL;');
    $method->addBody('}');

    $method->addBody('$iterator = $'.$entityType.'->get(\''.$fieldName.'\')->getIterator();');
    $method->addBody('$output = [];');
    $method->addBody('foreach($iterator as $item){');
    $method->addBody("\t".'$output[] = $item->get(\'target_id\')->getValue();');
    $method->addBody('}');
    $method->addBody('return $output;');
  }

  protected function generateFetchEntitiesMethod(ClassType $class, FieldConfig $fieldConfig, string $entityType, string $entityClass) {
    $fieldName = $fieldConfig->getName();
    $methodName = $this->prepareFunctionTitle($fieldName).'Entities';
    $method = $this->prepareMethod($fieldConfig, $class, $methodName , $entityType, $entityClass);

    //@todo : Find a better way to integrate php code
    $method->addBody('if(!empty($offset)) {');
    $method->addBody("\t".'$item = $node->get(\''.$fieldName.'\')->get($offset);');
    $method->addBody("\t".'return !empty($value) ? $item->entity : NULL;');
    $method->addBody('}');

    $method->addBody('$iterator = $'.$entityType.'->get(\''.$fieldName.'\')->getIterator();');
    $method->addBody('$output = [];');
    $method->addBody('foreach($iterator as $item){');
    $method->addBody("\t".'$output[] = $item->entity;');
    $method->addBody('}');
    $method->addBody('return $output;');
  }

}
