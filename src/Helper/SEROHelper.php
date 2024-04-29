<?php

namespace App\Helper;

use App\Entity\SERO\Application;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

use function PHPUnit\Framework\returnSelf;

class SEROHelper
{

    public function fileNamer($version)
    {

        $app = $version->getApplication()->getIbcode();
        $versionName = $version->getVersionNumber();
        $attachmentType = 'Original protocol';
        $time = date("h-m-");
        $user = $version->getApplication()->getSubmittedBy();

        $fileName = $app . "-" . $versionName . "-" . $attachmentType . "-" . $time . "-" . $user.".";

        return $fileName;
    }
    public function ammendmentFileNamer($ammendment)
    {

        $app = $ammendment->getVersion()->getApplication()->getIbcode();
        $versionName = $ammendment->getVersion()->getVersionNumber();
        $attachmentType = 'Original protocol';
        $time = date("h-m-");
        $user = $ammendment->getVersion()->getApplication()->getSubmittedBy();

        $fileName = $app . "-" . $versionName . "-" . $attachmentType . "-" . $time . "-" . $user.".";

        return $fileName;
    }


    public function versionate($application)
    {
        $code="EPHI-SERO";
        $year=date("y");
        $id=$application->getId();
        $versionnumber=$code."-".$id.'-'.$year;
            return $versionnumber;
    }
}
