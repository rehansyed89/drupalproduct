<?php

namespace Drupal\products_main\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\products_main\Service\ProductsHelperService;

/**
 * Class ProductsController.
 */
class ProductsController extends ControllerBase {

  /**
   * The productsHelperService.
   *
   * @var ProductsHelperService
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
   * @param ProductsHelperService $productsHelperService
   *   A productsHelperService object
   */

  public function __construct(ProductsHelperService $productsHelperService, PagerManagerInterface $pagerManager){
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
    $build['content'] = $this->pagination($products_lists, 15);
    $build['pager'] = [
      '#type' => 'pager',
    ];
    return $build;
  }

  /*
   * The pagination.
   *
   * @param $items
   *    List of the products.
   *
   * @param $itemsPerPage
   *    Total items per page.
   */
  public function pagination($items, $itemsPerPage) {
    $total = count($items);
    $currentPage = $this->pagerManager->createPager($total, $itemsPerPage)->getCurrentPage();
    $chunks = array_chunk($items, $itemsPerPage);
    return $chunks[$currentPage];
  }

}


