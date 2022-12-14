<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new MilesApart\AdminBundle\MilesApartAdminBundle(),
            new MilesApart\PublicBundle\MilesApartPublicBundle(),
            new MilesApart\StaffBundle\MilesApartStaffBundle(),
            new WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Ddeboer\DataImportBundle\DdeboerDataImportBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new Mopa\Bundle\BarcodeBundle\MopaBarcodeBundle(),
            new MilesApart\BasketBundle\MilesApartBasketBundle(),
            new Flob\Bundle\FoundationBundle\FlobFoundationBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),
            new Nurikabe\StarRatingBundle\NurikabeStarRatingBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new MilesApart\PublicUserBundle\MilesApartPublicUserBundle(),
            new Snowcap\ImBundle\SnowcapImBundle(),
            new MilesApart\SellerBundle\MilesApartSellerBundle(),
            new Caponica\AmazonMwsBundle\CaponicaAmazonMwsBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new Dizda\CloudBackupBundle\DizdaCloudBackupBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

  

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
