<?php

namespace Drupal\products_main\Service;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Class ProductsManager.
 */
class ProductsManager {

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
   * @return NodeInterface[]
   */
  public function fetchProductsList(): array{
    $nodes = [];
    try {
      $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties(['type' => 'product', 'status' => 1 ]);
    } catch (\Exception $exception) {
      $this->messenger->addError(t($exception->getMessage()));
    }
    return $nodes;
  }

}
