PHP wrapper for Giantbomb API
==============================

Get your API Key at http://api.giantbomb.com


**Basic usage**
```php
include 'GiantBomb/GiantBomb.php';
$gb_obj = new GiantBomb('YOUR_KEY');
```

**Currently Available Methods**
* game(game_id, field_list)
* games(filter, limit, offset, platform, sort, field_list)
* review(review_id, field_list)
* game_rating(rating_id, field_list)
* company(company_id, field_list)
* character(character_id, field_list)
* search(query, field_list, limit, page, resources)
* genres(field_list, limit, offset)

*All the methods return an object*

Checkout the wiki for more help :- https://github.com/amalfra/GiantBomb/wiki

UNDER MIT LICENSE
=================

Copyright (C) 2013 Amal Francis amalfra@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.



[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/amalfra/giantbomb/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

