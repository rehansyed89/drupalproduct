<?php

namespace Drupal\products_main\Controller;

use Drupal\Core\Controller\ControllerBase;
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
   * The productsHelperService.
   *
   * @var ProductsManager
   */
  private $productsHelperService;

  /**
   * The pager manager.
   *
   * @var \Drupal\Core\Pager\PagerManagerInterface
   */
  protected $pagerManager;

  /**
   * Constructs a ProductsController object.
   *
   * @param ProductsManager $productsHelperService
   *   A productsHelperService object
   */

  public function __construct(ProductsManager $productsHelperService, PagerManagerInterface $pagerManager){
    $this->productsHelperService = $productsHelperService;
    $this->pagerManager = $pagerManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): ProductsController
  {
    return new static(
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
    $products_lists = $this->productsHelperService->fetchProductsList();

    //pagination
    $itemsPerPage = 15;
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


