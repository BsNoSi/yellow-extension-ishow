# Yellow Extension ishow

Version 1.4.1

> Tested with core version 0.8.23

## Application

CSS image slider with optional link to pages. 

Instead of huge libraries for *simply pushing images* this extension only uses CSS for this to saves bandwidth because only a fraction of code is required. For support of optional links (in this case) a small Script is added to the page.


## Install

1. Download and install [Datenstrom Yellow CMS](https://github.com/datenstrom/yellow/).
2. Download [ishow extension](https://github.com/BsNoSi/yellow-extension-ishow/archive/master.zip). If you are using Safari, right click and select 'Download file as'.
3. Copy the `yellow-extension-ishow-master.zip` into the `system/extension` folder.

To uninstall simply delete the [extension files](https://github.com/BsNoSi/yellow-extension-ishow/blob/master/extension.ini).

## Usage

**Limitation**: You can use only one "ishow" per page.

**Requries**: An entry `ishow:` in header area, *if linking shall be supported*.

```
---
title: …
…
ishow: yes | 1 | … – the entry as such is required
…
```
This entry adds the required Javascript to the page.


     [ishow "images" "display" "swipe" "show" "path" "frame"]

| Parameter | Function |
| :---: | :--- |
| images | *required* : The folder of the displayed images.<br/>Use a filter i.e. `.*svg` to show *all* SVG-files in this path.<br/> `images` has to be relative to coreImageDir.<br/>**The first image defines the height dimension of the presentation.**<br/> Therefore, the "highest" should be the first in the queue. To get a good looking presentation, images should have a similar sizing. |
| display | *optional, default 4sec* : The display time of a single image. Can be a fraction of seconds like `1.5`. |
| swipe | *optional, default 1 sec* : The blending time between two images.<br/>`swipe` has high impact to attraction of presentation. Good values are fractions of a second, for instance »0.5«. To fast can destroy the effect, to slow can make it boring. |
| show | *optional*, default is "show", see available show types below |
| path | *optional*, default  is "".<br/>A given path adds a link to every image of the show to a page in this path with the name of the clicked image. |
| frame | *optional*, default "".<br/>Content as such is not releavant, can be anything, preferably an "x".<br/>The extension contains a "standard frame definition"<br/>`border: solid rgb(230,230,230) 1px; border-radius:6px;`|

### Available show types

| Parameter | Effect |
| :---: | :--- |
| show | (default): Images appear and disappear centered |
|left | Images slide from right to left. |
| right |Images slide from left to rihgt |
| up | Images slide from button to top |
| down | Images slide from top to bottom |
| pump | Images grow and shrink centered. |
| zoom | Images zoom in and out centered. |
| fly | Images fly left in right out. |

> All shows are running in an infinite loop. Depending on the height of the first image all following are centered horizontally and vertically in the available area. Although this compensates differences in size, they should be small for a homogeneous presentation.

### Link Targets

Linking is applied with  Javascript. If no Javascript is available, the cursor nevertheless chances to pointer style. If that bothers you – let me know.

- `/path/` Clicking an image leads to `root/path/image-file-name`. 
- `path/` Clicking an images leads to `{current document path}/path/image-file-name`. 

`image-file-name` is except extender: `/media/image/fortheshow/highlights.png` → `/path/highlight`

> **Make sure that targets are valid.** Otherwise, Yellow will create it's standard »Oh no!« message. See *How to organize links* below.

### Examples

> Running demos of all available shows are available here [https://buoa.de/wiki/showtime#ret] with a list. It is a German speaking page but to see the demos you can simply go down the list to open a demo and it's parameters.

`[ishow company/.*svg 3 0.3 show /divisions/]`

- Replaces each SVG files available in `root/media/images/company/` every 3 seconds with 0.3 second blending time. Clicking an image leads to `root/divisions/image file name`.

`[ishow company/ 5 1 up divisions/]`

- Scrolls left *any* image file available in `root/media/images/company/` every 5 seconds with 1 second blending time. Clicking an image leads to `current document path/divisions/image file name`.

`[ishow company/.*png 2 0.4 fly divisions/]`

- Flys in and out each png image file available in `root/media/images/company/` every 2 seconds with 0.4 seconds "fly time". Clicking an image leads to `current path/divisions/image file name`

`[ishow company/ - - - - x]`

- Show any image file available in `/media/images/company/` every 4 seconds with 1 second blending time. No interaction on click. The presentation has a frame.

## How to organize links

If you have all respective files to link to in a subfolder name your images to the respective file names and you are done. If you want to point to different folder locations or to external pages this is no problem either:

- Create a folder, for instance `root/thefolder/`. 

  > You can create a subfolder in any directory as well but don't do this in `home` directory. This may cause fatal error.

- Make sure that `thefolder` has no number if in `root`. Then it is hidden from navigation.

- Name the images of a presentation alphabetically or with leading numbers into the desired order. Keep in mind: first should be the tallest. 

- Create files with corresponding names in `thefolder`

- Redirect inside these files to the page, you want to address ultimately:

~~~markdown
---
Title: file name of the image |→ to be true: doesn't matter anyway
Redirect: /folder/            |→ a YELLOW main page
[or]                          |
Redirect: /folder/folder/file |→ specified YELLOW file
[or]                          |
Redirect: https://domain.tld/…|→ Any page in the WWW
---
Just a moment please…
~~~

"Just a moment" is for the possible case of page load delay – typically, you and your visitors will never see this. But you need something on the page, otherwise it can cause unwanted side effects.

> Be aware, that a file with redirection regardless to this will be shown in the navigation of a "visual" folder with the given title.

## More Effects

You can easily add effects. Copy a `case → break;` code block and try yourself. If you have found an impressing motion effect place it here as an [issue](https://github.com/BsNoSi/yellow-extension-ishow/issues). I will implement it in the standard code, to make »ishow« *update save* for you.

## History:

2020-10-17: API changes applied.

2020-10-15: Update to catch disfunction because of API-changes of Yellow.

2019-12-20: Initial (published) release.


## Developer

[Norbert Simon](https://nosi.de/)



