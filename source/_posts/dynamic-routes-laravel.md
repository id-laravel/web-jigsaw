---
extends: _layouts.post
section: content
title: "Membuat Dynamic Routes di Laravel"
author: uyab
categories: [php, laravel]
date: 2023-03-07
---

## Introduksi
Dalam salah satu proyek yang pernah kami kerjakan, ada kebutuhan dimana admin bisa mendaftarkan endpoint API (route) secara dinamis. 
Endpoint tersebut akan mengembalikan data sesuai SQL query yang didaftarkan oleh admin.


## Cara 1: Daftarkan Semua Routes
```php
$customRoutes = DB::table('endpoint_apis')->get();
foreach ($customRoutes as $row) {
    $method = $row->method;
    Route::$method($row->endpoint, 'App\Http\Controllers\Api\DynamicRouteHandler');
}
```

### Kelebihan
- Setiap route terdaftar secara eksplisit, akan muncul di `route:list`.

### Kekurangan
- Ada potensi isu ketika menjalankan `route:cache` dengan setup server yang multi instance. Penambah route di salah satu instance tidak akan terdeteksi di instance lain.

## Cara 2: Daftarkan Satu Route Saja
```php
Route::any('{any}', 'App\Http\Controllers\Api\DynamicRouteHandler')->where('any', '.*');
```

Bagian `where('any', '.*')` merupakan salah satu poin penting dari kode di atas, karena regex tersebut memungkinkan dynamic route untuk memiliki format nested:
- user
- user/active
- organization/{org}/user/active/

Cara kedua ini bisa dioptimasi lagi, misalkan dengan menambah prefix khusus untuk semua dynamic route:
```php
Route::any('custom/{any}', 'App\Http\Controllers\Api\DynamicRouteHandler')->where('any', '.*');
```


### Kelebihan
- Tidak terpengaruh dengan `route:cache` maupun setup server yang multi instance.

### Kekurangan
- Hanya satu route yang terdeteksi di `route:list`.
- Pengecekan method `GET` atau `POST` perlu dilakukan secara manual di DynamicRouteHandler.
