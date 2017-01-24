# Squire

A guide for your guide.

[![Build Status](https://travis-ci.org/sixteenbit/squire.svg?branch=master)](https://travis-ci.org/sixteenbit/squire)

## Getting Started

Make sure you have the following installed:

* [Node.js](https://nodejs.org/)
* [Grunt](http://gruntjs.com/)
* [Bower](http://bower.io)
* [Sass](http://sass-lang.com/)

In the root of your project, run the following:

`npm i && bower i && grunt setup`

Then run `grunt` to build the project.

## Development

In your wp-content/themes folder you will now have a folder with the name of your theme which is setup with the basics to get a theme off the ground quickly. In the root of your newly created theme you'll have the following grunt tasks you can run:

    grunt # runs the default task that builds the assets
    grunt server # initiates Browsersync and watches files for changes
    
### Sass

Global variables are located in `/assets/sass/abstracts/_foundation-vars.scss`

### Javascript

All files in `/assets/js/src/` are concatenated into the `/assets/js/` directory.

## Production

When you're done and ready to go live you'll need to minify your js and whatnot. You can do this by using:

    grunt build
    
This will minify all your assets and copy the theme to a dist/ directory then compresses to a .zip.

## Shortcodes

### Button

`[button style="" url="http://example.com"]A Button[/button]`

### Color Block

`[color-block color="0069ff"]`

## Multi Page template

Squire has the option to load child pages into the parent page along with an on page navigation. Simply select the "Multi Page" template and add child pages to the parent.
