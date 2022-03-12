---
title: Directory Structure
---

# {{ $page->matter('title') }}

When you scaffold a default statix project the directory structure will look similiar to the tree below

```bash
project-name/
├── app
├── builds
├── config/
│   └── site.php
├── public
├── resources
├── routes/
│   └── web.php
├── storage
├── .env.example
├── package.json
├── statix
├── tailwind.config.js
└── webpack.mix.js
```

## The `App` Directory

The app directory contains the core code of your application. We'll explore this directory in more detail soon; however, almost all of the classes in your application will be in this directory.

## The `Config` Directory

The config directory, as the name implies, contains all of your application's configuration files. It's a great idea to read through all of these files and familiarize yourself with all of the options available to you.