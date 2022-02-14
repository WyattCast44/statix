---
title: Quickstart
---

# TOC

- Install Statix
- Install starter kit
- Create a new build of your site
- Serve your site and view it in the browser

## Install Statix

Create the project

```bash
composer create-project statix/statix my-site
```

Move into directory

```bash
cd my-site
```

## Install starter kit

To install one of our starter kits, run the following command and replace `[kit-name]` with the name of the kit

```bash
php statix install:starter [kit-name]
```

For example, we will install the `tailwind:blog` starter kit, which is a simple blogging site built with TailwindCSS

```bash
php statix install:starter tailwind:blog
```

## Create Build

To create the first build of your site run the following command

```bash
php statix build
```

This command will create a static build of your site in the `builds` directory, and by default it will be created in a directory called `local`

## Serve your site

To serve your site using our builtin PHP server, run the following command

```bash
php statix serve
```