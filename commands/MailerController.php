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
use Yii;
use darkdrim\simplehtmldom\SimpleHTMLDom as SHD;



class MailerController extends Controller
{

    public function actionSend(){
        $mail = [];

        $auctions = Auctions::find()->all();
//        echo '<pre>';
//        print_r($auctions[0]);


        $keyWords = [
            'http://www.icetrade.by/search/auctions?search_text=%D0%B4%D0%BE%D1%80%D0%BE%D0%B6&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D0%BF%D0%B5%D1%80%D0%B5%D0%B5%D0%B7%D0%B4&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D0%BF%D0%B5%D1%80%D0%B5%D0%B2%D0%BE%D0%B4&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D1%88%D0%BF%D0%B0%D0%BB&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D1%81%D1%82%D1%80%D0%B5%D0%BB&zakup_type[1]=1&zakup_type[2]=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t[Trade]=1&t[eTrade]=1&t[socialOrder]=1&t[singleSource]=1&t[Auction]=1&t[Request]=1&t[contractingTrades]=1&t[negotiations]=1&t[Other]=1&r[1]=1&r[2]=2&r[7]=7&r[3]=3&r[4]=4&r[6]=6&r[5]=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D0%B6.%D0%B4.&search=%D0%9D%D0%B0%D0%B9%D1%82%D0%B8&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=20&p=',
            'http://www.icetrade.by/search/auctions?search_text=%D0%BF%D1%83%D1%82%D1%8C&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&onPage=20&p='
        ];

        for($i = 0; $i < count($keyWords); $i++)://1for
            $link = 1;
            $dataStr = $this->file_get_contents_curl($keyWords[$i]);
            $data = SHD::str_get_html($dataStr);

            //если tr меньше 3, то либо нет пагинации,
            //либо нет тендеров
            $count_tr = count($data->find('#auctions-list tr'));
            if($count_tr>3){
                $link =  $data->find('div.paging')[0]->lastChild()->innerText();
            }

            if($count_tr<3){
                if(count($data->find('#auctions-list tr')[1]->find('td'))<2){
                    continue;
                }
            }

            for ($j = 1;$j < (int)$link+1; $j++)://for2
                $newNum = $data->find('table#auctions-list tr')[1]->find('td')[3]->innerText();
                if ($newNum == $auctions[$i]->number):
                    break;
                endif;

                $dataStr2 = $this->file_get_contents_curl($keyWords[$i].$j);
                $data2 = SHD::str_get_html($dataStr2);
//                echo $keyWords[$i].$j . '<br>' . '<br>';
                $count = 0;
                $key = true;
                foreach ($data2->find('table#auctions-list tr') as $element){

                    if($count == 0){
                        $count++;
                        continue;
                    }

                    if ($element->find('td')[3]->innerText() == $auctions[$i]->number){
                        $key = false;
                        $auctions[$i]->number = $newNum;
                        $auctions[$i]->save();
                        break;//прерывание перебора tr
                    }
//                    echo $auctions[$i]->key_word. $element . '<br>' . '<br>';
                    array_push($mail, '<td colspan="2" style="background-color: #8e8e8e">'.$auctions[$i]->key_word .'</td>' . $element);


                }
                if ($key==false){
                    break;//прерывание перехода на сл. стр. на тек. запросе
                }


            endfor;//for2


        endfor;//1for
        if (empty($mail)){
            exit('no new tenders!');
        }
        $this->sendMail(
            'body_mail',
            $mail,
            'd_rahatsevich@mail.ru',
            'NWS_PTO@mail.ru'
        );

//        return $this->render('index', ['params'=>$mail]);

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

    function countElements($arr){

        for($i = 0; $i<count($arr);$i++){

        }
        return $i;


    }




}