# Website id-laravel.com

## Installation

- Clone repository
- Run `composer install`
- Run `npm install`
- Run `npm run watch`
- Read https://jigsaw.tighten.co/docs/building-and-previewing/ for further information about Jigsaw

## Adding Content

> You must have access to push to this repository, or fork and submit a Pull Request.

Create new Markdown file in `source/_posts/article-slug.md` with following template:
```markdown
---
extends: _layouts.post
section: content
title: <The Title>
author: <Your Name>
categories: [category1, category2]
date: 2019-11-08
---

Your article content goes here. No need to add title, just write your article body.
    
```
