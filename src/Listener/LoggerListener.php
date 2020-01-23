<?php

namespace JGI\ArbetsformedlingenAdsBundle\Listener;

use JGI\ArbetsformedlingenAds\Event\ResultEvent;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggerListener
{
    /**
     * @var LoggerInterface[]
     */
    private $loggers = [];

    /**
     * @param LoggerInterface $logger
     */
    public function addLogger(LoggerInterface $logger): void
    {
        $this->loggers[] = $logger;
    }

    /**
     * @param ResultEvent $event
     */
    public function whenResultIsReturned(ResultEvent $event): void
    {
        $level = $event->getResult()->isSuccess() ? LogLevel::DEBUG : LogLevel::ERROR;

        foreach ($this->loggers as $logger) {
            $logger->log($level, sprintf('TransactionId: %s', $event->getTransaction()->getTransactionId()));
            $logger->log($level, $event->getHrxmlDocument()->saveXML());
            $logger->log($level, $event->getResponse());
        }
    }
}
