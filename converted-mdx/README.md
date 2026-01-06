# Converted MDX Files for Astro

This directory contains all 42 blog posts converted from Jigsaw Markdown format to Astro MDX format.

## Conversion Details

- **Source Format**: Jigsaw Markdown (`.md`) with Blade-style frontmatter
- **Target Format**: Astro MDX (`.mdx`) with standard YAML frontmatter
- **Total Posts Converted**: 42
- **Conversion Date**: 2026-01-06
- **Conversion Tool**: gray-matter (proper YAML parser)

## Frontmatter Changes

### Original (Jigsaw):
```yaml
---
extends: _layouts.post
section: content
title: Article Title
author: Author Name
categories: [category1, category2]
date: 2019-11-08
---
```

### Converted (Astro):
```yaml
---
title: "Article Title"
description: "Description if available"
pubDatetime: 2019-11-08T00:00:00.000Z
author: "Author Name"
tags:
  - category1
  - category2
featured: false
draft: false
---
```

## Key Transformations

1. **Frontmatter**:
   - `categories` → `tags`
   - `date` → `pubDatetime` (ISO 8601 format)
   - Added `description`, `featured`, and `draft` fields
   - Removed Jigsaw-specific fields (`extends`, `section`)
   - Properly handles titles with special characters (colons, quotes)

2. **Content**:
   - Preserved all original content including HTML elements (iframes, etc.)
   - Maintained code blocks and formatting
   - Kept all images and links as-is

3. **File Extension**:
   - Changed from `.md` to `.mdx`

## Usage

These MDX files are ready to be used in an Astro project. To use them:

1. Copy all `.mdx` files to your Astro project's content directory (typically `src/content/blog/`)
2. Ensure your Astro project has MDX support installed
3. Update internal links and image paths as needed
4. Configure redirects for SEO (see `../docs/export-content-guide.md`)

## Conversion Script

The conversion was performed using `convert-to-mdx.js` script which:
- Uses `gray-matter` for robust YAML parsing
- Transforms frontmatter to Astro-compatible format
- Preserves all content and formatting
- Generates valid MDX files
- Handles edge cases (titles with colons, special characters)

## Next Steps

1. Review converted files for any content-specific issues
2. Update internal links to point to new URLs
3. Verify image paths and update if necessary
4. Set up URL redirects for SEO
5. Configure Astro content collections
6. Test builds and preview site

For detailed migration instructions, see: `../docs/export-content-guide.md`
