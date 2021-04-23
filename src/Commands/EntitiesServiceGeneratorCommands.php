<?php

namespace Drupal\entities_service_generator\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;

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
   * Generate entities service
   *
   * @param $entity_type
   *   Entity Type.
   * @param $bundle
   *   Bundle.
   * @usage ges node article
   *   Generate service to fetch all data of an article node
   *
   * @command generate-entities-service
   * @aliases ges
   */
  public function generateService(string $entity_type, string $bundle = '') {

  }


}
