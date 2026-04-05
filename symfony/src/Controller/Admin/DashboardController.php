<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private RequestStack $requestStack,
        private ChartBuilderInterface $chartBuilder,
    ) {
    }

    public function index(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        // 1. Récupérer la date de l'URL (format YYYY-MM) ou le mois actuel par défaut
        $dateQuery = $request->query->get('date', (new \DateTimeImmutable())->format('Y-m'));

        // 2. Créer l'objet DateTime de manière sécurisée (on ajoute "-01" pour le jour)
        try {
            $currentDate = new \DateTimeImmutable($dateQuery.'-01');
        } catch (\Exception $e) {
            $currentDate = new \DateTimeImmutable();
        }

        // 3. Récupérer les données via les repositories
        $chartData = $this->orderRepository->getMonthlySalesData($currentDate);
        $bestFiveClientsForMonth = $this->orderRepository->getFiveBestClients($currentDate);

        // 4. Préparation du Graphique
        $sum = array_sum($chartData);
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => range(1, 31),
            'datasets' => [
                [
                    'label' => 'Ventes du mois (€)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => array_values($chartData),
                    'tension' => 0.4,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => !empty($chartData) ? max($chartData) + 1.2 : 10,
                ],
            ],
        ]);

        // 5. Rendu du template
        return $this->render('admin/dashboard.html.twig', [
            'chart' => $chart,
            'totalSales' => $sum,
            'bestClients' => $bestFiveClientsForMonth,
            // On renvoie la string formatée pour l'input HTML type="month"
            'currentDateValue' => $currentDate->format('Y-m'),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bonjour Alice');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
    }
}
