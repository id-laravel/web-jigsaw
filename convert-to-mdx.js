#!/usr/bin/env node

/**
 * Script to convert Jigsaw markdown posts to Astro MDX format
 * Based on the export guide in docs/export-content-guide.md
 * 
 * Dependencies: 
 *   - gray-matter (install with: npm install --no-save gray-matter --legacy-peer-deps)
 * 
 * Usage: node convert-to-mdx.js
 */

const fs = require('fs');
const path = require('path');

// Try to load gray-matter, provide helpful error if not available
let matter;
try {
  matter = require('gray-matter');
} catch (e) {
  console.error('❌ Error: gray-matter is not installed');
  console.error('   Install it with: npm install --no-save gray-matter --legacy-peer-deps');
  process.exit(1);
}

// Convert Jigsaw frontmatter to Astro format
function convertFrontmatter(data) {
  const astroFrontmatter = {
    title: data.title || 'Untitled',
    description: data.description || '',
    pubDatetime: data.date ? new Date(data.date).toISOString() : new Date().toISOString(),
    author: data.author || 'ID Laravel',
    tags: Array.isArray(data.categories) ? data.categories : [],
    featured: false,
    draft: false
  };
  
  return astroFrontmatter;
}

// Convert content from Markdown to MDX-compatible format
function convertContent(content) {
  // Currently, no content transformations are needed
  // The original content works well in MDX format
  // Future transformations could include:
  // - Converting HTML iframes to MDX components
  // - Updating image paths
  // - Converting internal links
  
  return content;
}

// Generate MDX frontmatter as YAML
function generateMDXFrontmatter(data) {
  let yaml = '---\n';
  yaml += `title: "${data.title.replace(/"/g, '\\"')}"\n`;
  yaml += `description: "${data.description.replace(/"/g, '\\"')}"\n`;
  yaml += `pubDatetime: ${data.pubDatetime}\n`;
  yaml += `author: "${data.author}"\n`;
  yaml += `tags:\n`;
  data.tags.forEach(tag => {
    yaml += `  - ${tag}\n`;
  });
  yaml += `featured: ${data.featured}\n`;
  yaml += `draft: ${data.draft}\n`;
  yaml += '---\n\n';
  return yaml;
}

// Main conversion function
function convertPost(inputPath, outputPath) {
  try {
    const content = fs.readFileSync(inputPath, 'utf8');
    const { data, content: body } = matter(content);
    
    // Convert frontmatter
    const astroData = convertFrontmatter(data);
    
    // Convert content
    const convertedBody = convertContent(body);
    
    // Generate MDX file
    const mdxFrontmatter = generateMDXFrontmatter(astroData);
    const mdxContent = mdxFrontmatter + convertedBody;
    
    // Write to output
    const outputFilename = path.basename(inputPath).replace('.md', '.mdx');
    const fullOutputPath = path.join(outputPath, outputFilename);
    fs.writeFileSync(fullOutputPath, mdxContent);
    
    console.log(`✓ Converted: ${path.basename(inputPath)} -> ${outputFilename}`);
    return true;
  } catch (error) {
    console.error(`✗ Error converting ${inputPath}:`, error.message);
    return false;
  }
}

// Process all posts
function main() {
  // Source: Jigsaw posts directory
  const sourceDir = './source/_posts';
  // Target: Converted MDX files directory
  const targetDir = './converted-mdx';
  
  // Verify source directory exists
  if (!fs.existsSync(sourceDir)) {
    console.error(`❌ Error: Source directory not found: ${sourceDir}`);
    console.error('   Make sure you are running this script from the repository root');
    process.exit(1);
  }
  
  if (!fs.existsSync(targetDir)) {
    fs.mkdirSync(targetDir, { recursive: true });
  }
  
  const files = fs.readdirSync(sourceDir);
  let successCount = 0;
  let errorCount = 0;
  
  console.log(`\nConverting ${files.length} posts from Jigsaw to Astro MDX format...\n`);
  
  files.forEach(file => {
    if (file.endsWith('.md')) {
      const inputPath = path.join(sourceDir, file);
      const success = convertPost(inputPath, targetDir);
      if (success) {
        successCount++;
      } else {
        errorCount++;
      }
    }
  });
  
  console.log(`\n✅ Conversion complete!`);
  console.log(`   - Successfully converted: ${successCount} posts`);
  console.log(`   - Errors: ${errorCount} posts`);
  console.log(`   - Output directory: ${targetDir}\n`);
}

main();
