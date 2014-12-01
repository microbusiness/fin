<?php

namespace Fin\GoogleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fin\GoogleBundle\Entity\Portfolio;

/**
 * Company
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Fin\GoogleBundle\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="codeMarket", type="string", length=255)
     */
    private $codeMarket;

    /**
     *  @var string
     *
     * @ORM\Column(name="codeCompany", type="string", length=255)
     */
    private $codeCompany;

    /**
     * * @var Portfolio
     *
     * @ORM\ManyToOne(targetEntity="Portfolio")
     * @ORM\JoinColumn(name="portfolio_id", referencedColumnName="id", nullable=false)
     */
    private $portfolio;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set codeMarket
     *
     * @param string $codeMarket
     * @return Company
     */
    public function setCodeMarket($codeMarket)
    {
        $this->codeMarket = $codeMarket;

        return $this;
    }

    /**
     * Get codeMarket
     *
     * @return string 
     */
    public function getCodeMarket()
    {
        return $this->codeMarket;
    }

    /**
     * Set codeCompany
     *
     * @param string $codeCompany
     * @return Company
     */
    public function setCodeCompany($codeCompany)
    {
        $this->codeCompany = $codeCompany;

        return $this;
    }

    /**
     * Get codeCompany
     *
     * @return string 
     */
    public function getCodeCompany()
    {
        return $this->codeCompany;
    }

    /**
     * @return mixed
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }

    /**
     * @param mixed $portfolioId
     */
    public function setPortfolio($portfolio)
    {
        $this->portfolio = $portfolio;
    }


}
