---
extends: _layouts.post
section: content
title: Menangani Error 404
author: Bayu Hendra Winata
categories: [laravel]
date: 2013-10-22
---

> Ini adalah kode untuk Laravel 4.x


```php
App::error(function($exception, $code)
{
	return 'error ' . $code ;
});
```

```php
// Jika ingin men-trigger HTTP error secara manual, panggil kode di bawah dari manapun
App::abort(404); // berlaku juga untuk error HTTP yang lain, misalnya 403, 500
```
