<?php

namespace Drupal\entities_service_generator\Commands;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drush\Commands\DrushCommands;
use Nette\PhpGenerator\ClassType;
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

  /**
   * @var EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  public function __construct(EntityFieldManagerInterface $entityFieldManager) {
    $this->entityFieldManager = $entityFieldManager;
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

    $file = new PhpFile();
    $class = $file->addClass(ucfirst($bundle).ucfirst($entityType).'Fetcher');
    foreach ($fieldDefinitions as $fieldName => $fieldDefinition){
      if(substr($fieldName, 0, 6) == 'field_'){
        $method = $class->addMethod('fetch_'.$fieldName);
        $method->addParameter($entityType);
        $method->setBody(
        "if($".$entityType."->hasField('".$fieldName."') && !$".$entityType."->isEmpty('".$fieldName."'))".
        "\n\treturn $".$entityType."->get('".$fieldName."')->getValue();".
        "\nelse".
        "\n\treturn NULL;"
        );
      }
    }

    $basePath = $this->prepareServiceTree($module, $entityType);

    $handle = fopen($basePath.'/'.$class->getName().'.php', 'w');
    $printer = new Printer();
    fwrite($handle, $printer->printFile($file));
    fclose($handle);

  }

  protected function prepareServiceTree($module, $entityType){
    $basePath = $this->fetchModuleBasePath($module);
    $this->createFolder($basePath);
    $this->createFolder($basePath.'/src');
    $this->createFolder($basePath.'/src/Service');
    $this->createFolder($basePath.'/src/Service/EntityManagerServices');
    return $this->createFolder($basePath.'/src/Service/EntityManagerServices/'.ucfirst($entityType));
  }

  protected function createFolder($path){
    if(!file_exists($path))
      mkdir($path);
    return $path;
  }

  protected function fetchModuleBasePath($module){
    return 'modules/custom/'.$module;
  }


}
