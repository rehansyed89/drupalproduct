<?php

namespace Drupal\products_main\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\products_main\Service\ProductsManager;

/**
 * Class ProductsController.
 */
class ProductsController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The productsHelperService.
   *
   * @var ProductsManager
   */
  private $productsHelperService;

  /**
   * The pager manager.
   *
   * @var PagerManagerInterface
   */
  protected $pagerManager;

  /**
   * Constructs a ProductsController object.
   *
   * @param ProductsManager $productsHelperService
   *   A productsHelperService object
   */

  public function __construct(EntityTypeManagerInterface $entityTypeManager,ProductsManager $productsHelperService, PagerManagerInterface $pagerManager){
    $this->entityTypeManager = $entityTypeManager;
    $this->productsHelperService = $productsHelperService;
    $this->pagerManager = $pagerManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): ProductsController
  {
    return new static(
      $container->get("entity_type.manager"),
      $container->get("products.helper"),
      $container->get('pager.manager')
    );
  }
  /**
   * List of products.
   *
   * @return array
   *   Return List of products.
   */
  public function list(): array{
    $products_lists = [];
    $itemsPerPage = 15;
    $nodes = $this->productsHelperService->fetchProductsList();
    foreach ($nodes as $node) {
      $products_lists[] = $this->entityTypeManager->getViewBuilder('node')->view($node, 'product_view_mode');
    }
    $totalProducts = count($products_lists);
    $currentPage = $this->pagerManager->createPager($totalProducts, $itemsPerPage)->getCurrentPage();
    $chunks = array_chunk($products_lists, $itemsPerPage);

    $build['content'] = $chunks[$currentPage];
    $build['pager'] = [
      '#type' => 'pager',
    ];
    return $build;
  }
}


