### Very Simple Facebook Share/Comment/Like Counts

An extremely simple class for getting Facebook like, share, and comment counts for a URL. By default it returns the sum of all likes, shares, and comments (you want the biggest number possible, right?).

Basic Usage:

```php
use bermanco\FacebookShareCount\FacebookShareCount;

$fb_count = new FacebookShareCount;
$fb_count->get_single_url_count('http://metafilter.com'); // 1512
```


