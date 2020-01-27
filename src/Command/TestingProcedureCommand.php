<?php

namespace JGI\ArbetsformedlingenAdsBundle\Command;

use JGI\ArbetsformedlingenAds\Client;
use JGI\ArbetsformedlingenAds\Model\Transaction;
use JGI\ArbetsformedlingenAds\TestJobsCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestingProcedureCommand extends Command
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('arbetsformedlingen:testing-procedure')
            ->setDescription('Send ads for test procedure to Arbetsförmedlingen.')
            ->addArgument('senderId', InputArgument::REQUIRED)
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('organisationNumber', InputArgument::REQUIRED)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->client->isTestEnvironment()) {
            $output->writeln('<error>Command must be run in test environment</error>');

            return 1;
        }

        $senderId = $input->getArgument('senderId');
        $email = $input->getArgument('email');
        $organisationNumber = $input->getArgument('organisationNumber');

        $arbetsformedlingenJobs = TestJobsCollection::createTestJobs($organisationNumber, $email);

        $transactionIdDatePart = (new \DateTime())->format('Ymd_His_');
        foreach ($arbetsformedlingenJobs as $i => $arbetsformedlingenJob) {
            $transactionId = $transactionIdDatePart . ($i + 1);
            $transaction = new Transaction($senderId, $email, $transactionId, [$arbetsformedlingenJob]);

            $result = $this->client->publish($transaction);
            if ($result->isSuccess()) {
                $output->writeln(sprintf('Transaction with id %s sent successfully', $transactionId));
            } else {
                $output->writeln(sprintf('<error>Transaction with id %s sent with failure</error>', $transactionId));
                foreach ($result->getErrors() as $error) {
                    $output->writeln(sprintf('<error>%s (%s)</error>', $error->getMessage(), $error->getErrorCode()));
                }
            }
        }

        $output->writeln('');
        $output->writeln('Completed');
    }
}
