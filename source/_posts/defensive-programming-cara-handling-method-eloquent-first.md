---
extends: _layouts.post
section: content
title: "Cara Memanggil Method Eloquent first() Agar Tidak Terkena Null Pointer Exception"
author: uyab
categories: [php, laravel]
date: 2023-03-08
---

## Masalah
Salah satu method Eloquent yang sering digunakan adalah `first()`. Sesuai yang terdapat dalam [dokumentasi resminya](https://laravel.com/docs/10.x/eloquent#retrieving-single-models), 
`first()` digunakan untuk mengambil satu record dari database.

```php
$user = User::where('email', 'foo@example.com')->first();
$fullname = $user->first_name . ' ' . $user->last_name;
```

Yang sering dilupakan oleh para programmer, method `first()` di atas bisa jadi mengembalikan `null`, ketika tidak ada data di database sesuai dengan kondisi yang diberikan.
Hasilnya, akan muncul error sejuta umat `Trying to get property of non object` ketika mencoba mengakase properti `$user->first_name`.

## Solusi

Ada beberapa cara agar kode kita tidak error ketika data tidak ditemukan (method `first()` mengembalikan null). 
Beberapa diantaranya:

1. Selalu tambahkan **Guard Clause**
2. Gunakan `firstOrFail()`

### Tambahkan _Guard Clause_
_Guard Clause_ adalah kode yang digunakan untuk memastikan suatu prekondisi terpenuhi sebelum bisa melanjutkan ke baris kode berikutnya.

```php
$user = User::where('email', 'foo@example.com')->first();
if ($user) {
    $fullname = $user->first_name . ' ' . $user->last_name;
} else {
    $fullname = '-';
}
```

Atau menggunakan _early return_ untuk menambah _readability_ kode:
```php
$user = User::where('email', 'foo@example.com')->first();
if (!$user) {
    return '-'
}

return $user->first_name . ' ' . $user->last_name;
```

### Ganti Dengan `firstOrFail()`
Jika `first()` dipanggil dari Controller dan kondisinya merupakan input dari user, kita bisa menggantinya dengan `firstOrfail`.

Contoh, user mencoba mengakses halaman `http://localhost/user?email=invalid@example.com`:
```php
$user = User::where('email', request('email'))->firstOrFail();

// Kode di bawah ini tidak akan dieksekusi jika email tidak ditemukan, halaman akan menampilkan 404
$fullname = $user->first_name . ' ' . $user->last_name;
```

Referensi: https://laravel.com/docs/10.x/eloquent#not-found-exceptions
