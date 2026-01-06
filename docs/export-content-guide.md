# Panduan Ekspor Konten dari Situs Lama

Dokumen ini menjelaskan cara mengekspor konten dari situs id-laravel.com yang lama ke blog baru berbasis Astro.

## Persiapan

1. **Backup Data Lama**
   - Backup database situs lama
   - Backup semua file media (gambar, asset, dll)
   - Dokumentasikan struktur URL lama untuk redirect

2. **Inventarisasi Konten**
   - Buat daftar semua artikel yang akan dipindahkan
   - Identifikasi artikel yang masih relevan
   - Catat metadata penting (tanggal, author, tags, dll)

## Metode Ekspor

### Dari WordPress

Jika situs lama menggunakan WordPress:

```bash
# 1. Export melalui WordPress Admin
# Tools > Export > Posts > Download Export File
# Hasil: file XML dengan semua konten

# 2. Convert XML ke Markdown
# Gunakan tool seperti wordpress-export-to-markdown
npx wordpress-export-to-markdown
```

### Dari Database Manual

Jika perlu export langsung dari database:

```sql
-- Export artikel
SELECT 
  id,
  post_title as title,
  post_content as content,
  post_date as pubDate,
  post_modified as modDate,
  post_status as status
FROM wp_posts 
WHERE post_type = 'post' 
  AND post_status = 'publish'
ORDER BY post_date DESC;

-- Export tags/categories
SELECT 
  p.id,
  t.name as tag,
  t.slug
FROM wp_posts p
JOIN wp_term_relationships tr ON p.ID = tr.object_id
JOIN wp_terms t ON tr.term_taxonomy_id = t.term_id
WHERE p.post_type = 'post';
```

### Dari HTML/Static Site

Jika situs lama adalah HTML statis:

```bash
# Gunakan tool scraping seperti wget atau httrack
wget --recursive --no-parent --convert-links https://old-site.com/blog/

# Atau manual copy-paste konten ke format markdown
```

## Konversi ke Format Astro

### 1. Format Frontmatter

Setiap artikel harus memiliki frontmatter YAML di awal file:

```markdown
---
title: "Judul Artikel"
description: "Deskripsi singkat artikel"
pubDatetime: 2025-01-15T10:00:00.000Z
modDatetime: 2025-01-20T15:30:00.000Z
author: "Nama Penulis"
tags:
  - laravel
  - php
  - tutorial
featured: false
draft: false
---

Konten artikel dimulai di sini...
```

### 2. Konversi Konten

**A. Code Blocks**

Konversi syntax highlighting:

```markdown
<!-- Format lama -->
<pre><code class="language-php">
<?php echo "Hello"; ?>
</code></pre>

<!-- Format baru -->
```php
<?php echo "Hello"; ?>
```
```

**B. Images**

```markdown
<!-- Format lama -->
<img src="/uploads/2025/01/image.jpg" alt="Description">

<!-- Format baru -->
![Description](/assets/images/2025/01/image.jpg)
```

**C. Internal Links**

Update semua internal link:

```markdown
<!-- Format lama -->
<a href="https://old-site.com/artikel-1">Link</a>

<!-- Format baru -->
[Link](/posts/artikel-1)
```

### 3. Script Konversi Batch

Contoh script untuk batch convert (Node.js):

```javascript
// convert-posts.js
const fs = require('fs');
const path = require('path');
const matter = require('gray-matter');

const sourceDir = './export-data';
const targetDir = './src/data/blog';

fs.readdirSync(sourceDir).forEach(file => {
  if (!file.endsWith('.md')) return;
  
  const content = fs.readFileSync(path.join(sourceDir, file), 'utf8');
  const { data, content: body } = matter(content);
  
  // Transform frontmatter
  const newFrontmatter = {
    title: data.title || 'Untitled',
    description: data.excerpt || data.description || '',
    pubDatetime: new Date(data.date || Date.now()),
    author: data.author || 'ID Laravel',
    tags: data.tags || [],
    featured: false,
    draft: false
  };
  
  // Transform content
  let newBody = body
    .replace(/https:\/\/old-site\.com/g, '')
    .replace(/<img src="([^"]+)" alt="([^"]*)">/g, '![$2]($1)');
  
  // Write new file
  const newContent = matter.stringify(newBody, newFrontmatter);
  fs.writeFileSync(path.join(targetDir, file), newContent);
});

console.log('Conversion complete!');
```

Jalankan:

```bash
npm install gray-matter
node convert-posts.js
```

## Migrasi Asset

### 1. Images

```bash
# Copy semua gambar ke folder assets
cp -r /path/to/old-site/images/* public/assets/images/

# Atau untuk optimasi:
# Gunakan sharp atau imagemagick untuk resize/compress
```

### 2. Update Image References

```bash
# Cari dan ganti semua referensi gambar
find src/data/blog -name "*.md" -exec sed -i 's|/old-path/|/assets/|g' {} +
```

## Setup Redirects

Untuk mempertahankan SEO, buat file redirect:

**Untuk Netlify/Cloudflare Pages** (`public/_redirects`):

```text
/old-url-1  /new-url-1  301
/old-url-2  /new-url-2  301
```

**Untuk Vercel** (`vercel.json`):

```json
{
  "redirects": [
    { "source": "/old-url-1", "destination": "/new-url-1", "permanent": true }
  ]
}
```

## Validasi Post-Migration

### Checklist

- [ ] Semua artikel berhasil dimigrasi
- [ ] Semua gambar dapat diakses
- [ ] Syntax highlighting berfungsi
- [ ] Internal links bekerja
- [ ] Tags dan categories sudah tepat
- [ ] Metadata (tanggal, author) akurat
- [ ] URL redirects berfungsi
- [ ] Sitemap terupdate
- [ ] RSS feed berfungsi

### Testing

```bash
# Build site untuk memastikan tidak ada error
pnpm run build

# Jalankan di local untuk review
pnpm run dev

# Check broken links
npx broken-link-checker http://localhost:4321
```

## Tips

1. **Incremental Migration**: Migrate konten secara bertahap, mulai dari artikel terpopuler
2. **Preserve URLs**: Gunakan slug yang sama untuk SEO
3. **Update Dates**: Pastikan timezone konsisten (Asia/Jakarta)
4. **Test Thoroughly**: Review setiap artikel setelah konversi
5. **Backup**: Selalu simpan backup data original

## Troubleshooting

### Masalah Umum

**Q: Format tanggal tidak sesuai**
```javascript
// Konversi ke ISO format
const date = new Date('2025-01-15');
const isoDate = date.toISOString(); // 2025-01-15T00:00:00.000Z
```

**Q: Karakter Indonesia rusak**
```bash
# Pastikan encoding UTF-8
iconv -f ISO-8859-1 -t UTF-8 input.md > output.md
```

**Q: Code blocks tidak ter-highlight**

Pastikan language identifier benar menggunakan huruf kecil:

```php
// Benar: gunakan huruf kecil
```

Bukan:

```text
// Salah: ```PHP atau ```Php
```

## Resources

- [Gray Matter](https://github.com/jonschlinkert/gray-matter) - YAML frontmatter parser
- [Turndown](https://github.com/mixmark-io/turndown) - HTML to Markdown converter
- [WordPress to Markdown](https://github.com/lonekorean/wordpress-export-to-markdown) - WordPress exporter
