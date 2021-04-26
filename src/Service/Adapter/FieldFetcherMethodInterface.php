<?php

namespace Drupal\entities_service_generator\Service\Adapter;

use Drupal\Core\Field\FieldConfigInterface;
use Nette\PhpGenerator\ClassType;

interface FieldFetcherMethodInterface {

  public function doesHandle(FieldConfigInterface $fieldConfig, string $entityType, string $entityClass): bool;

  public function generateMethods(ClassType &$class, FieldConfigInterface $fieldConfig, string $entityType, string $entityClass);
}
