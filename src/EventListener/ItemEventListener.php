<?php

namespace App\EventListener;

use App\Contract\DecoderInterface;
use App\Entity\Item;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ItemEventListener
{
    /**
     * @var DecoderInterface
     */
    private $encoder;

    public function __construct(DecoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param Item $item
     * @param LifecycleEventArgs $event
     */
    public function prePersist(Item $item, LifecycleEventArgs $event): void
    {
        $item->setData($this->encoder->encode($item->getData()));
    }

    /**
     * @param Item $item
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(Item $item, LifecycleEventArgs $event): void
    {
        $item->setData($this->encoder->encode($item->getData()));
    }

    /**
     * @param Item $item
     * @param LifecycleEventArgs $event
     */
    public function postLoad(Item $item, LifecycleEventArgs $event): void
    {
        $item->setData($this->encoder->decode($item->getData()));
    }
}
