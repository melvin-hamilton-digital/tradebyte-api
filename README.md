# Tradebyte API

[![build](https://img.shields.io/github/workflow/status/melvin-hamilton-digital/tradebyte-api/PHP%20Composer)](https://github.com/melvin-hamilton-digital/tradebyte-api/actions)

## API documentation

https://www.tradebyte.io/documentation/

## Current features

* order data exchange,
* stock data exchange,
* order messages,
* product and article csv feed generation.

## Example usage

```php
use GuzzleHttp\Client as HttpClient;
use MHD\Tradebyte\Api\Client as TradebyteClient;
use MHD\Tradebyte\Data\Message;
use MHD\Tradebyte\Data\MessagesList;
use MHD\Tradebyte\Data\OrdersList;
use MHD\Tradebyte\Data\Stock;

$httpClient = new HttpClient([
    'auth' => ['username', 'password'],
    'base_uri' => "http://rest.trade-server.net/{$yourAccountId}/",
]);
$tradebyteClient = new TradebyteClient($httpClient);

# process orders
$response = $orders$tradebyteClient->getOrders('chxx');
foreach (new OrdersList($response->getBody()->getContents()) as $order) {
# ...
}

# send messages
$message = new Message();
$message->setType(Message::TYPE_NO_INVENTORY);
$message->setSku('12345678');

$messagesList = new MessagesList();
$messagesList->addMessage($message);

$tradebyteClient->sendMessages($messagesList);

# send stock
$stock = new Stock();
$stock->addArticle('12345678', 0);

$tradebyteClient->sendStock($stock);
```

## Product CSV feed

Library provides [`MHD\Tradebyte\Data\Csv\Product`](./src/Data/Csv/Product.php) class which is meant to be used
with `symfony/serializer` in order to simplify and streamline Tradebyte product CSV feed generation.

```php
use MHD\Tradebyte\Data\Csv\Product;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Serializer;

$product = new Product();
$product->id = '1234';
$product->brand = 'T-Shirts inc.';
$product->name = 'Blue T-Shirt';
$product->description = 'It\'s a t-shirt';
$product->addComponent('color', 'blue');

$serializer = new Serializer([new CustomNormalizer()], [new CsvEncoder()]);

echo $serializer->serialize([$product], 'csv');
# p_nr,p_brand,p_name,p_text,p_keywords,p_comp[color]
# 1234,"T-Shirts inc.","Blue T-Shirt","It's a t-shirt",,blue

```

## Article CSV feed

Library provides [`MHD\Tradebyte\Data\Csv\Artilce`](./src/Data/Csv/Article.php) class which is meant to be used
with `symfony/serializer` in order to simplify and streamline Tradebyte article CSV feed generation.

```php
use MHD\Tradebyte\Data\Csv\Article;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Serializer;

$article = new Article();
$article->productId = '1234';
$article->articleId = '12345678';
$article->stock = '10';
$article->unit = Article::UNIT_PIECE;
$article->addRetailPrice('chxx', 24.99);
$article->addVatRate('chxx', Article::VAT_RATE_NORMAL);

$serializer = new Serializer([new CustomNormalizer()], [new CsvEncoder()]);

echo $serializer->serialize([$article], 'csv');
# p_nr,a_nr,a_stock,a_unit,a_weight,a_vk[chxx],a_mwst[chxx]
# 1234,12345678,10,ST,,24.99,1

```
