<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Exception;
use PDO;
use Philipretl\TechnicalTestSourcetoad\Config;
use Philipretl\TechnicalTestSourcetoad\Database\SQliteConnection;
use Philipretl\TechnicalTestSourcetoad\DTO\OrderPricesDTO;
use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;
use Philipretl\TechnicalTestSourcetoad\Models\CartModel;
use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteAddressRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteCartRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteCustomerRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteItemRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteOrderRepository;
use Philipretl\TechnicalTestSourcetoad\Services\ColombianCheckout;
use Philipretl\TechnicalTestSourcetoad\Services\ColombianShippingService;
use Philipretl\TechnicalTestSourcetoad\Services\Contracts\Checkout;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\select;

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
            $output->writeln('<info>Welcome to the SourceToad Ecommerce Dashboard.</info>');

            $menu_option = select(
                label: 'What do you want to do?',
                options: [
                    'list_users' => 'List users.',
                    'show_cart_by_customer' => 'Show cart by customer.',
                    'check_orders' => 'Check orders by customer',
                    'exit' => 'Exit.'
                ],
            );

            if ($menu_option === 'exit') {
                $output->writeln('<info>Thanks for be with us!!!</info>');
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
            case 'check_orders':
                $this->checOrders($output, $pdo);
                break;
            default:
                $output->writeln('<error>The option is not valid or not implemented yet.</error>');
        }
    }

    public function renderTable(OutputInterface $output, string $title, array $keys, array $items)
    {
        $table = new Table($output);
        $table->setHeaders($keys);

        foreach ($items as $item) {
            $table->addRow($item->toArray());
        }
        $table->setHeaderTitle($title);

        $output->writeln('');
        $table->render();
        $output->writeln('');
    }

    public function listUsers(OutputInterface $output, PDO $pdo): void
    {
        try {
            $customers = $this->getAllUsers($pdo);

            $properties = get_object_vars($customers[0]);
            $keys = array_keys($properties);

            $this->renderTable($output, 'Users', $keys, $customers);
        } catch (Exception $exception) {
            $output->writeln('<error>' . -$exception->getMessage() . '</error>');
        }
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

            $this->renderTable($output, 'Carts', array_keys($cart->toArray()), array($cart));

            $items_repository = new SQliteItemRepository($pdo);
            $items = $items_repository->getAllItemsByCart($cart->id);

            $cart->items = $items;

            $this->renderTable($output, 'Items from cart_id: ' . $cart->id, array_keys($items[0]->toArray()), $items);

            $checkout = select(
                label: 'Do you want to process to checkout ?',
                options: [
                    'true' => 'Yes',
                    'false' => 'No'
                ]
            );

            $checkout = $checkout === 'true' ? true : false;

            if ($checkout) {
                $this->checkoutOrder($output, $pdo, $customer_id, $cart);
            }

        } catch (\Exception $exception) {
            $output->writeln('<error> ' . $exception->getMessage() . '</error>');
        }

    }

    public function checkoutOrder(OutputInterface $output, PDO $pdo, int $customer_id, CartModel $cart)
    {
        try {

            $address_repository = new SQliteAddressRepository($pdo);
            $addresses = $address_repository->getAddressByCustomer($customer_id);

            foreach ($addresses as $address) {
                $mapped_addresses[$address->id] = 'id: ' . $address->id . ' - ' . $address->fullAddress();
            }

            $is_calculating = true;
            while ($is_calculating) {
                $mapped_addresses[0] = 'Cancel';

                $address_id = select(
                    label: 'What address do you want to calculate the value.',
                    options: $mapped_addresses,
                );

                if ($address_id === 0) {
                    $is_calculating = false;
                    break;
                }

                $selected_address = null;

                foreach ($addresses as $address) {
                    if ($address->id === $address_id) {
                        $selected_address = $address;
                        break;
                    }
                }

                $shipping_service = new ColombianShippingService();
                $order_repository = new SQliteOrderRepository($pdo);
                $cart_repository = new SQliteCartRepository($pdo);
                $checkout_service = new ColombianCheckout($shipping_service, $order_repository, $cart_repository);

                $order_prices_dto = $checkout_service->calculatePrices($cart, $selected_address);

                $output->writeln('');
                $output->writeln('<info>Checkout Resume</info>');

                $this->renderTable(
                    $output,
                    'Items',
                    array_keys($cart->items[0]->toArray()),
                    $cart->items
                );

                $this->renderTable(
                    $output,
                    'Pre-checkout values',
                    array_keys($order_prices_dto->toArray()),
                    array($order_prices_dto)
                );

                $create_order = select(
                    label: 'Do you confirm the order ?',
                    options: [
                        'true' => 'Yes',
                        'false' => 'No'
                    ]
                );

                $create_order = $create_order === 'true' ? true : false;

                if ($create_order) {
                    $creted_order = $this->finishtCheckout(
                        $output,
                        $pdo,
                        $cart,
                        $selected_address,
                        $order_prices_dto,
                        $checkout_service
                    );

                    $this->renderTable(
                        $output,
                        "Order created",
                        array_keys($creted_order->toArray()),
                        array($creted_order)
                    );
                    $is_calculating = false;
                    break;
                }
            }


        } catch (Exception $exception) {
            $output->writeln('<error> ' . $exception->getMessage() . '</error>');
        }

    }

    public function checOrders(OutputInterface $output, PDO $pdo): void
    {
        try {
            $order_repository = new SQliteOrderRepository($pdo);

            $customers = $this->getAllUsers($pdo);
            $mapped_customers = [];

            foreach ($customers as $customer) {
                $mapped_customers[$customer->id] = 'id: ' . $customer->id . ' - ' . $customer->fullName();
            }

            $customer_id = select(
                label: 'What user do you want to get the orders?',
                options: $mapped_customers,
            );

            $orders = $order_repository->getOrdersByCustomer($customer_id);

            foreach ($customers as $customer) {
                if ($customer->id === $customer_id) {
                    $selected_customer = $customer;
                    break;
                }
            }

            $this->renderTable(
                $output,
                'Orders By User - id:' . $customer_id . "/" . $selected_customer->fullName(),
                array_keys($orders[0]->toArray()),
                $orders
            );
        } catch (Exception $exception) {
            $output->writeln('<error> ' . $exception->getMessage() . '</error>');
        }
    }

    public function finishtCheckout(
        OutputInterface $output,
        PDO $pdo,
        CartModel $cart,
        AddressModel $address,
        OrderPricesDTO $order_prices_dto,
        Checkout $checkout_service
    ) {
        $order_created = $checkout_service->finishCheckoutProcess(
            $cart,
            $address,
            $order_prices_dto
        );

        return $order_created;
    }
}