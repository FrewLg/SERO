<?php

namespace App\Helper;

use App\Entity\SERO\Application;
use App\Entity\SERO\IrbCertificate;
use Doctrine\ORM\EntityManagerInterface;


class SEROHelper
{

    private  $allCert;
    private  $allapp;
    public function __construct(EntityManagerInterface $em)
    {
        $allapp = $em->getRepository(Application::class)->findAll();
        $allCert = $em->getRepository(IrbCertificate::class)->findAll();
        $this->allapp = $allapp;
        $this->allCert = $allCert;
    }

    public function fileNamer($version)
    {

        $app = $version->getApplication()->getIbcode();
        $versionName = $version->getVersionNumber();
        $attachmentType = 'Original protocol';
        $time = date("h-m-y");
        $user = $version->getApplication()->getSubmittedBy();
        return $app . "-" . $versionName . "-" . $attachmentType . "-" . $time . "-" . $user . ".";
    }
    public function icfFileNamer($icf)
    {

        $app = $icf->getApplication()->getIbcode();
        $icfName = $icf->getVersionNumber();
        $attachmentType = 'ICF';
        $time = date("h-m-y");
        $user = $icf->getApplication()->getSubmittedBy();
        return $app . "-" . $icfName . "-" . $attachmentType . "-" . $time . "-" . $user . ".";
    }

    public function ammendmentFileNamer($ammendment)
    {

        $app = $ammendment->getVersion()->getApplication()->getIbcode();
        $appid = $ammendment->getVersion()->getApplication();
        $versionName = $ammendment->getVersion()->getVersionNumber();
        $attachmentType = 'Ammendment protocol';
        $time = date("h-m-y");
        $append = $this->versionate($appid);
        $user = $ammendment->getVersion()->getApplication()->getSubmittedBy();
        return $append . $app . "-" . $versionName . "-" . $attachmentType . "-" . $time . "-" . $user . ".";
    }


    public function versionate($application)
    {
        $code = "EPHI-SERO";
        $year = date("y");
        $allAppinDb = $this->allapp;
        $newAppId = count($allAppinDb) + 1;
        return $code . "-" . $newAppId . '-' . $year;
    }

    public function certIdGenerator($application)
    {
        $code = "EPHISERO";
        $year = date("y");
        $allAppinDb = $this->allCert;
        $newAppId = count($allAppinDb) + 1;
        return $code . $newAppId .  $year;
    }
    public function icfVersion($application)
    {
        $versionnumber = count($application->getIcfs()) + 1;
        return $versionnumber;
    }
}
