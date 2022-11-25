## Block-SectionMenu

This is a moodle block that draws a menu.

The menu has a Home link at the top which links to the course homepage.

Then it shows each section in a list. It checks that items are visible to the user.

At the bottom it will list each activity in Section 0.

it does not list activities within Section 1 or higher.

It adds a classname to the active link (sections or activities).

You can customise the block additional classname to help you target styling.

### Setup

Install the block in the usual manner (`/blocks/sectionmenu`) and then add an instance to the course. Next, edit the block settings and add a classname to help identify the block using theme CSS, maybe a name if you like, and then set its visibility to be 'any page' (which will be any page in the course, including activities).

### Styling

Each item is just a regular P tag with a A tag inside it.

Active nodes will have `class="active"` added to the P tag.

The Home, Sections and Activities sections are wrapped in div tags to help further denote the purpose of each. The rendered layout looks like this:

```html
<div class="card-text content mt-3">
<div class="top home">
<p class="current"><a href="/course/view.php?id=148&section=0">Home</a></p>
</div>

<div class="middle sections">
<p><a href="/course/view.php?id=148&section=1">Diagnosis</a></p>
<p><a href="/course/view.php?id=148&section=2">Fault finding</a></p>
<p><a href="/course/view.php?id=148&section=3">Software usage</a></p>
<p><a href="/course/view.php?id=148&section=4">Record storage</a></p>
</div>

<div class="bottom activities">
<p><a href="/mod/page/view.php?id=814">its on the homepage</a></p>
</div>
```

You can change the menu listing by editing the `main.mustache` file.

###

Licence: GPL3