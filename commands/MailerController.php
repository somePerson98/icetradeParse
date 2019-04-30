<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.03.2019
 * Time: 15:03
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Auctions;
use yii\console\ExitCode;
use Yii;
use darkdrim\simplehtmldom\SimpleHTMLDom as SHD;



class MailerController extends Controller
{

    public function actionSend(){

        $dataStr = $this->file_get_contents_curl("http://www.icetrade.by/search/auctions?search_text=%D0%BC%D1%8F%D1%81%D0%BE&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=20&p=1");

        $auctions = [];




        $data = SHD::str_get_html($dataStr);

        $lastNum = Auctions::findOne(['id' => 1]);

        $newNum = $data->find('table#auctions-list tr')[1]->find('td')[3]->innerText();

        $link = $data->find('div.paging')[0]->lastChild()->innerText();

        if ($newNum != $lastNum->number) {
            for ($i = 1; $i < (int)$link + 1; $i++) {




                $dataStr2 = $this->file_get_contents_curl("http://www.icetrade.by/search/auctions?search_text=%D0%BC%D1%8F%D1%81%D0%BE&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=20&p=$i");
                $data2 = SHD::str_get_html($dataStr2);

                $count = 0;
                foreach ($data2->find('table#auctions-list tr') as $element) {

                    if ($count == 0) {
                        $count++;
                        continue;
                    }

                    if ($element->find('td')[3]->innerText() != $lastNum->number) {
//                        echo $element . '<br>' . '<br>';

                        array_push($auctions, $element);

//                        if ($i == $link){
//                            $this->sendMail(
//                                'body_mail',
//                                $auctions,
//                                'd_rahatsevich@mail.ru',
//                                'test.mailer.php@yandex.by'
//                            );
//
//                            $lastNum->number = $newNum;
//                            $lastNum->save();
//
//
//                            die();
//                        }

                    } else {

                        $this->sendMail(
                            'body_mail',
                            $auctions,
                            'd_rahatsevich@mail.ru',
                            'test.mailer.php@yandex.by'
                        );

                        $lastNum->number = $newNum;
                        $lastNum->save();
                        die();
                    }
                }

            }

            exit();
        }

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

    public function actionTest(){

        $data = $this->file_get_contents_curl("http://www.icetrade.by/");

//        var_dump($data);
        $html = SHD::str_get_html($data);
        echo $html;
        exit();



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
