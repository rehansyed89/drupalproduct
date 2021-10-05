<?php

namespace Drupal\products_main\Service;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\node\Entity\Node;

/**
 * Class ProductsHelperService.
 */
class ProductsHelperService {

  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The messenger.
   *
   * @var MessengerInterface
   */
  protected $messenger;

  /**
   * Creates ProductsHelperService objects.
   *
   * @param EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, MessengerInterface $messenger){
    $this->entityTypeManager = $entityTypeManager;
    $this->messenger = $messenger;
  }

  /**
   * fetches the product List
   *
   * @return array
   */
  public function fetchProductsList(): array{
    $page = [];
    try {
      $query = $this->entityTypeManager->getStorage('node')->getQuery();
      $node_ids = $query->condition('type', 'product')->execute();
      $nodes = Node::loadMultiple($node_ids);

      foreach ($nodes as $node) {
        $page[] = $this->entityTypeManager->getViewBuilder('node')->view($node, 'products_view_mode');
      }
    } catch (\Exception $exception) {
      $this->messenger->addError(t($exception->getMessage()));
    }

    return $page;
  }

}
