<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28.02.2019
 * Time: 12:28
 */

namespace app\controllers;

use app\commands\MailerController;
use Symfony\Component\Yaml\Tests\A;
use Yii;
use yii\base\Theme;
use yii\web\Controller;
use app\models\Auctions;
use app\components\simplehtmldom\SimpleHTMLDom as SHD;
use app\controllers\KeyWordsController;


class ParseController extends Controller
{

    public $auctions;
    public $auctionsToSend;
    public $lastNumber;
    public $total;

    public function actionParse1()
    {

        echo 111;
        return;

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

    public function actionParse()
    {
        $keyWords = KeyWordsController::getKeyWords();
        $this->auctionsToSend = [];
        $this->auctions = new Auctions();
        $this->checkKeyWords($keyWords);
        $thead = '';
        foreach ($keyWords as $keyWord => $encodedWord) {
            $url = "http://www.icetrade.by/search/auctions?search_text=$encodedWord&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1";
            $pageCount = self::getPageCount($url . '&onPage=' . MailerController::SHOW_ITEMS);
            if (! $pageCount) {
                continue;
            }
            for ($i = 0; $i < $pageCount; $i++) {
                $p = $i+1;
                $data = SHD::file_curl_get_html($url . '&p=' . $p, 1, 5000);
                $stop = false;
                $items = $data->find("#auctions-list tr");

                foreach($data->find("#auctions-list tr") as $item) {

                    //пропуск thead
                    if ($item->find('th')){
                        $thead = $item;
                        continue;
                    }
                    $number = $item->find('td')[3]->innerText();
                    $this->lastNumber = $this->lastNumber == null ? $number : $this->lastNumber;

                    //если номера в бд нет - продолжаем заполнять массив
                    if (! $this->hasNumber($keyWord, $number)){
                        array_push($this->auctionsToSend, ['key_word' => $keyWord, 'number' => $number, 'item' => $item]);
                        //если эл-т и стр последние - то добавить в массив (при условии, что ! $this->hasNumber)
                        if ($this->isLastTrAndLastPage($items, $item, $pageCount, $i)) {
                            $this->setNumber($keyWord);
                        }
                        continue;
                    }else {
                        $this->setNumber($keyWord);
                        $stop = true;
                        break;
                    }
                }
                if ($stop) break;
            }
        }
        return $this->render('index', ['parsed' => $this->auctionsToSend, 'thead' => $thead]);
    }

    protected function getPageCount($url) {
        $data = SHD::file_curl_get_html($url, 1, 5000);
        $totalStr = $data->find('.total') ? $data->find('.total')[0]->innerText() : false;
        if (! $totalStr) return false;
        $total = preg_replace("/[^,.0-9]/", '', $totalStr);
        if((int) $total == 0)
            return false;
        $pageCount = (int) $total / MailerController::SHOW_ITEMS <= 1 ? 1 : ceil((int) $this->total / MailerController::SHOW_ITEMS); //округление в большую сторону

        return $pageCount;
    }

    protected function checkKeyWords($keyWords) {
        foreach ($keyWords as $keyWord => $encodedWord) {
            if ($this->auctions->find()->andWhere(['key_word' => $keyWord])->one()) {
                continue;
            }
            $this->auctions->key_word = $keyWord;
            $this->auctions->insert();
        }
    }

    protected function hasNumber($keyWord, $number) {
        $auction = $this->auctions->find()->andWhere(['key_word' => $keyWord, 'number' => $number])->one();
        if (! $auction) {
            return false;
        }
        return true;
    }

    public function setNumber($keyWord) {
        $auction = Auctions::find()->andWhere(['key_word' => $keyWord])->one();
        $auction->number = $this->lastNumber;
        $auction->save();
        $this->lastNumber = null;
    }

    protected function isLastTrAndLastPage($collection, $currentItem, $pageCount, $i) {
        $lastItemRel = end($collection)->attr['rel'];
        $currentItemRel = $currentItem->attr['rel'];
        if ($i == $pageCount - 1 && $lastItemRel == $currentItemRel) {
            return true;
        }
        return false;
    }
}