<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.03.2019
 * Time: 15:03
 */

namespace app\commands;


use app\controllers\GetMailersController;
use app\controllers\KeyWordsController;
use yii\console\Controller;
use app\models\Auctions;
use Yii;
use app\components\simplehtmldom\SimpleHTMLDom;

class MailerController extends Controller
{
    const SHOW_ITEMS = 20;
    public $auctions;
    public $auctionsToSend;
    public $lastNumber;
    public $total;

    public function actionSend() {
        $auctions = $this->actionParse();
        if (! empty($auctions['auctionsToSend']))
            $this->sendMail('body_mail', $auctions['auctionsToSend'], $auctions['thead']);
        else echo "no new tenders";
    }

    public function actionTest() {
        $m = Yii::$app->mailer->compose()
            ->setFrom('icetrade.parse@gmail.com')
            ->setTo('dasha.r.00@inbox.ru')
            ->setSubject('Email sent from Yii2-Swiftmailer')
            ->setTextBody("Some Test data from someone.")
            ->send();
    }

    public function actionParse()
    {
        $keyWords = KeyWordsController::getKeyWords();
        $this->auctionsToSend = [];
        $this->auctions = new Auctions();
        $this->checkKeyWords($keyWords);
        $thead = '';
        foreach ($keyWords as $keyWord => $encodedWord) {
            $baseUrl = "http://www.icetrade.by/search/auctions?search_text=$encodedWord&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&p=1&onPage=" . self::SHOW_ITEMS;
            $pageCount = self::getPageCount($baseUrl);

            if (! $pageCount) {
                echo "Continue";
                continue;
            }
            for ($i = 0; $i < $pageCount; $i++) {
                $p = $i+1;
                $currentUrl = "http://www.icetrade.by/search/auctions?search_text=$encodedWord&zakup_type%5B1%5D=1&zakup_type%5B2%5D=1&auc_num=&okrb=&company_title=&establishment=0&industries=&period=&created_from=&created_to=&request_end_from=&request_end_to=&t%5BTrade%5D=1&t%5BeTrade%5D=1&t%5BsocialOrder%5D=1&t%5BsingleSource%5D=1&t%5BAuction%5D=1&t%5BRequest%5D=1&t%5BcontractingTrades%5D=1&t%5Bnegotiations%5D=1&t%5BOther%5D=1&r%5B1%5D=1&r%5B2%5D=2&r%5B7%5D=7&r%5B3%5D=3&r%5B4%5D=4&r%5B6%5D=6&r%5B5%5D=5&sort=num%3Adesc&sbm=1&p=$p&onPage=" . self::SHOW_ITEMS;
                $data = SimpleHTMLDom::file_curl_get_html($currentUrl, 1, 5000);
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
                    var_dump($number);
                    //если номера в бд нет - продолжаем заполнять массив
                    if (! $this->hasNumber($keyWord, $number)){
                        array_push($this->auctionsToSend, ['key_word' => $keyWord, 'number' => $number, 'item' => $item]);
                        //если эл-т и стр последние - то добавить в массив (при условии, что ! $this->hasNumber)
                        //то есть и в бд нет, и эл-т и стр последние (таким образом не надо вручную записывать
                        // номер в бд. При отсутствии номера - он добавится сам)
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
        return ['auctionsToSend' => $this->auctionsToSend, 'thead' => $thead];
    }

    protected function getPageCount($url) {
        $data = SimpleHTMLDom::file_curl_get_html($url, 1, 5000);
        $totalStr = $data->find('.total') ? $data->find('.total')[0]->innerText() : false;
        echo $totalStr;
        if (! $totalStr) return false;
        $total = preg_replace("/[^,.0-9]/", '', $totalStr);

        if((int) $total == 0) {
            return false;
        }

        $pageCount = (int) $total / self::SHOW_ITEMS <= 1 ? 1 : ceil((int) $total / self::SHOW_ITEMS); //округление в большую сторону

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

    function sendMail($body_mail, $auctions, $addition = ''){
        $from = GetMailersController::getMailers()['from'];
        $to = GetMailersController::getMailers()['to'];
        var_dump($to);
        $result = Yii::$app->mailer->compose("$body_mail", ['auctions' => $auctions, 'addition' => $addition])
            ->setFrom("$from")
            ->setTo(array($to[0], $to[1], $to[2], $to[3], $to[4]))
            ->setSubject('Новые тендеры')
            ->send();

        return var_dump($result);
    }
}
