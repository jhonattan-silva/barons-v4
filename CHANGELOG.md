# CHANGELOG - Barons Theme

All notable changes to this project will be documented in this file.

## [3.0.1] - 2026-02-23

### 🚀 Added
- CSS Variables (Custom Properties) para design system consistente
- Performance optimization file (inc/performance.php)
- SEO optimization file (inc/seo.php)
- Security enhancement file (inc/security.php)
- Open Graph meta tags para redes sociais
- Twitter Cards support
- Schema.org JSON-LD markup (Article, BreadcrumbList)
- Lazy loading automático de imagens
- Smooth scrolling para navegação
- Keyboard navigation detection
- Widget areas support
- Custom logo support
- Nav menu locations (primary, footer)
- HTML5 support
- Security headers (X-Content-Type-Options, X-Frame-Options, etc)
- Brute force login protection
- Async/Defer attributes para scripts
- Preload de recursos críticos
- Comprehensive README.md documentation

### ⬆️ Updated
- Bootstrap: 5.0.1 → 5.3.3 (última versão estável)
- Font Awesome: 5.12.0-2 → 6.5.1 (nova geração)
- jQuery: removido arquivo local obsoleto 1.10.0, usando versão nativa do WordPress
- Facebook SDK: v12.0 → v18.0
- header.php: implementado wp_head() adequadamente sem hardcoded libraries
- footer.php: melhorada acessibilidade e SEO
- functions.php: modernizado com best practices WordPress
- style.css: CSS moderno com variables, clamp(), e modern reset
- meus_scripts.js: corrigido encoding, modernizado sintaxe, adicionado features

### 🔧 Fixed
- Encoding de caracteres no JavaScript (removido caracteres coreanos)
- Duplicação de CSS loading no header
- Schema.org URLs: http → https
- Código duplicado em múltiplos lugares
- Missing alt attributes em imagens
- Missing ARIA labels em navegação
- Focus styles para acessibilidade
- Problemas de validação HTML

### 🗑️ Removed
- Carregamento direto de Bootstrap via CDN no header (movido para wp_enqueue)
- Carregamento direto de Font Awesome no header (movido para wp_enqueue)
- Duplicação de stylesheet loading
- Código obsoleto e não utilizado
- jQuery 1.10.0 reference (mantido arquivo por precaução)
- Meta tags redundantes
- WordPress version exposure
- Unnecessary wp_head() bloat (emojis, embeds)

### 🔒 Security
- Implementado security headers
- Adicionado proteção contra brute force
- Removido informações de versão do WordPress
- Desabilitado XML-RPC
- Adicionado file upload sanitization
- Implementado DISALLOW_FILE_EDIT
- Ocultado erros de login
- Removido RSD e WLW manifest links

### ⚡ Performance
- Implementado lazy loading de imagens
- Removido query strings de assets
- Desabilitado WordPress emojis
- Desabilitado WordPress embeds
- Adicionado preload de recursos críticos
- Implementado defer para CSS não crítico
- Adicionado async para JavaScript não crítico
- Limitado revisões de posts (3)
- Aumentado intervalo de autosave (5 min)
- Removido REST API links desnecessários

### 🎨 Styling
- Adicionado CSS variables para sistema de design
- Implementado clamp() para tipografia responsiva
- Melhorado mobile menu com backdrop-filter
- Adicionado transitions suaves
- Implementado will-change para performance
- Adicionado focus styles para navegação por teclado
- Melhorado responsividade geral

### ♿ Accessibility
- Adicionado role attributes apropriados
- Implementado ARIA labels
- Melhorado navegação por teclado
- Adicionado skip links structure
- Implementado focus indicators
- Melhorado semantic HTML
- Adicionado rel="noopener noreferrer" em links externos
- Implementado alt text em todas as imagens

### 🔍 SEO
- Adicionado Open Graph meta tags
- Implementado Twitter Cards
- Adicionado Schema.org structured data
- Implementado canonical URLs
- Adicionado meta descriptions dinâmicas
- Implementado robots meta tags
- Melhorado title tags
- Adicionado sitemap preparation

### 📝 Documentation
- Criado README.md completo
- Adicionado CHANGELOG.md
- Melhorado comentários no código
- Adicionado DocBlocks
- Documentado CSS variables
- Documentado estrutura de arquivos

### 🏗️ Code Quality
- Organizado código em arquivos separados (inc/)
- Implementado WordPress coding standards
- Adicionado 'use strict' no JavaScript
- Melhorado estrutura de funções
- Adicionado security checks (ABSPATH)
- Implementado proper escaping
- Melhorado sanitization

---

## [3.0.0] - Anterior

### Initial Features
- Sistema de categorias customizadas (Games, Filmes, Séries, HQs, Livros)
- Carrossel de destaque
- Sidebar com posts aleatórios
- Integração com Facebook
- Sistema de subtítulos customizados
- Featured images automáticas
- Paginação
- Comentários
- Search functionality
- Responsive design básico

---

## Formato

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### Tipos de Mudanças
- **Added**: Novos recursos
- **Changed**: Mudanças em recursos existentes
- **Deprecated**: Recursos que serão removidos
- **Removed**: Recursos removidos
- **Fixed**: Correções de bugs
- **Security**: Correções de segurança
