<?php

namespace PiZone\ContentBundle\Services;

class PiZonePager
{

    public static function calculateStartAndEndPage($pagerfranta, $proximity = 3)
    {
        $startPage = $pagerfranta->getCurrentPage() - $proximity;
        $endPage = $pagerfranta->getCurrentPage() + $proximity;

        if (self::startPageUnderflow($startPage)) {
            $endPage = self::calculateEndPageForStartPageUnderflow($startPage, $endPage, $pagerfranta);
            $startPage = 1;
        }
        if (self::endPageOverflow($endPage, $pagerfranta)) {
            $startPage = self::calculateStartPageForEndPageOverflow($startPage, $endPage, $pagerfranta);
            $endPage = $pagerfranta->getNbPages();
        }

        return array('start' => $startPage, 'end' => $endPage);
    }

    private static function startPageUnderflow($startPage)
    {
        return $startPage < 1;
    }

    private static function endPageOverflow($endPage, $pagerfranta)
    {
        return $endPage > $pagerfranta->getNbPages();
    }

    private static function calculateEndPageForStartPageUnderflow($startPage, $endPage, $pagerfranta)
    {
        return min($endPage + (1 - $startPage), $pagerfranta->getNbPages());
    }

    private static function calculateStartPageForEndPageOverflow($startPage, $endPage, $pagerfranta)
    {
        return max($startPage - ($endPage - $pagerfranta->getNbPages()), 1);
    }
}

