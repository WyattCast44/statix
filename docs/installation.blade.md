---
title: Installation
next: {{ route('docs.configuration') }}
previous: {{ route('docs.quickstart') }}
---

## System Requirements

Statix requires you to have PHP 8.0+ and composer installed on your system to get started. You may also need NodeJS installed to build assets in one of our several starter kits.

# Installation

Learn how to install Statix in one of several ways. 

1. Using the statix installer
3. Using composer create project 
2. Cloning the statix/statix repository on Github

# Using the Official Installer

We recommend creating your Statix app using the official [Statix installer](https://#), which makes installing and configuring Statix applications a breeze. To install the official installer, run the following command

```bash
composer global require statix/installer
```

Once you have installed the installer, you can create your new project by running the following command

```
statix new [app]
```

Where `[app]` is the name of your project. Additionally you can indicate to the installer that you would like to install a starter kit you can pass the `--kit` flag and pass the name of the kit to installed

```bash
statix new [app] --kit="blog:tailwindcss"
```

# Using Composer

Todo 

# Cloning the repo on GitHub

Todo 

# Related Pages

- todo