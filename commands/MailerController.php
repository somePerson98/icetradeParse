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
use darkdrim\simplehtmldom\SimpleHTMLDom;



class MailerController extends Controller
{

    public function actionSend(){

        $auctions = [];

        $data = \darkdrim\simplehtmldom\SimpleHTMLDom::file_get_html("http://www.icetrade.by/search/auctions?search_text=%D0%BC%D1%8F%D1%81%D0%BE&search=%D0%9D%D0%B0%D0%B9%D1%82%D0%B8&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=20&p=1");
        $lastNum = Auctions::findOne(['id' => 1]);

        $newNum = $data->find('table#auctions-list tr')[1]->find('td')[3]->innerText();

        $link = $data->find('div.paging')[0]->lastChild()->innerText();
        if ($newNum != $lastNum) {
            for ($i = 1; $i < (int)$link + 1; $i++) {

                $data2 = \darkdrim\simplehtmldom\SimpleHTMLDom::file_curl_get_html("http://www.icetrade.by/search/auctions?search_text=%D0%BC%D1%8F%D1%81%D0%BE&search=%D0%9D%D0%B0%D0%B9%D1%82%D0%B8&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=20&p=$i");
                $count = 0;
                foreach ($data2->find('table#auctions-list tr') as $element) {

                    if ($count == 0) {
                        $count++;
                        continue;
                    }

                    if ($element->find('td')[3]->innerText() != $lastNum->number) {
//                        echo $element . '<br>' . '<br>';

                        array_push($auctions, $element);

                        if ($i == $link){
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

                    } else {

                        $this->sendMail(
                            'body_mail',
                            $auctions,
                            'd_rahatsevich@mail.ru',
                            'test.mailer.php@yandex.by'
                        );
//                        $result = Yii::$app->mailer->compose('body_mail', ['auctions' => $auctions])
//                            ->setFrom('d_rahatsevich@mail.ru')
//                            ->setTo('test.mailer.php@yandex.by')
//                            ->setSubject('Тема сообщения')
////            ->setTextBody('Текст сообщения')
////            ->setHtmlBody('<b>qweтекст сообщения в формате HTML</b>')
//                            ->send();

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




}