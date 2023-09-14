<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use PDO;
use Philipretl\TechnicalTestSourcetoad\Config;
use Philipretl\TechnicalTestSourcetoad\ConsoleTableNormalizer;
use Philipretl\TechnicalTestSourcetoad\Database\SQliteConnection;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteCartRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteCustomerRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteItemRepository;
use Philipretl\TechnicalTestSourcetoad\Resources\UserDataSource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class EcommerceCommand extends Command
{

    protected function configure()
    {
        $this->setName('challenge:third')
            ->setDescription('This start the ecommerce challenge');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $database = SQliteConnection::connect(Config::PATH_TO_SQLITE_FILE);

        if ($database->getPdo() === null) {
            $output->writeln('<error>Whoops, could not connect to the SQLite database! </error>');

        }

        $menu_option = 'run';

        while ($menu_option !== 'exit') {
            $output->writeln('');
            $output->writeln('<info>Welcome to the fantastic SourceToad Ecommerce.</info>');

            $menu_option = select(
                label: 'What do you want to do?',
                options: [
                    'list_users' => 'List Users.',
                    'show_cart_by_customer' => 'Show cart by customer.',
                    'exit' => 'Exit.'
                ],
            );

            if ($menu_option === 'exit') {
                return Command::SUCCESS;
            }

            $this->goToAction($menu_option, $database->getPdo(), $output);

        }

        return Command::SUCCESS;
    }

    public function goToAction(string $menu_option, PDO $pdo, OutputInterface $output)
    {
        switch ($menu_option) {
            case 'list_users':
                $this->listUsers($output, $pdo);
                break;
            case 'show_cart_by_customer':
                $this->showCartByCustomer($output, $pdo);
                break;
            default:
                $output->writeln('<error>The option is not valid or not implemented yet.</error>');
        }
    }

    public function renderTable(string $title, OutputInterface $output, array $keys, array $items)
    {
        $table = new Table($output);
        $table->setHeaders($keys);

        foreach ($items as $item) {
            $table->addRow($item->toArray());
        }
        $table->setHeaderTitle($title);

        $output->writeln('');
        $table->render();
    }

    public function listUsers(OutputInterface $output, PDO $pdo)
    {
        $customers = $this->getAllUsers($pdo);

        $properties = get_object_vars($customers[0]);
        $keys = array_keys($properties);

        $this->renderTable('Users', $output, $keys, $customers);
    }

    public function getAllUsers(PDO $pdo): array
    {

        $customer_repository = new SQliteCustomerRepository($pdo);
        $customers = $customer_repository->getAllCustomers();

        return $customers;
    }

    public function showCartByCustomer(OutputInterface $output, PDO $pdo)
    {

        $customers = $this->getAllUsers($pdo);
        $mapped_customers = [];

        foreach ($customers as $customer) {
            $mapped_customers[$customer->id] = 'id: ' . $customer->id . ' - ' . $customer->fullName();
        }

        $customer_id = select(
            label: 'What user do you want to get the cart',
            options: $mapped_customers,
        );

        try {
            $cart_repository = new SQliteCartRepository($pdo);
            $cart = $cart_repository->getCartByCustomer($customer_id);

            $this->renderTable('Carts', $output, array_keys($cart->toArray()), array($cart));

            $items_repository = new SQliteItemRepository($pdo);
            $items = $items_repository->getAllItemsByCart($cart->id);

            $this->renderTable('Items from cart_id: ' . $cart->id , $output, array_keys($items[0]->toArray()), $items);

        } catch (\Exception $exception) {
            $output->writeln('<error> ' . $exception->getMessage() . '</error>');
        }

    }
}