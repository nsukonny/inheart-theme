# WORDPRESS STARTER THEME.
## Gulp4, SCSS, JavaScript ES6+, WEBP.

## Please run all commands from the current theme folder.

### 1. Change the value of the pathRoot to your local domain name in
`gulpfile.babel.js/config/path.js`

### 2. Install dependencies:
`npm i`

### 3. Start development:
`npm start`

### 4. Production build:
`npm run build`

### 5. You are awesome! Happy coding! ;-)
<hr />

## Where to place SCSS / JS / images / fonts source files?
Nice question! Inside your `src` directory.
<hr />

## Where are processed files located?
Inside your `static` directory.<br />
It will appear after starting development or after running production build.
<hr />

## Features:

### 1. Styles

<br />

#### 1.1. `SCSS` support.

<br />

#### 1.2. Autoprefix.

<br />

#### 1.3. If `background-url` points to a local image that has its `WEBP` version - it will be replaced.

<br />

#### 1.4. Gulp4 default sourcemaps added.

<br />

#### 1.5. Put styles for specific pages in `src/scss/pages`.<br />
Processed files will appear in `static/css/pages`.

<br />

#### 1.6. Gulp-shorthand will replace all CSS-properties with their shorter one-line entry, if possible.<br />
Note: Works on build only.

<br />

#### 1.7. All CSS media queries with the same rules will be grouped.<br />
Note: Works on build only.

<hr />
<br />

### 2. JavaScript

<br />

#### 2.1. ES6+ with `import` / `export` support.

<br />

#### 2.2. Gulp4 default sourcemaps added.

<br />

#### 2.3. Put scripts for specific pages in `src/js/pages`.<br />
Processed `<file_name>.min.js` files will appear in `static/js/<file_name>/`.

<hr />
<br />

### 3. Local Images

<br />

#### 3.1. Minification of `PNG`, `JPG`, `JPEG`, `GIF`, `SVG`.
Please place them inside your `src/img` directory and then include them into your code from your `static/img/` directory.

<br />


#### 3.2. Also creates `WEBP` version of the image if possible.

<hr />
<br />

### 4. Fonts
Please put your fonts into `src/fonts` folder.

<br />

#### 4.1. Fonts will be automatically moved.
You should use your fonts from `static/fonts` directory.

<hr />
<br />

### 5. Errors
Pop-up system notifications are added - now you will not waste your time searching for errors.

<hr />
<br />

### 6. Cleaning static/ directory

<br />

#### 6.1. Every time you will run development or production build, the following subdirectories of your `static/` directory will be cleared:
`js` | `css` | `img` | `fonts`

<br />

#### 6.2. Any other subdirectories will not be affected, so feel free to add everything you need to your `static/` directory. :relaxed:

<hr />
<br />

### 7. Flexible Content Sections

<br />

#### 7.1. Create Flexible Content field with flexible_content name (slug) - it's required!
Add any sections to your flexible_content.

<br />

#### 7.2. Go to the page you need and set its template to ACF Flexible Content.

<br />

#### 7.3. Add any Flexible Content sections and fill the fields. Save page.

<br />

#### 7.4. Now you can see generated PHP, JS and SCSS files in specific directory:
`acf-flexible-content/<your_section_name>`<br />
Note, that these files will be generated only when you'll add specific Flexible Content section to your page which has ACF Flexible Content template and then save it.

<br />

#### 7.5. After running `npm start` or `npm run build` CSS & JS files will appear in
`static/<css_or_js>/<your_section_name>`<br />
CSS & JS files are already included for every generated section template. You don't need to include them manually.

<hr />
<br />

Good luck! :muscle:
