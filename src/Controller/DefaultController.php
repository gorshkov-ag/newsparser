<?php
/**
 * Created by PhpStorm.
 * User: gorshkov-ag
 * Date: 07.06.2018
 * Time: 16:10
 */

namespace App\Controller;


use App\Entity\NewsItem;
use App\Entity\Site;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Site $site */
        $site = $em->getRepository(Site::class)->findOneBy(array());
        if (is_null($site)) {
            return $this->redirectToRoute('update');
        }

        $form = $this->createFormBuilder()
            ->add('sortField', ChoiceType::class, [
                'choices' => [
                    'Date DESC' => 'dd',
                    'Date ASC' => 'da',
                    'Title ASC' => 'ta',
                    'Title DESC' => 'td',
                ]
            ])
            ->add('dateFrom', TextType::class, array(
                'help' => 'Ex. 15.03.2017 15:32'
            ))
            ->add('dateTo', TextType::class, array(
                'help' => 'Ex. 15.05.2018 18:56'
            ))
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateFromData = $form->get('dateFrom')->getData();
            $dateFrom = null;
            $dateTo = null;
            $orderField = null;
            $orderDirection = null;

            if (DateTime::createFromFormat('d m Y, H:i', $dateFromData)) {
                $dateFrom = (DateTime::createFromFormat('d m Y, H:i', $dateFromData));
            }
            $dateToData = $form->get('dateTo')->getData();
            if (DateTime::createFromFormat('d m Y, H:i', $dateToData)) {
                $dateTo = (DateTime::createFromFormat('d m Y, H:i', $dateToData));
            }

            $orderData = $form->get('sortField')->getData();
            switch ($orderData) {
                case 'ta':
                case 'td':
                    $orderField = 'title';
                    break;
                case 'da':
                case 'dd':
                    $orderField = 'pubDate';
                    break;
            }
            switch ($orderData) {
                case 'ta':
                case 'da':
                    $orderDirection = 'ASC';
                    break;
                case 'td':
                case 'dd':
                    $orderDirection = 'DESC';
                    break;
            }

            $newsItems = $em->getRepository(NewsItem::class)->filterByDate($dateFrom, $dateTo,
                $orderField, $orderDirection);
        } else {
            /** @var NewsItem[] $newsItems */
            $newsItems = $em->getRepository(NewsItem::class)
                ->findBy(array('siteId' => $site->getId()),
                    array('pubDate' => 'DESC'), 25);
        }

        return $this->render('index.html.twig', array(
            'form' => $form->createView(),
            'site' => $site,
            'news_items' => $newsItems
        ));
    }


}