<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28.02.2019
 * Time: 12:28
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Auctions;
use darkdrim\simplehtmldom\SimpleHTMLDom as SHD;


class ParseController extends Controller
{


    public function actionParse()
    {

        $products = [];


        $data = SHD::file_get_html(
            "https://e-dostavka.by/search/?searchtext=маскарпоне"

        );

        foreach ($data->find('div.products_card') as $productCard) {

            $productLink = $productCard->find('div.title a.fancy_ajax')[0]->attr['href'];

            $currentProductData = \darkdrim\simplehtmldom\SimpleHTMLDom::file_get_html($productLink);

            $currentProductDesc = $currentProductData->find('ul.description')[0]->lastChild()->innerText();
            $currentProductImg = $currentProductData->find('img.retina_redy')[0];
            $currentProductName = $currentProductData->find('h1')[0]->innerText();

            array_push($products, "$currentProductName <br> $currentProductDesc <br> $currentProductImg");


//                echo  .  . $currentProductDesc. '<br>';


        }

        $result = Yii::$app->mailer->compose('index', ['products' => $products])
            ->setFrom('dahatsevich2019@gmail.com')
            ->setTo('test.mailer.php@yandex.by')
            ->setSubject('Тема сообщения')
//            ->setTextBody('Текст сообщения')
//            ->setHtmlBody('<b>qweтекст сообщения в формате HTML</b>')
            ->send();

        var_dump($result);
        die();

    }

    public function actionMailer()
    {

        $result = Yii::$app->mailer->compose()
            ->setFrom('dahatsevich2019@gmail.com')
            ->setTo('test.mailer.php@yandex.by')
            ->setSubject('Тема сообщения')
            ->setTextBody('Текст сообщенияassadasdasds')
            ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
            ->send();


        var_dump($result);
        die();

    }

    public function actionSend()
    {

        $auctions = [];

        $data = \darkdrim\simplehtmldom\SimpleHTMLDom::file_curl_get_html("http://www.icetrade.by/search/auctions?search_text=дерево&search=Найти&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=100&p=1");
        $lastNum = Auctions::findOne(['id' => 1]);

        $newNum = $data->find('table#auctions-list tr')[1]->find('td')[3]->innerText();

        $link = $data->find('div.paging')[0]->lastChild()->innerText();
        if ($newNum != $lastNum) {
            for ($i = 1; $i < $link + 1; $i++) {

                $data2 = \darkdrim\simplehtmldom\SimpleHTMLDom::file_curl_get_html("http://www.icetrade.by/search/auctions?search_text=дерево&search=Найти&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=100&p=$i");
                $count = 0;
                foreach ($data2->find('table#auctions-list tr') as $element) {

                    if ($count == 0) {
                        $count++;
                        continue;
                    }

                    if ($element->find('td')[3]->innerText() != $lastNum->number) {
                        echo $element . '<br>' . '<br>';

//                        array_push($auctions, $element);

                    } else {
//                        $result = Yii::$app->mailer->compose('body_mail', ['auctions' => $auctions])
//                            ->setFrom('dahatsevich2019@gmail.com')
//                            ->setTo('test.mailer.php@yandex.by')
//                            ->setSubject('Тема сообщения')
////            ->setTextBody('Текст сообщения')
////            ->setHtmlBody('<b>qweтекст сообщения в формате HTML</b>')
//                            ->send();
//
//                        var_dump($result);
                        die('error');
                    }


                }

            }

            exit();
        }

    }
}