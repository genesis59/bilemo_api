<?php

namespace App\DataFixtures;

use App\Entity\Camera;
use App\Entity\Color;
use App\Entity\Customer;
use App\Entity\Picture;
use App\Entity\Reseller;
use App\Entity\Smartphone;
use App\Factory\BrandFactory;
use App\Factory\CameraFactory;
use App\Factory\ColorFactory;
use App\Factory\CustomerFactory;
use App\Factory\PictureFactory;
use App\Factory\RangeFactory;
use App\Factory\ResellerFactory;
use App\Factory\ScreenFactory;
use App\Factory\SmartphoneFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Zenstruck\Foundry\Proxy;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        BrandFactory::createMany(5);
        RangeFactory::createMany(15);
        ColorFactory::createMany(20);
        CameraFactory::createMany(10);
        ScreenFactory::createMany(10);
        SmartphoneFactory::createMany(100);

        /** @var Proxy $smartphoneProxy */
        foreach (SmartphoneFactory::all() as $smartphoneProxy) {

            /** @var Smartphone $smartphone */
            $smartphone = $smartphoneProxy->object();
            /** @var Proxy $pictureProxy */
            $pictureProxy = PictureFactory::createOne(['smartphone' => $smartphone]);
            /** @var Picture $picture */
            $picture = $pictureProxy->object();
            $smartphone->addPicture($picture);

            /** @var Proxy $cameraProxy */
            foreach (CameraFactory::randomSet(rand(1, 2)) as $cameraProxy) {
                /** @var Camera $camera */
                $camera = $cameraProxy->object();
                $smartphone->addCamera($camera);
            }
            /** @var Proxy $colorProxy */
            foreach (ColorFactory::randomSet(rand(1, 4)) as $colorProxy) {
                /** @var Color $color */
                $color = $colorProxy->object();
                $smartphone->addColor($color);
            }
        }

        PictureFactory::createMany(50);
        ResellerFactory::createMany(5);
        CustomerFactory::createMany(200);
        /** @var Proxy $customerProxy */
        foreach (CustomerFactory::all() as $customerProxy) {
            $customerProxy->disableAutoRefresh();
            /** @var Customer $customer */
            $customer = $customerProxy->object();

            /** @var Proxy $smartphoneProxy */
            foreach (SmartphoneFactory::randomSet(rand(1, 5)) as $smartphoneProxy) {
                /** @var Smartphone $smartphone */
                $smartphone = $smartphoneProxy->object();
                $customer->addSmartphone($smartphone);
            }
            /** @var Proxy $resellerProxy */
            $resellerProxy = ResellerFactory::random();
            /** @var Reseller $reseller */
            $reseller = $resellerProxy->object();
            $customer->setReseller($reseller);
        }

        $manager->flush();
    }
}
