---
extends: _layouts.post
section: content
title: Mendeteksi Ajax Request
author: Bayu Hendra Winata
categories: [ajax, request]
date: 2013-10-19
---



```php
if (Request::wantsJson())
{
    // your beautiful code here...
}

// atau

if (Request::ajax())
{
    // your beautiful code here...
}
```
