<?php

// src/AppBundle/Command/CreateUserCommand.php
namespace MilesApart\SellerBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GetAmazonOrdersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:get-amazon-orders')

            // the short description shown while running "php app/console list"
            ->setDescription('Gets outstanding Amazon orders and adds them to the database.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to get new Amazon orders...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Get the Royal Mail service
        $royal_mail_service = $this->getContainer()->get('seller.amazon_orders_service');

        $service_output = $royal_mail_service->getAmazonOrders();

        //If there are any orders
        if($service_output['output_output']) {
            // outputs multiple lines to the console (adding "\n" at the end of each line)
            $output->writeln([
                'New Orders!!',
                '============',
                '',
            ]);

            // outputs a message followed by a "\n"
            $output->writeln('A confirmation email has been sent.');


        }


        //If there are no new orders


        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'No new orders',
            '============',
            '',
        ]);

    }

}