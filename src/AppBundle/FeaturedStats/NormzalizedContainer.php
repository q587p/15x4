<?php

namespace AppBundle\FeaturedStats;

class NormzalizedContainer extends Container
{
    /** {@inheritdoc} */
    public function getItems()
    {
        usort($this->items, function (Item $item1, Item $item2) {
            if ($item1->getScore() > $item2->getScore()) {
                return -1;
            }
            if ($item1->getScore() < $item2->getScore()) {
                return 1;
            }

            //handle tossup
            if ($item1->getTotalVotes() > $item2->getTotalVotes()) {
                return 1;
            }
            if ($item1->getTotalVotes() < $item2->getTotalVotes()) {
                return -1;
            }


            return 0;
        });

        return $this->items;
    }
}
