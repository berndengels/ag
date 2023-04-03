<?php

namespace App\Repositories\Traits;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

trait ResultParser
{
    public function parse(DomCrawler $article)
    {
        if('Keine Treffer' === $article->attr('aria-label')) {
            return null;
        }

        $a      = $article->filter('h3 a');
        $name   = trim($a->text());
        $url    = $a->attr('href');
        $info   = null;
        $body   = $article->filter('div.search-results-body div.row');
        $sections           = $body->filter('.search-results-body-section');
        $visitorAddress     = $sections->first()->filter('.visitor-address span');
        $street             = trim($visitorAddress->first()->text());
        [$postcode, $city]  = explode('&nbsp;', htmlentities(trim($visitorAddress->last()->text())), 2);
        $city               = $city;
        $email              = null;
        $fon                = null;
        $openingTime        = null;

        $emailFilter = $sections->eq(1)->filter('.email a');
        if($emailFilter->count() > 0) {
            $email = trim($emailFilter->first()->text()) ?? null;
        }

        $fonFilter = $sections->eq(1)->filter('.phone-numbers li a');
        if($fonFilter->count() > 0) {
            $fon = $fonFilter->each(fn (DomCrawler $a) => $a->text());
            $fon = implode("\n", $fon);
        }

        $openingTimeFilter = $sections->last()->filter('.opening-hours li');
        if($openingTimeFilter->count() > 0) {
            $openingTime = $openingTimeFilter->each(function(DomCrawler $li) {
                $text = $li->text();
                if('' !== $text) {
                    return $text;
                }
            });
            $openingTime = implode("\n", $openingTime);
        }

        if(preg_match("/\(/", $name)) {
            preg_match("/^([^\(\)]+) ([\(][^\)]+[\)])$/i", $name, $matches);
            if(3 === count($matches)) {
                [, $name, $info] = $matches;
                $info = preg_replace("/[\(\)]+/",'', $info);
            }
        }

        $data = [
            'name'      => html_entity_decode(string: $name, encoding: 'UTF-8'),
            'info'      => html_entity_decode(string: $info, encoding: 'UTF-8'),
            'url'       => $url,
            'street'    => html_entity_decode(string: $street, encoding: 'UTF-8'),
            'postcode'  => $postcode,
            'city'      => html_entity_decode(string: $city, encoding: 'UTF-8'),
            'email'     => $email,
            'fon'       => $fon ? html_entity_decode(string: $fon, encoding: 'UTF-8') : null,
            'opening_time'  => $openingTime ? html_entity_decode(string: $openingTime, encoding: 'UTF-8') : null
        ];

        return $data;
    }
}
