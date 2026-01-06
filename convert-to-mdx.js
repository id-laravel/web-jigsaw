#!/usr/bin/env node

/**
 * Script to convert Jigsaw markdown posts to Astro MDX format
 * Based on the export guide in docs/export-content-guide.md
 */

const fs = require('fs');
const path = require('path');

// Parse frontmatter manually (simple YAML parser)
function parseFrontmatter(content) {
  const match = content.match(/^---\n([\s\S]*?)\n---\n([\s\S]*)$/);
  if (!match) {
    return { data: {}, content: content };
  }

  const frontmatter = match[1];
  const body = match[2];
  
  const data = {};
  const lines = frontmatter.split('\n');
  
  for (let line of lines) {
    if (line.includes(':')) {
      const colonIndex = line.indexOf(':');
      const key = line.substring(0, colonIndex).trim();
      let value = line.substring(colonIndex + 1).trim();
      
      // Handle arrays
      if (value.startsWith('[') && value.endsWith(']')) {
        value = value.slice(1, -1).split(',').map(v => v.trim());
      }
      
      data[key] = value;
    }
  }
  
  return { data, content: body };
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
  let converted = content;
  
  // Convert HTML iframe to MDX component (if needed)
  // For now, keep iframes as-is since they work in MDX
  
  // Ensure code blocks have proper language identifiers
  // Add any other content transformations here
  
  return converted;
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
    const { data, content: body } = parseFrontmatter(content);
    
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
  const sourceDir = './source/_posts';
  const targetDir = './converted-mdx';
  
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
