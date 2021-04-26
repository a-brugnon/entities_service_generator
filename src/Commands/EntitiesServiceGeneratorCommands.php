<?php

namespace Drupal\entities_service_generator\Commands;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\entities_service_generator\Service\FieldFetchersGenerateMethodsManager;
use Drupal\field\Entity\FieldConfig;
use Drush\Commands\DrushCommands;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class EntitiesServiceGeneratorCommands extends DrushCommands {

  protected $folder = 'EntityManagerServices';

  /**
   * @var EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var FieldFetchersGenerateMethodsManager
   */
  protected $fieldFetchersGenerateMethodsManager;

  public function __construct(EntityFieldManagerInterface $entityFieldManager,
                              EntityTypeManagerInterface $entityTypeManager,
                              FieldFetchersGenerateMethodsManager $fieldFetchersGenerateMethodsManager) {
    $this->entityFieldManager = $entityFieldManager;
    $this->entityTypeManager = $entityTypeManager;
    $this->fieldFetchersGenerateMethodsManager = $fieldFetchersGenerateMethodsManager;
  }


  /**
   * Generate entities service
   *
   * @param $module
   *   Module Destination
   * @param $entityType
   *   Entity Type.
   * @param $entityType
   *   Entity Type.
   * @param $bundle
   *   Bundle.
   * @usage ges entities_service node article
   *   Generate service to fetch all data of an article nodev in entities_service module
   *
   * @command generate-entities-service
   * @aliases ges
   */
  public function generateService(string $module, string $entityType, string $bundle = '') {
    $fieldDefinitions = $this->entityFieldManager->getFieldDefinitions($entityType, $bundle);
    $entityClass = $this->entityTypeManager->getDefinition($entityType)->getClass();

    $file = new PhpFile();
    $namespace = $file->addNamespace('Drupal\\'.$module.'\Service\\'.$this->folder.'\\'.ucfirst($entityType));
    $class = $namespace->addClass(ucfirst($bundle).ucfirst($entityType).'Fetcher');
    foreach ($fieldDefinitions as $fieldDefinition){
      if($fieldDefinition instanceof FieldConfig){
        $this->fieldFetchersGenerateMethodsManager->generateMethods($class, $fieldDefinition, $entityType, $entityClass);
      }
    }

    $basePath = $this->prepareServiceTree($module, $entityType);

    $handle = fopen($basePath.'/'.$class->getName().'.php', 'w');
    $printer = new Printer();
    fwrite($handle, $printer->printFile($file));
    fclose($handle);

  }

  protected function prepareServiceTree($module, $entityType): string{
    $basePath = $this->fetchModuleBasePath($module);
    $this->createFolder($basePath);
    $this->createFolder($basePath.'/src');
    $this->createFolder($basePath.'/src/Service');
    $this->createFolder($basePath.'/src/Service/EntityManagerServices');
    return $this->createFolder($basePath.'/src/Service/'.$this->folder.'/'.ucfirst($entityType));
  }

  protected function createFolder(string $path): string{
    if(!file_exists($path))
      mkdir($path);
    return $path;
  }

  protected function fetchModuleBasePath(string $module): string{
    return 'modules/custom/'.$module;
  }

}
