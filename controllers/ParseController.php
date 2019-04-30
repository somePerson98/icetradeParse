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
use app\components\simplehtmldom\SimpleHTMLDom as SHD;


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

            $currentProductData = SHD::file_get_html($productLink);

            $currentProductDesc = $currentProductData->find('ul.description')[0]->lastChild()->innerText();
            $currentProductImg = $currentProductData->find('img.retina_redy')[0];
            $currentProductName = $currentProductData->find('h1')[0]->innerText();

            array_push($products, "$currentProductName <br> $currentProductDesc <br> $currentProductImg");


                echo $currentProductDesc. '<br>';


        }

//        $result = Yii::$app->mailer->compose('index', ['products' => $products])
//            ->setFrom('dahatsevich2019@gmail.com')
//            ->setTo('test.mailer.php@yandex.by')
//            ->setSubject('Тема сообщения')
////            ->setTextBody('Текст сообщения')
////            ->setHtmlBody('<b>qweтекст сообщения в формате HTML</b>')
//            ->send();
//
//        var_dump($result);
//        die();

    }

    public function actionMailer()
    {

        $result = Yii::$app->mailer->compose()
            ->setFrom('d_rahatsevich@mail.ru')
            ->setTo('test.mailer.php@yandex.by')
            ->setSubject('Тема сообщения')
            ->setTextBody('Текст сообщенияassadasdasds')
            ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
            ->send();


        var_dump($result);
        die();

    }

    public function actionSend(){

        $auctions = [];

        $numbers = Auctions::find()->all();
//        echo "<pre>";
//        print_r($numbers[0]->number);
//        exit();

        $keyWords = [
            'http://www.icetrade.by/search/auctions?search_text=%D0%B4%D0%BE%D1%80%D0%BE%D0%B6&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D0%BF%D0%B5%D1%80%D0%B5%D0%B5%D0%B7%D0%B4&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D1%81%D1%82%D1%80%D0%B5%D0%BB&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D0%BF%D0%B5%D1%80%D0%B5%D0%B2%D0%BE%D0%B4&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D1%88%D0%BF%D0%B0%D0%BB&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p='

        ];

//        $dataStr = $this->file_get_contents_curl($keyWords[1]);
//        $data = SHD::str_get_html($dataStr);
//
//        $link = $data->find('div.paging')[0];
//        var_dump($link);


        for ($i = 0;$i < count($keyWords);$i++){
            $dataStr = $this->file_get_contents_curl($keyWords[$i]);
            $data = SHD::str_get_html($dataStr);
            $newNum = $data->find('table#auctions-list tr')[1]->find('td')[3]->innerText();

            $lastNum = $numbers[$i];
//            echo $lastNum->number;
//            exit();

            $link = $data->find('div.paging')[0];

            if ($newNum != $lastNum->number) {

                if ($link == null){
                    $link = 0;
                }else{
                    $link = $link->lastChild()->innerText();
                }
                for ($j = 1; $j < (int)$link + 1; $j++) {



                    $dataStr2 = $this->file_get_contents_curl($keyWords[$i].$j);
                    $data2 = SHD::str_get_html($dataStr2);


                    $count = 0;
                    foreach ($data2->find('table#auctions-list tr') as $element) {

                        if ($count == 0) {
                            $count++;
                            continue;
                        }

                        if ($element->find('td')[3]->innerText() != $lastNum->number) {
//                            echo $element . '<br>' . '<br>';

                            array_push($auctions, $element);


                        } else {


                            $lastNum->number = $newNum;
                            $lastNum->save();


                            break;
                        }

                    }
                        break;
                }

            }

        }

        $this->sendMail(
            'body_mail',
            $auctions,
            'd_rahatsevich@mail.ru',
            'test.mailer.php@yandex.by'
        );

        exit();


    }


    function sendMail($body_mail, $array, $from, $to){

        $result = Yii::$app->mailer->compose("$body_mail", ['params' => $array])
            ->setFrom("$from")
            ->setTo("$to")
            ->setSubject('Тема сообщения')
//            ->setTextBody('Текст сообщения')
//            ->setHtmlBody('<b>qweтекст сообщения в формате HTML</b>')
            ->send();

        return var_dump($result);



    }

    function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}