<?php

namespace Fin\GoogleBundle\Service;

use Goutte\Client;
use DateTime;

class GoogleFinance {

    private $em;
    private $url;

    public function __construct($em,$url)
    {
        $this->em=$em;
        $this->url=$url;
    }

    public function getPorfolioFromGoogle($id,$begin,$end)
    {
        $portfolio=$this->em->getRepository('FinGoogleBundle:Portfolio')->find($id);

        $portfolioArray=array();
        foreach ($portfolio->getCompany() as $company)
        {
            $data=file($this->url.'q='.$company->getCodeMarket().':'.$company->getCodeCompany().'&startdate='.$begin->format('M+d,Y').'&enddate='.$end->format('M+d,Y').'&output=csv');
            $fl=0;
            foreach ($data as $item)
            {
                if ($fl==1)
                {
                    $ai=explode(',',$item);
                    if (!array_key_exists($ai[0],$portfolioArray))
                    {
                        $portfolioArray[$ai[0]]=array();
                    }

                    $portfolioArray[$ai[0]][]=array('c'=>$company->getCodeCompany(),'open'=>$ai[1]);
                }
                else
                {
                    $fl=1;
                }

            }
        }

        $portfolio->setStat($portfolioArray);

        return $portfolio;

    }



} 